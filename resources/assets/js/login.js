import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let account;
let owner;

window.App = {
    start: function () {
        let self = this;

        Election.setProvider(web3.currentProvider);

        web3.eth.getAccounts(function (err, accs) {
            if (err != null) {
                self.setStatus('text-danger Error fetching accounts.');
                return;
            }

            if (accs.length === 0) {
                self.setStatus('text-warning Couldn\'t get any accounts. Ensure Ethereum client is configured correctly.');
                return;
            }

            account = accs[0];
        });

        self.populateAdmin();

        setInterval(function () {
            if (web3.eth.accounts[0] !== account) {
                account = web3.eth.accounts[0];
                self.populateCurrent(account);
            }
        }, 100);
    },

    //Only displays the last message.
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

    populateAdmin: function () {
        let self = this;
        let election;

        Election.deployed().then((instance) => {
            election = instance;

            election.owner.call().then((address) => {
                owner = address;
                $('#admin').val(address);

                self.populateCurrent(account);
            });
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving contract owner.');
        });
    },

    populateCurrent: function (acc) {
        let current = $('#current');
        current.val(acc);

        if (acc === owner) {
            current
                .removeClass('text-danger')
                .addClass('text-success');

            window.location.href = '/admin/dashboard';
        } else {
            current
                .removeClass('text-success')
                .addClass('text-danger');
        }
    },
};
