//No ES6 support
const chai = require("chai");
const sha256 = require("js-sha256");

let Election = artifacts.require('./Election.sol');
let catchRevert = require("./exceptions.js").catchRevert;
let assert = chai.assert;

//TODO Fix assert handling messages
//TODO Convert promise syntax to async/await
//TODO Add more proofing for assertions
//TODO Asserts for events emitted?
contract('Election', function (accounts) {
    let election;
    let owner = accounts[0];
    let nonOwner = accounts[1];

    before(function () {
        return Election.deployed().then((instance) => {
            election = instance;
        });
    });

    it('sets contract owner', function () {
        let expected = owner;

        return election.owner.call().then((actual) => {
            assert.equal(actual, expected, ' Contract owner was not contract deployer');
        });
    });

    it('has 0 total votes upon deployment', function () {
        let expected = 0;

        return election.totalVotes.call().then((actual) => {
            assert.equal(actual.toNumber(), expected, 'Total votes was not ' + expected);
        });
    });

    it('initialises a constituency', function () {
        let code = 'P.001';
        let expected_initialised = true;
        let expected_numVotes = 0;
        let expected_name = 'Bukit Mertajam';
        let expected_type = 0;

        return election.initialiseConstituency(expected_type, code, expected_name, {from: owner})
            .then(() => {
                return election.getConstituency.call(code);
            }).then((value) => {
                let actual_initialised = value[0];
                let actual_numVotes = value[1].toNumber();
                let actual_candidates = value[2].map(x => x.toNumber());
                let actual_name = value[3];
                let actual_type = value[4].toNumber();

                assert.equal(actual_initialised, expected_initialised, 'Constituency was not initialised');
                assert.equal(actual_numVotes, expected_numVotes, 'Number of votes was not ' + expected_numVotes);
                assert.isEmpty(actual_candidates, 'Candidates array was not empty');
                assert.equal(actual_name, expected_name, 'Constituency name was not ' + expected_name);
                assert.equal(actual_type, expected_type, 'Constituency type was not ' + expected_type);
            });
    });

    it('should not allow initialisation of existing constituency', async function () {
        let code = 'P.001';
        let name = 'Bukit Mertajam';
        let type = 0;

        await catchRevert(election.initialiseConstituency(type, code, name, {from: owner}));
    });

    it('should not allow initialisation of unknown constituency type', async function () {
        let code = 'P.002';
        let name = 'Batu Kawan';
        let type = 10;

        await catchRevert(election.initialiseConstituency(type, code, name, {from: owner}));
    });

    it('registers a party', function () {
        let expected_name = 'AAA';
        let expected_abbreviation = 'A';

        return election.registerParty(expected_name, expected_abbreviation, {from: owner})
            .then(() => {
                return election.getParty.call(0);
            }).then((value) => {
                let actual_name = value[0];
                let actual_abbreviation = value[1];

                assert.equal(actual_name, expected_name, 'Party name was not ' + expected_name);
                assert.equal(actual_abbreviation, expected_abbreviation, 'Party abbreviation was not ' + expected_abbreviation);
            });
    });

    it('should not allow registration of existing party', async function () {
        let name = 'AAA';
        let abbreviation = 'A';

        await catchRevert(election.registerParty(name, abbreviation, {from: owner}));
    });

    it('registers a candidate', function () {
        let expected_code = 'P.001';
        let expected_name = 'AAA';
        let expected_party = 0;
        let expected_votes = 0;

        return election.registerCandidate(expected_code, expected_name, expected_party, {from: owner})
            .then(() => {
                return election.getConstituency.call(expected_code);
            }).then((value) => {
                let actual_candidates = value[2].map(x => x.toNumber());
                return election.getCandidate.call(actual_candidates[0]);
            }).then((value) => {
                let actual_votes = value[0].toNumber();
                let actual_name = value[1];
                let actual_valid = value[2];
                let actual_party = value[3].toNumber();
                let actual_code = value[4];

                assert.equal(actual_votes, expected_votes, '');
                assert.equal(actual_name, expected_name, '');
                assert.isTrue(actual_valid, '');
                assert.equal(actual_party, expected_party, '');
                assert.equal(actual_code, expected_code, '');
            });
    });

    it('should not allow registration of candidate for a non-initialised constituency', async function () {
        let expected_code = 'P.002';
        let expected_name = 'CCC';
        let expected_party = 0;

        await catchRevert(election.registerCandidate(expected_code, expected_name, expected_party, {from: owner}));
    });

    it('should not allow registration of candidate for an unregistered party', async function () {
        let expected_code = 'P.001';
        let expected_name = 'AAA';
        let expected_party = 10;

        await catchRevert(election.registerCandidate(expected_code, expected_name, expected_party, {from: owner}));
    });

    //TODO Check constituency/candidate vote counter
    it('votes for candidate', function () {
        //Setup
        let type = 1;
        let code = 'P.001/N.01';
        let constituency_name = 'Machang Bubok';
        let candidate_name = 'CCC';
        let party = 0;

        let hash = sha256('AAA' + '111111111111' + sha256('1234'));
        let expected_fvote = [0];
        let expected_svote = [1];

        return election.initialiseConstituency(type, code, constituency_name, {from: owner})
            .then(() => {
                return election.registerCandidate(code, candidate_name, party, {from: owner})
            }).then(() => {
                return election.vote(hash, expected_fvote, expected_svote, {from: owner});
            }).then(() => {
                return election.getVoter.call(hash);
            }).then((value) => {
                let actual_voted = value[0];
                let actual_fvalid = value[1];
                let actual_svalid = value[2];
                let actual_fvote = value[3].toNumber();
                let actual_svote = value[4].toNumber();

                assert.isTrue(actual_voted, '');
                assert.isTrue(actual_fvalid, '');
                assert.isTrue(actual_svalid, '');
                assert.equal(actual_fvote, expected_fvote[0], '');
                assert.equal(actual_svote, expected_svote[0], '');
            });
    });

    it('should not allow voting for a voter hash that has already voted', async function () {
        let hash = sha256('AAA' + '111111111111' + sha256('1234'));
        let fvote = [0];
        let svote = [1];

        await catchRevert(election.vote(hash, fvote, svote, {from: owner}));
    });

    it('should not allow initialisation of constituency by contract non-owner', async function () {
        let code = 'P.002';
        let name = 'Batu Kawan';
        let type = 0;

        await catchRevert(election.initialiseConstituency(type, code, name, {from: nonOwner}));
    });

    it('should not allow registration of party by contract non-owner', async function () {
        let name = 'BBB';
        let abbreviation = 'B';

        await catchRevert(election.registerParty(name, abbreviation, {from: nonOwner}));
    });

    it('should not allow registration of candidate by contract non-owner', async function () {
        let code = 'P.001';
        let name = 'CCC';
        let party = 0;

        await catchRevert(election.registerCandidate(code, name, party, {from: nonOwner}));
    });

    it('should not allow voting by contract non-owner', async function () {
        let hash = sha256('BBB' + '222222222222' + sha256('1234'));
        let fvote = [0];
        let svote = [1];

        await catchRevert(election.vote(hash, fvote, svote, {from: nonOwner}));
    });

    it('transfers contract ownership', function () {
        // noinspection UnnecessaryLocalVariableJS
        let expected_owner = nonOwner;

        return election.transferOwnership(expected_owner, {from: owner})
            .then(() => {
                return election.owner.call();
            }).then((actual) => {
                // noinspection UnnecessaryLocalVariableJS
                let actual_owner = actual;
                assert.equal(actual_owner, expected_owner, '');
            });
    });

    //TODO Check onlyOwner modifier
    it('renounces contract ownership', function () {
        //Setup
        let owner = nonOwner;
        let expected_address = 0;

        return election.renounceOwnership({from: owner})
            .then(() => {
                return election.owner.call();
            }).then((actual) => {
                // noinspection UnnecessaryLocalVariableJS
                let actual_address = actual;
                assert.equal(actual_address, expected_address, '');
            });
    });
});
