pragma solidity ^0.4.24;

import "./Ownable.sol";

contract Election is Ownable {
    enum ConstituencyType {Federal, State}
    uint totalVotes;

    modifier hasNotVoted(string _nonce){
        require(votes[_nonce].hasVoted != true);
        _;
    }

    struct Constituency {
        uint numVotes;
        string constituency;
        ConstituencyType constituencyType;
        Candidate[] candidates;
    }

    struct Party {
        string abbreviation;
        string name;
    }

    struct Candidate {
        uint numVotes;
        string name;
    }

    struct Voter {
        bool hasVoted;
        uint votedForFederal;
        uint votedForState;
    }

    //Candidate[] candidates;
    //Party[] parties;

    mapping(string => Voter) votes;
    mapping(uint => Party) candidateToParty;

    constructor(){
        totalVotes = 0;
    }

    function registerCandidate(string _name, ConstituencyType constituency, string constituency, string party){

    }

    function vote(string _nonce, uint _votedForFederal, uint _votedForState) hasNotVoted(_nonce) {
        totalVotes++;
        votes[_nonce].hasVoted = true;
        votes[_nonce].votedForFederal = _votedForFederal;
        votes[_nonce].votedForState = _votedForState;
    }
}
