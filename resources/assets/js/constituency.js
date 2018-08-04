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

        self.populateConstituency();
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    populateConstituency: function () {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.getConstituency.call(code, {from: account})
        }).then((value) => {
            $('#code').html(code);
            if (value[0]) {
                $('#init').html('INITIALISED');
            } else {
                $('#init').html('UNINITIALISED');
            }
            $('#state').html(states[state]);
            $('#total').html(value[1].toNumber());
            $('#name').html(value[3]);
            $('#type').html(types[value[4].toNumber()]);

            self.populateCandidates(value[2].map(x => x.toNumber()));
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving initialisation status.');
        });
    },

    populateCandidates: function (candidates) {
        let self = this;
        let election;
        let promises = [];
        Election.deployed().then((instance) => {
            election = instance;
            for (let candidate of candidates) {
                promises.push(election.getCandidate.call(candidate, {from: account}))
            }

            Promise.all(promises).then(() => {
                let parties = [];
                for (let x = 0; x < promises.length; x++) {
                    promises[x].then((value) => {
                        parties.push((election.getParty.call(value[3].toNumber(), {from: account})))
                    })
                }

                Promise.all(parties).then(() => {
                    for (let x = 0; x < promises.length; x++) {
                        promises[x].then((value) => {
                            parties[x].then((party) => {
                                $('<tr/>')
                                    .appendTo($('#candidates tbody'))
                                    .append($('<td>')
                                        .text(value[1]))
                                    .append($('<td>')
                                        .text(party[1]))
                                    .append($('<td>')
                                        .text(value[0].toNumber()));
                            });
                        });
                    }
                });
            });
        })
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
