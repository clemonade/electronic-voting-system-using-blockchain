import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let account;
let owner;

//TODO Gotta fix function names dude.
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

            //web3.eth.getAccounts() only returns the one selected account
            //https://github.com/MetaMask/metamask-extension/issues/3207
            account = accs[0];
        });

        self.populateDashboard();

        //TODO Formulate a better alternative
        if (window.location.href.indexOf("admin") > -1) {
            setInterval(function () {
                if (web3.eth.accounts[0] !== account) {
                    account = web3.eth.accounts[0];
                    self.checkRedirect(account);
                }
            }, 100);
        }
    },

    //Only displays the last message.
    setStatus: function (message) {
        if (message[0] === ' ') {
            $('#status').text(message);
        } else {
            let bs4class = message.substr(0, message.indexOf(' '));
            let status = message.substr(message.indexOf(' ') + 1);

            $('#status')
                .removeClass()
                .addClass(bs4class)
                .text(status);
        }
    },

    populateDashboard: function () {
        let self = this;
        let election;

        Election.deployed().then((instance) => {
            election = instance;

            election.owner.call().then((address) => {
                owner = address;
                $('#admin').val(address);

                web3.eth.getBalance(address, (err, balance) => {
                    $('#balance').val(web3.fromWei(balance, "ether") + " ETH");
                });

                self.checkRedirect(account);
            });

            $('#address').val(election.address);

            self.populateInitialisation(federals);
            self.populateInitialisation(states);
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving contract information.');
        });
    },

    checkRedirect: function (acc) {
        let current = $('#current');
        current.val(acc);

        if (acc === owner) {
            current
                .removeClass('text-danger')
                .addClass('text-success');
        } else {
            current
                .removeClass('text-success')
                .addClass('text-danger');
        }

        //Can sometimes trigger a login-dashboard loop, might be caused by MetaMask
        if (window.location.href.indexOf("admin") > -1) {
            if (acc !== owner) {
                //window.location.href = '/admin';
            }
        }
    },

    populateInitialisation: function (constituencies) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            let promises = [];
            for (let constituency of constituencies) {
                let code = constituency.code;
                promises.push(election.getConstituency.call(code));
            }
            Promise.all(promises).then(() => {
                for (let x = 0; x < promises.length; x++) {
                    let state = constituencies[x].state;
                    let code = constituencies[x].code;
                    let name = constituencies[x].name;
                    let jcode = constituencies[x].code.replace(/\./g, '\\.').replace(/\//g, '\\/');
                    promises[x].then((value) => {
                        if (value[0] === false) {
                            $('#' + jcode)
                                .click(() => {
                                    self.initialiseConstituency(code, name)
                                });
                        } else {
                            $('#' + jcode)
                                .prop('disabled', true)
                                .text('Initialised')
                                .addClass('btn-outline-primary');
                            $('#' + jcode + 'V')
                                .removeAttr('disabled')
                                .removeClass('btn-outline-primary')
                                .addClass('btn-success');

                            //Only display seat winner for initialised constituencies
                            self.populateSeatWinner(state, jcode, value[2].map(x => x.toNumber()));
                        }
                    });
                }
            });
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving initialisation status.');
        });
    },

    //No duplicate abbreviations pls.
    populateSeatWinner: function (state, jcode, candidates) {
        //If no contesting candidates
        //Hot dang this conditional filtering.
        if (!Array.isArray(candidates) || !candidates.length) {
            return;
        }

        let self = this;
        let election;
        let level = 'F';
        let promises = [];

        if (jcode.includes('/')) {
            level = 'S';
        }

        Election.deployed().then((instance) => {
            election = instance;
            for (let candidate of candidates) {
                promises.push(election.getCandidate.call(candidate))
            }

            Promise.all(promises).then(() => {
                let winParty;
                let winVotes = 0;
                let votes = [];

                //Assign first contesting party as default
                promises[0].then((value) => {
                    winParty = value[3].toNumber();
                });

                for (let x = 0; x < promises.length; x++) {
                    votes.push(
                        promises[x].then((value) => {
                            let numVotes = value[0].toNumber();
                            let party = value[3].toNumber();

                            if (numVotes > winVotes) {
                                winVotes = numVotes;
                                winParty = party;
                            }

                            return election.getParty.call(party);
                        }).then((party) => {
                            let parties = [];

                            $('.' + level + state + 'N').each(function () {
                                parties.push($(this).text());
                            });

                            //The extra parentheses matters lol.
                            if (!(parties.indexOf(party[1]) > -1)) {
                                $('#' + level + state + 'T')
                                    .append($('<td>')
                                        .addClass(level + state)
                                        .append($('<small>')
                                            .append($('<span>')
                                                .addClass(level + state + 'N')
                                                .text(party[1]))
                                            .append(' (')
                                            .append($('<span>')
                                                .addClass(level + state + 'W')
                                                .text('0'))
                                            .append(')')
                                        ));
                            }
                        })
                    );
                }

                Promise.all(votes).then(() => {
                    return election.getParty.call(winParty);
                }).then((value) => {
                    $('#' + jcode + 'P')
                        .append($('<img>')
                            .prop('title', value[1])
                            .prop('src', '/storage/parties/' + value[0] + value[1] + '.jpg')
                            .prop('height', '50'));

                    $('.' + level + state).each(function () {
                        let party = $(this).find('.' + level + state + 'N').text();
                        if (value[1] === party) {
                            let seat = $(this).find('.' + level + state + 'W');
                            let count = seat.text();
                            count++;
                            seat.text(count);
                        }
                    });
                });
            });
        });
    },

    initialiseConstituency: function (code, name) {
        let self = this;
        let election;
        let type;

        if (code.includes('N')) {
            type = 1;
        } else {
            type = 0;
        }

        Election.deployed().then((instance) => {
            election = instance;
            return election.initialiseConstituency(type, code, name, {from: account});
        }).then(() => {
            self.setStatus('text-success Constituency ' + code + ' initialised successfully.')
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error initialising constituency.');
        });
    },
};
