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

        self.populateParties();

        $('#state').on('change', function () {
            let element = $('#level');
            let level = element.val();

            if (level === null) {
                $('#level option[value="0"]').prop('selected', true);
            }

            //TODO Needs a more solid solution
            if (this.value === '14') {
                $('#level option[value="0"]').prop('selected', true);
                $('#level option[value="1"]').prop('disabled', true);

            } else {
                $('#level option[value="1"]').prop('disabled', false);
            }

            level = element.val();
            self.populateConstituencies(level);
        });

        $('#constiteuncy').on('change', function () {
            self.populateCandidates($(this).val());
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
        let self = this;
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

        self.populateCandidates(dropdown.val());
    },

    populateCandidates: function (constituency) {
        let self = this;
        let election;
        let dropdown = $('#candidate');

        dropdown.empty();

        Election.deployed().then((instance) => {
            election = instance;
            return election.getConstituency.call(constituency);
        }).then((value) => {
            let promises = [];
            let candidates = value[2].map(x => x.toNumber());
            for (let candidate of candidates) {
                promises.push(election.getCandidate.call(candidate))
            }
            Promise.all(promises).then(() => {
                let parties = [];
                for (let x = 0; x < promises.length; x++) {
                    promises[x].then((value) => {
                        parties.push((election.getParty.call(value[3].toNumber())))
                    })
                }

                Promise.all(parties).then(() => {
                    for (let x = 0; x < promises.length; x++) {
                        promises[x].then((value) => {
                            parties[x].then((party) => {
                                $('<option/>')
                                    .text(value[1] + ' (' + party[1] + ')')
                                    .val(candidates[x])
                                    .appendTo(dropdown);
                            });
                        });
                    }
                });
            });

        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error retrieving candidates.');
        });
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
            self.setStatus('text-success Candidate ' + name + ' (' + constituency + ') registered successfully.');
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error registering candidate.');
        });
    },

    //DEV
    validateIncrement: function () {
        let self = this;
        let valid = true;

        let s = $('#state');
        let l = $('#level');
        let co = $('#constiteuncy');
        let ca = $('#candidate');
        let v = $('#votes');

        let state = s.val();
        let level = l.val();
        let constituency = co.val();
        let candidate = ca.val();
        let votes = v.val();

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
            co.addClass('is-invalid');
        } else {
            co.removeClass('is-invalid');
        }

        if (candidate === null) {
            valid = false;
            ca.addClass('is-invalid');
        } else {
            ca.removeClass('is-invalid');
        }

        if (votes === "") {
            valid = false;
            $('#voteserror').text('Votes is required.');
            v.addClass('is-invalid');
        } else {
            if (isNaN(votes)) {
                valid = false;
                $('#voteserror').text('Votes is not a number.');
                v.addClass('is-invalid');
            } else {
                v.removeClass('is-invalid');
            }
        }

        if (valid) {
            self.add(candidate, votes);
        }
    },

    add: function (candidate, votes) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.addVotes(candidate, votes, {from: account});
        }).then(() => {
            self.setStatus('text-success ' + votes + ' votes incremented successfully.');
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error incrementing candidate votes.');
        });
    },
};
