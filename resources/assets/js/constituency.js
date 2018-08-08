/* eslint-disable no-undef */

import {default as Web3} from 'web3';
import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let accounts;
let account;
let totalturnout = 0;

window.App = {
    start: function () {
        let self = this;

        Election.setProvider(web3.currentProvider);

        web3.eth.getAccounts(function (err, accs) {
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

        self.populateConstituency();
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    populateConstituency: function () {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            return election.getConstituency.call(code, {from: account})
        }).then((value) => {
            let valid = 0;
            let turnout = Math.round(value[1].toNumber() / count * 100) + '%';
            totalturnout = value[1].toNumber();

            $('#turnout').text(turnout + ' (' + value[1].toNumber() + ')');
            $('#name').text(value[3]);
            $('#level').html(types[value[4].toNumber()].toUpperCase());

            if (value[0]) {
                $('#init').html('INITIALISED');
            } else {
                $('#init').html('UNINITIALISED');
            }

            self.populateCandidates(value[2].map(x => x.toNumber()));
        }).catch(function (e) {
            console.log(e);
            self.setStatus('Error retrieving initialisation status.');
        });
    },

    populateCandidates: function (candidates) {
        let self = this;
        let election;
        let promises = [];
        Election.deployed().then((instance) => {
            election = instance;
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
                                $('<tr/>')
                                    .appendTo($('#candidates tbody'))
                                    .append($('<td>')
                                        .prop('align', 'center')
                                        .append($('<figure>')
                                            .addClass('figure')
                                            .append($('<img>')
                                                .addClass('figure-img')
                                                .prop('title', party[0])
                                                .prop('src', '/storage/parties/' + party[0] + party[1] + '.jpg')
                                                .prop('height', '50'))
                                            .append($('<figcaption>')
                                                .addClass('figure-caption')
                                                .text(party[1]))))
                                    .append($('<td>')
                                        .text(value[1]))
                                    .append($('<td>')
                                        .addClass('votes text-right')
                                        .text(value[0].toNumber()));
                            }).then(() => {
                                let votes = 0;

                                $('.votes').each(function () {
                                    votes += parseInt($(this).html());
                                });

                                let spoilt = Math.round((totalturnout - votes) / totalturnout * 100) + '%';
                                $('#spoilt').text(spoilt + ' (' + (totalturnout - votes) + ')');
                            });
                        });
                    }
                });
            });
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
