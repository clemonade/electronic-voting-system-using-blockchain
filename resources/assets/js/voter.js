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
            div.hide();
            $('#stateconstituency').empty();
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
            let code = constituency.code;
            if (code.includes(federal.code)) {
                $('<option/>')
                    .val(constituency.id)
                    .text(constituency.code + ' ' + constituency.name)
                    .appendTo(element);
            }
        }
    },

    validate: function () {
        let self = this;
        let valid = true;

        //j.
        let jname = $('#name');
        let jnric = $('#nric');
        let jnonce = $('#nonce');

        let name = jname.val().toUpperCase();
        let nric = jnric.val();
        let nonce = jnonce.val();

        if (name === "") {
            valid = false;
            jname.addClass('is-invalid');
        } else {
            jname.removeClass('is-invalid');
        }

        if (nric === "") {
            valid = false;
            jnric.addClass('is-invalid');
        } else {
            jnric.removeClass('is-invalid');
        }

        if (nonce === "") {
            valid = false;
            jnonce.addClass('is-invalid');
        } else {
            jnonce.removeClass('is-invalid');
        }

        if (valid) {
            self.verify(name, nric, nonce);
        }
    },

    verify: function (name, nric, nonce) {
        let self = this;
        let election;

        let hash = sha256(name + nric + sha256(nonce));

        Election.deployed().then((instance) => {
            election = instance;
            return election.getVoter.call(hash, {from: account});
        }).then((value) => {
            if (value[0]) {
                $('#bool')
                    .val('VOTED')
                    .removeClass('text-danger')
                    .addClass('text-success');
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

                self.setStatus('text-success Voter info found.');
            } else {
                $('#bool')
                    .val('DID NOT VOTE')
                    .removeClass('text-success')
                    .addClass('text-danger');
                $('#hash').val('INAPPLICABLE');
                $('#federal').val('INAPPLICABLE');
                $('#state').val('INAPPLICABLE');

                self.setStatus('text-warning Voter info not found.');
            }
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving voter info.');
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
