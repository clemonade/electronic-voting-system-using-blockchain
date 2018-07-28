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

    },

    setStatus: function (message) {
        $('#status').html(message);
    },


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
