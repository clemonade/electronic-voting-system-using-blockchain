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

        console.log('hi');
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

    //Necessary alternative to form request validation fml
    validate: function () {
        let valid = true;
        let n = $('#nonce');
        let nonce = n.val();

        if (nonce === "") {
            valid = false;
            n.addClass('is-invalid');
        } else {
            n.removeClass('is-invalid');
        }

        return valid;
    },
};
