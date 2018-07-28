/* eslint-disable no-undef */
import {default as Web3} from 'web3';
import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let accounts;
let account;

window.App = {
    start: function () {
        //alert(window.location);
        let self = this;

        Election.setProvider(web3.currentProvider);

        web3.eth.getAccounts(function (err, accs) {
            if (err != null) {
                alert('There was an error fetching your accounts.');
                return;
            }

            if (accs.length === 0) {
                alert('Couldn\'t get any accounts! Make sure your Ethereum client is configured correctly.');
                return;
            }

            accounts = accs;
            account = accounts[0];
        });

        self.setInitialisation(federals);
        self.setInitialisation(states);
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    setInitialisation: function (constituencies) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            let promises = [];
            for (let constituency of constituencies) {
                let code = constituency.code;
                promises.push(election.getConstituency.call(code, {from: account}));
            }
            Promise.all(promises).then(() => {
                //console.log(promises);
                for (let x = 0; x < promises.length; x++) {
                    let code = constituencies[x].code;
                    let name = constituencies[x].name;
                    let jcode = constituencies[x].code.replace(/\./g, '\\.');
                    promises[x].then((value) => {
                        if (value[0] === false) {
                            $('#' + jcode)
                                .click(() => {
                                    self.initialiseConstituency(code, name)
                                });
                        } else {
                            $('#' + jcode)
                                .text('Initialised')
                                .attr('disabled', 'disabled');
                            $('#' + jcode + 'V')
                                .removeAttr('disabled')
                                .click(() => {
                                    self.redirectVerify(code);
                                });
                        }
                    });
                }
            });
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving initialisation status.');
        });
    },

    initialiseConstituency: function (code, name) {
        let self = this;
        let election;
        let type;

        if (code.includes('N')) {
            type = 1;
        } else {
            type = 0;
        }

        Election.deployed().then((instance) => {
            election = instance;
            return election.initialiseConstituency(type, code, name, {from: account});
        }).then(() => {
            self.setStatus('Constituency ' + code + ' initialised.')
        }).catch((e) => {
            console.log(e);
            self.setStatus('Error initialising constituency; see log.');
        });
    },

    redirectVerify: function (code) {
        let self = this;
        let address = code.split('_');
        window.location.href = '/admin/verify/' + address[0] + '/' + address[1];
    }
};

$(document).ready(() => {
    if (typeof web3 !== 'undefined') {
        console.warn('Using web3 detected from external source.');
        window.web3 = new Web3(web3.currentProvider);
    } else {
        console.warn('No web3 detected. Falling back to http://127.0.0.1:7545.');
        window.web3 = new Web3(new Web3.providers.HttpProvider('http://127.0.0.1:7545'));
    }

    App.start();
});
