pragma solidity ^0.4.24;

import "./Ownable.sol";

contract Election is Ownable {
    enum ConstituencyType {FEDERAL, STATE}
    uint totalVotes;

    modifier hasNotVoted(string _nonce){
        require(votes[_nonce].hasVoted != true);
        _;
    }

    modifier isValidConstituencyType(uint _constituencyType){
        require(uint(ConstituencyType.STATE) >= _constituencyType);
        _;
    }

    modifier isValidParty (uint _party){
        require(parties.length > _party);
        _;
    }

    modifier isValidCandidate(uint _candidateId){
        require(candidates.length > _candidateId);
        _;
    }

    struct Constituency {
        uint numVotes;
        ConstituencyType constituencyType;
        Candidate[] candidates;
    }

    struct Party {
        string name;
        string abbreviation;
    }

    struct Candidate {
        string name;
        uint numVotesFederal;
        uint numVotesState;
        bool validCandidate;
    }

    struct Voter {
        bool hasVoted;
        uint votedForFederal;
        uint votedForState;
    }

    Candidate[] candidates;
    Party[] parties;
    uint[] candidateToParty;

    mapping(string => Voter) votes;
    mapping(string => Constituency) constituencies;

    constructor() public {
        totalVotes = 0;
    }

    function registerParty(string _partyName, string _partyAbbreviation) onlyOwner() {
        Party memory party = Party(_partyName, _partyAbbreviation);
        parties.push(party);
    }

    function registerCandidate(
        uint _constituencyType,
        string _constituencyCode,
        string _candidateName,
        uint _party) onlyOwner() isValidConstituencyType(_constituencyType) isValidParty(_party) {
        uint candidateId = candidates.length;
        Candidate memory candidate = Candidate(_candidateName, 0, 0, true);
        candidateToParty[candidateId] = _party;
        candidates.push(candidate);

        if (constituencies[_constituencyCode].candidates.length == 0) {
            initiateConstituency(_constituencyType, _constituencyCode);
        }

        constituencies[_constituencyCode].candidates.push(candidate);
    }

    function initiateConstituency(uint _constituencyType, string _constituencyCode) isValidConstituencyType(_constituencyType) {
        constituencies[_constituencyCode].numVotes = 0;
        constituencies[_constituencyCode].constituencyType = ConstituencyType(_constituencyType);
    }

    function invalidateCandidate(uint _candidateId) onlyOwner() isValidCandidate(_candidateId) {
        candidates[_candidateId].validCandidate = false;
    }

    function vote(string _nonce, uint _votedForFederal, uint _votedForState) onlyOwner() hasNotVoted(_nonce) {
        totalVotes++;
        votes[_nonce].hasVoted = true;
        votes[_nonce].votedForFederal = _votedForFederal;
        votes[_nonce].votedForState = _votedForState;
    }

    /*function getTest() view returns(){

    }*/
}
