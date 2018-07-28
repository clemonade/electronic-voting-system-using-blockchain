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

        web3.eth.getAccounts((err, accs) => {
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

        self.populateParties();
        self.populateConstituencies($('#level').val());
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    populateParties: function () {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.getPartiesLength.call({from: account});
        }).then((value) => {
            let promises = [];
            let partiesLength = value.toNumber();
            for (let x = 0; x < partiesLength; x++) {
                promises.push(election.getParty.call(x, {from: account}));
            }
            Promise.all(promises).then(() => {
                for (let x = 0; x < partiesLength; x++) {
                    promises[x].then((value) => {
                        $('<option/>')
                            .text(value[0] + ' (' + value[1] + ')')
                            .val(x)
                            .appendTo($('#party'));
                    });
                }
            });
        }).catch((e) => {
            console.log(e);
            self.setStatus('Error showing parties; see log.');
        });
    },

    populateConstituencies: function (value) {
        let self = this;
        if (value === '0') {
            self.populateDropdown(federals);
        } else {
            self.populateDropdown(states);
        }
    },

    populateDropdown: function (constituencies) {
        $('#constiteuncy').empty();
        for (let constituency of constituencies) {
            $('<option/>')
                .val(constituency.code)
                .text(constituency.code + ' ' + constituency.name)
                .appendTo($('#constiteuncy'));
        }
    },

    registerCandidate: function () {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            let constituencyCode = $('#constiteuncy').val();
            let candidateName = $('#name').val();
            let partyId = $('#party').val();
            return election.registerCandidate(constituencyCode, candidateName, partyId, {from: account});
        }).then(() => {
            self.setStatus('Candidate registered.');
        }).catch((e) => {
            console.log(e);
            self.setStatus('Error showing constituencies; see log.');
        });
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
