/* eslint-disable no-undef */
import {default as Web3} from 'web3';
import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';
import {sha256} from "js-sha256";

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
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    populateFederalDropdown: function (state_id) {
        let self = this;
        let element = $('#federalconstituency');
        let div = $('.stateconstituency');

        element.empty();
        div.show();

        for (let constituency of federals) {
            if (constituency.state === state_id) {
                $('<option/>')
                    .val(constituency.id)
                    .text(constituency.code + ' ' + constituency.name)
                    .appendTo(element);
            }
        }

        if (state_id === '14') {
            div.hide()
        } else {
            self.populateStateDropdown(element.val())
        }
    },

    populateStateDropdown: function (federal_id) {
        let element = $('#stateconstituency');

        element.empty();

        let federal;
        for (let constituency of federals) {
            if (constituency.id === parseInt(federal_id)) {
                federal = constituency;
            }
        }

        for (let constituency of states) {
            if (constituency.code.includes(federal.code)) {
                $('<option/>')
                    .val(constituency.id)
                    .text(constituency.code + ' ' + constituency.name)
                    .appendTo(element);
            }
        }
    },

    //TODO Reassess error handling
    verify: function () {
        let self = this;
        let election;

        let name = $('#name').val();
        let nric = $('#nric').val();
        let nonce = $('#nonce').val();
        let hash = sha256(name + nric + sha256(nonce));

        Election.deployed().then((instance) => {
            election = instance;
            return election.getVoter.call(hash, {from: account});
        }).then((value) => {
            if (value[0]) {
                self.setStatus('');

                $('#hash').val(hash);

                if (value[1]) {
                    $('#federal').val('VALID');
                } else {
                    $('#federal').val('INVALID');
                }

                if (value[2]) {
                    $('#state').val('VALID');
                } else {
                    $('#state').val('INVALID');
                }
            } else {
                self.setStatus('Invalid voter info.');
            }
            console.log(value[0]);
            console.log(value[1]);
            console.log(value[2]);
            console.log(value[3].toNumber());
            console.log(value[4].toNumber());
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving voter info.');
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