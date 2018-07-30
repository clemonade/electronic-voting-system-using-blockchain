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

        self.getConstituency(code);
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    getConstituency: function (code) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.getConstituency.call(code, {from: account})
        }).then((value) => {
            console.log(value[0]);
            console.log(value[1].toNumber());
            console.log(value[2].map(x => x.toNumber()));
            console.log(value[3]);
            console.log(value[4].toNumber());

            $('#code').html(code);
            if ('#' + value[0]) {
                $('#init').html('INITIALISED');
            } else {
                $('#init').html('UNINITIALISED');
            }
            $('#total').html(value[1].toNumber());
            $('#candidates').html(value[2].map(x => x.toNumber()));
            $('#name').html(value[3]);
            $('#type').html(types[value[4].toNumber()]);


        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving initialisation status.');
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
