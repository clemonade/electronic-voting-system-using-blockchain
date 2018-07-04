pragma solidity ^0.4.24;

import "./Ownable.sol";

contract BaseConstituency is Ownable {
    uint numVotes;
    uint constituencyType;
    string constituencyName;

    struct Candidate {
        string name;
        uint numVotes;
        bool validCandidate;
    }

    struct Voter {
        bool hasVoted;
        bool validVote;
        uint candidateVote;
    }

    Candidate[] candidates;

    constructor(uint _constituencyType, string _constituencyName) public{
        numVotes = 0;
        constituencyType = _constituencyType;
        constituencyName = _constituencyName;
    }

    function getStuff() public view returns(uint, string){
        return (constituencyType, constituencyName);
    }

}
