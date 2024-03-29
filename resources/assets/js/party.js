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

        self.populateParties('parties');

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
    //Nope, now used for frontend validation as well.
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

    //TODO Remove instances of status message passing through controllers
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
