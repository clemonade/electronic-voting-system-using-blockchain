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

        self.populateParties('parties');

        // let blockNumber = 'latest';
        // Election.deployed().then(function (instance) {
        //     let event = instance.NewParty({}, {fromBlock: blockNumber});
        //     event.watch((error, result) => {
        //         blockNumber = result.blockNumber + 1;
        //         if (!error) {
        //             console.log(result.args.partyId + ' ' + result.args.name + ' ' + result.args.abbreviation);
        //         }
        //     });
        // });

        // $('#status').html('<img src="/storage/util/loading.gif" height="10">');
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

    populateParties: function (id) {
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
                $('#' + id + ' tbody').html('');
                for (let x = 0; x < partiesLength; x++) {
                    promises[x].then((value) => {
                        $('<tr/>')
                            .appendTo($('#' + id + ' tbody'))
                            .append($('<td>')
                                .text(x + 1 + '.'))
                            .append($('<td>')
                                .text(value[0]))
                            .append($('<td>')
                                .addClass('text-center')
                                .text(value[1]))
                            .append($('<td>')
                                .prop('align', 'center')
                                .append($('<img>')
                                    .prop('src', '/storage/parties/' + value[0] + value[1] + '.jpg')
                                    .prop('height', '50'))
                            );
                    });
                }
            });
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error retrieving parties.');
        });
    },

    //TODO Integrate Laravel form request validation with MetaMask prompt trigger
    //Purely used to trigger/stop MetaMask prompt
    //This is dumb.
    //Nope, now used for frontend validation as well
    validate: function () {
        let self = this;
        let valid = true;

        let n = $('#name');
        let a = $('#abbreviation');
        let i = $('#image');

        let name = n.val();
        let abbreviation = a.val();
        let image = i.val();

        if (name === "") {
            valid = false;
            n.addClass('is-invalid');
        } else {
            n.removeClass('is-invalid');
        }

        //DEV
        let regex = new RegExp();
        //let regex = new RegExp(/^[a-zA-Z]+$/);

        if (abbreviation === "") {
            valid = false;
            a.addClass('is-invalid');
            $('#abbreviationfeedback').text('Abbreviation is required.')
        } else {
            if (regex.test(abbreviation)) {
                a.removeClass('is-invalid');
            } else {
                valid = false;
                a.addClass('is-invalid');
                $('#nricfeedback').text('Abbreviation is invalid.');
            }
        }

        //Does not test for file type. Upload to high hell.
        if (image === "") {
            valid = false;
            i.addClass('is-invalid');
        } else {
            i.removeClass('is-invalid');
        }

        if (valid) {
            self.registerParty(name, abbreviation);
        }

        return valid;
    },

    registerParty: function (name, abbreviation) {
        let self = this;
        let election;

        Election.deployed().then((instance) => {
            election = instance;
            return election.registerParty(name, abbreviation, {from: account});
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error registering party.');
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
