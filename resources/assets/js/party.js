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

        // Bootstrap the Election abstraction for Use.
        Election.setProvider(web3.currentProvider);

        // Get the initial account balance so it can be displayed.
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

        self.showParties();
    },

    setStatus: function (message) {
        $('#status').innerHTML = message;
    },

    registerParty: function () {
        let self = this;
        let election;
        Election.deployed().then(function (instance) {
            election = instance;
            let name = $('#name').val();
            let abbreviation = $('#abbreviation').val();
            return election.registerParty(name, abbreviation, {from: account});
        }).then(function () {
            self.setStatus('Transaction complete.');
            self.showParties();
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error registering party; see log.');
        });
    },

    showParties: function () {
        let self = this;
        let election;
        Election.deployed().then(function (instance) {
            election = instance;
            return election.getPartiesLength.call({from: account});
        }).then(function (value) {
            let promises = [];
            let partiesLength = value.toNumber();
            console.log(partiesLength);
            for (let x = 0; x < partiesLength; x++) {
                promises.push(election.getParty.call(x, {from: account}));
            }
            Promise.all(promises).then(() => {
                $('#list').html('');
                for (let x = 0; x < partiesLength; x++) {
                    promises[x].then(function (value) {
                        console.log(value[0] + value[1]);
                        let entry = $('<li/>').text(value[0] + ' ' + value[1]);
                        entry.appendTo($('#list'));
                    });
                }
            });
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error showing parties; see log.');
        });
    }
};

$(document).ready(function () {
    if (typeof web3 !== 'undefined') {
        console.warn('Using web3 detected from external source.');
        window.web3 = new Web3(web3.currentProvider);
    } else {
        console.warn('No web3 detected. Falling back to http://127.0.0.1:7545.');
        window.web3 = new Web3(new Web3.providers.HttpProvider('http://127.0.0.1:7545'));
    }

    App.start();
});
