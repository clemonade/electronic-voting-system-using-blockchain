import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);

let totalvotes = 0;
let totalturnout = 0;

window.App = {
    start: function () {
        let self = this;

        Election.setProvider(web3.currentProvider);

        //This is cancer.
        $('#turnout').text('0% (0)');
        $('#spoilt').text('0% (0)');

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
            return election.getConstituency.call(code)
        }).then((value) => {
            let turnout;

            totalturnout = value[1].toNumber();

            if (parseInt(count) === 0) {
                turnout = 0;
            } else {
                turnout = Math.round(totalturnout / parseInt(count) * 100);
            }

            $('#turnout').text(turnout + '% (' + value[1].toNumber() + ')');
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
                                totalvotes = 0;

                                $('.votes').each(function () {
                                    totalvotes += parseInt($(this).html());
                                });

                                if (totalturnout !== 0) {
                                    let spoilt = Math.round((totalturnout - totalvotes) / totalturnout * 100);
                                    $('#spoilt').text(spoilt + '% (' + (totalturnout - totalvotes) + ')');
                                }
                            });
                        });
                    }
                });
            });
        });
    },
};
