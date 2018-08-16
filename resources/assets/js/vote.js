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

        self.populateCandidates('federalcandidates', federal);

        if (state == null) {
            $('#statecandidatediv').hide();
        } else {
            self.populateCandidates('statecandidates', state);
        }

        setInterval(function () {
            if (web3.eth.accounts[0] !== account) {
                account = web3.eth.accounts[0];
                self.checkRedirect(account);
            }
        }, 100);
    },

    setStatus: function (message) {
        $('#status').html(message);
    },

    checkRedirect: function (acc) {
        if (acc !== owner) {
            window.location.href = '/admin';
        }
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
                                $('<tr/>')
                                    .appendTo($('#' + id + ' tbody'))
                                    .append($('<td/>')
                                        .addClass('text-center')
                                        .text(value[1]))
                                    .append($('<td/>')
                                        .prop('align', 'center')
                                        .append($('<img>')
                                            .prop('title', party[0])
                                            .prop('src', '/storage/parties/' + party[0] + party[1] + '.jpg')
                                            .prop('height', '50')))
                                    .append($('<td/>')
                                        .append($('<input/>')
                                            .prop('style', 'width:100%;height:100%')
                                            .prop('type', 'checkbox')
                                            .prop('class', 'checkbox')
                                            .prop('name', id + '[]')
                                            .prop('id', id + x)
                                            .val(candidates[x])));
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
        let federalelement = $('input[name="federalcandidates[]"]');
        let stateelement = $('input[name="statecandidates[]"]');

        $('input[name="federalcandidates[]"]:checked').each(function () {
            federalcandidates.push($(this).val());
        });

        $('input[name="statecandidates[]"]:checked').each(function () {
            statecandidates.push($(this).val());
        });

        federalcandidates = self.processVotes(federalelement, federalcandidates);
        statecandidates = self.processVotes(stateelement, statecandidates);

        //For testing purposes
        // console.log(federalcandidates);
        // console.log(statecandidates);

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

    //TODO Requires thorough testing
    //Crucial role to the functionality of voting.
    processVotes: function (element, array) {
        let x = [];
        element.each(function () {
            x.push($(this).val());
        });

        //If choice is not exactly one candidate
        if (array.length !== 1) {
            //If there are candidates contesting
            if (x.length !== 0) {
                //If there is only one contesting candidate
                if (x.length === 1) {
                    //Only one contesting candidate, unselected
                    return [x[0], x[0] + x[0]];
                }

                //More than one contesting candidate, unselected/more than one selected
                return x;
            }
            //Level does not exist
            return [0, 0, 0];
        }

        //Only one candidate selected
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
            self.setStatus('Error retrieving voter information.');
        });
    }
};
