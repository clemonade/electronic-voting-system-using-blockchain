import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let account;
let owner;

window.App = {
    start: function () {
        let self = this;
        let election;

        Election.setProvider(web3.currentProvider);

        Election.deployed().then((instance) => {
            election = instance;
            election.owner.call().then((address) => {
                owner = address;
            })
        });

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

        setInterval(function () {
            if (web3.eth.accounts[0] !== account) {
                account = web3.eth.accounts[0];
                self.checkRedirect(account);
            }
        }, 100);
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

    checkRedirect: function (acc) {
        if (acc !== owner) {
            //window.location.href = '/admin';
        }
    },

    validate: function () {
        let self = this;
        let valid = true;

        let n = $('#name');
        let i = $('#nric');

        let name = n.val();
        let nric = i.val();

        if (name === "") {
            valid = false;
            n.addClass('is-invalid');
        } else {
            n.removeClass('is-invalid');
        }

        //DEV
        let regex = new RegExp();
        //let regex = new RegExp(/^([0-9]{2})([0-1]{1})([0-9]{1})([0-3]{1})([0-9]{1})([0-9]{6})$/);

        if (nric === "") {
            valid = false;
            i.addClass('is-invalid');
            $('#nricfeedback').text('NRIC is required.')
        } else {
            if (regex.test(nric)) {
                i.removeClass('is-invalid');
            } else {
                valid = false;
                i.addClass('is-invalid');
                $('#nricfeedback').text('NRIC is invalid.');
            }
        }

        return valid;
    },
};
