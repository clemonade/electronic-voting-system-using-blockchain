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

        self.populateParties();

        $('#state').on('change', function () {
            // noinspection JSJQueryEfficiency
            let level = $('#level').val();

            if (level === null) {
                $('#level option[value="0"]').prop('selected', true);
            }

            if (this.value === '14') {
                $('#level option[value="0"]').prop('selected', true);
                $('#level option[value="1"]').prop('disabled', true);

            } else {
                $('#level option[value="1"]').prop('disabled', false);
            }

            // noinspection JSJQueryEfficiency
            level = $('#level').val();
            self.populateConstituencies(level);
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

    populateParties: function () {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.getPartiesLength.call();
        }).then((value) => {
            let promises = [];
            let partiesLength = value.toNumber();
            for (let x = 0; x < partiesLength; x++) {
                promises.push(election.getParty.call(x));
            }
            Promise.all(promises).then(() => {
                for (let x = 0; x < partiesLength; x++) {
                    promises[x].then((value) => {
                        $('<option/>')
                            .text(value[0] + ' (' + value[1] + ')')
                            .val(x)
                            .appendTo($('#party'));
                    });
                }
            });
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error retrieving parties.');
        });
    },

    populateConstituencies: function (value) {
        let self = this;
        if (value === '0') {
            self.populateDropdown(federals);
        } else {
            self.populateDropdown(states);
        }
    },

    populateDropdown: function (constituencies) {
        let dropdown = $('#constiteuncy');

        dropdown.empty();
        for (let constituency of constituencies) {
            if (constituency.state === $('#state').val()) {
                $('<option/>')
                    .val(constituency.code)
                    .text(constituency.code + ' ' + constituency.name)
                    .appendTo(dropdown);
            }
        }
    },

    //Actually used for validation
    //God save us all.
    validate: function () {
        let self = this;
        let valid = true;

        //Fite me.
        let c = $('#constiteuncy');
        let n = $('#name');
        let p = $('#party');
        let s = $('#state');
        let l = $('#level');

        let constituency = c.val();
        let name = n.val().toUpperCase();
        let party = p.val();
        let state = s.val();
        let level = l.val();

        if (name === "") {
            valid = false;
            n.addClass('is-invalid');
        } else {
            n.removeClass('is-invalid');
        }

        if (party === null) {
            valid = false;
            p.addClass('is-invalid');
        } else {
            p.removeClass('is-invalid');
        }

        if (state === null) {
            valid = false;
            s.addClass('is-invalid');
        } else {
            s.removeClass('is-invalid');
        }

        if (level === null) {
            valid = false;
            l.addClass('is-invalid');
        } else {
            l.removeClass('is-invalid');
        }

        if (constituency === null) {
            valid = false;
            c.addClass('is-invalid');
        } else {
            c.removeClass('is-invalid');
        }

        if (valid) {
            self.registerCandidate(constituency, name, party);
        }
    },

    registerCandidate: function (constituency, name, party) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.registerCandidate(constituency, name, party, {from: account});
        }).then(() => {
            self.setStatus('text-success Candidate registered successfully.');
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error registering candidate.');
        });
    }
};
