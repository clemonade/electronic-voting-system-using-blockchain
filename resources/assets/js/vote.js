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

        self.populateConstituencies();
        self.populateCandidates('federalcandidates', federal);
        self.populateCandidates('statecandidates', state);
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    populateConstituencies: function () {
        $('#federal').val(federal['code'] + ' ' + federal['name']);
        $('#state').val(state['code'] + ' ' + state['name']);
    },

    populateCandidates: function (id, constituency) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.getConstituency.call(constituency['code'], {from: account})
        }).then((value) => {
            let promises = [];
            let candidates = value[2].map(x => x.toNumber());
            for (let candidate of candidates) {
                promises.push(election.getCandidate.call(candidate, {from: account}))
            }
            Promise.all(promises).then(() => {
                let parties = [];
                for (let x = 0; x < promises.length; x++) {
                    promises[x].then((value) => {
                        parties.push((election.getParty.call(value[3].toNumber(), {from: account})))
                    })
                }

                Promise.all(parties).then(() => {
                    for (let x = 0; x < promises.length; x++) {
                        promises[x].then((value) => {
                            parties[x].then((party) => {
                                $('<label/>')
                                    .appendTo($('#' + id))
                                    .prop('for', id + x)
                                    .text(value[1] + ' ' + party[1])
                                    .append($('<input/>')
                                        .prop('type', 'checkbox')
                                        .prop('class', 'checkbox')
                                        .prop('name', id + '[]')
                                        .prop('id', id + x)
                                        .val(candidates[x]));
                            });
                        });
                    }
                });
            });

        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving candidates.');
        });
    },

    vote: function () {
        let self = this;
        let election;
        let federalcandidates = [];
        let statecandidates = [];

        $('input[name="federalcandidates[]"]:checked').each(function () {
            federalcandidates.push($(this).val());
        });

        $('input[name="statecandidates[]"]:checked').each(function () {
            statecandidates.push($(this).val());
        });

        federalcandidates = self.processVotes(federalcandidates);
        statecandidates = self.processVotes(statecandidates);

        Election.deployed().then((instance) => {
            election = instance;
            return election.vote(hash, federalcandidates, statecandidates, {from: account});
        }).then(() => {
            return election.getVoter.call(hash, {from: account});
        }).then((value) => {
            console.log(value);
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error casting vote.');
        });
    },

    processVotes: function (array) {
        if (array.length !== 1) {
            return [0, 0, 0];
        }

        return array;
    },

    test: function () {
        let self = this;
        let election;
        let nonce = hash;

        Election.deployed().then((instance) => {
            election = instance;
            return election.getVoter.call(nonce, {from: account});
        }).then((value) => {
            console.log(value[0]);
            console.log(value[1]);
            console.log(value[2]);
            console.log(value[3].toNumber());
            console.log(value[4].toNumber());
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving voter info.');
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
