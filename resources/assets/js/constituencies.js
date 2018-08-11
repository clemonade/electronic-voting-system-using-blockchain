/* eslint-disable no-undef */
import {default as Web3} from 'web3';
import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let accounts;
let account;

window.App = {
    start: function () {
        let self = this;

        Election.setProvider(web3.currentProvider);

        if (window.location.pathname.includes('admin')) {

        }
        web3.eth.getAccounts(function (err, accs) {
            if (err != null) {
                self.setStatus(' There was an error fetching accounts.');
                return;
            }

            if (accs.length === 0) {
                self.setStatus(' Couldn\'t get any accounts. Ensure Ethereum client is configured correctly.');
                return;
            }

            accounts = accs;
            account = accounts[0];
        });

        self.populateInfo();
        self.setInitialisation(federals);
        self.setInitialisation(states);
    },

    setStatus: function (message) {
        if (message[0] === ' ') {
            $('#status').text(message);
        } else {
            let bs4class = message.substr(0, message.indexOf(' '));
            let status = message.substr(message.indexOf(' ') + 1);

            $('#status')
                .addClass(bs4class)
                .text(status);
        }
    },

    populateInfo: function () {
        let self = this;
        let election;

        Election.deployed().then((instance) => {
            election = instance;

            election.owner.call().then((address) => {
                $('#admin').val(address);

                web3.eth.getBalance(address, (err, balance) => {
                    $('#balance').val(web3.fromWei(balance, "ether") + " ETH");
                });
            });

            $('#address').val(election.address);

        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving contract information.');
        });
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
                for (let x = 0; x < promises.length; x++) {
                    let code = constituencies[x].code;
                    let name = constituencies[x].name;
                    let jcode = constituencies[x].code.replace(/\./g, '\\.').replace(/\//g, '\\/');
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
                                .removeAttr('disabled');
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
            self.setStatus('Error initialising constituency.');
        });
    },
};

$(document).ready(() => {
    if (typeof web3 !== 'undefined') {
        console.warn('Using web3 detected from external source.');
        window.web3 = new Web3(web3.currentProvider);
    } else {
        console.warn('No web3 detected. Falling back to http://127.0.0.1:8545.');
        window.web3 = new Web3(new Web3.providers.HttpProvider('http://127.0.0.1:8545'));
    }

    App.start();
});
