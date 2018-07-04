pragma solidity ^0.4.24;

import "./Ownable.sol";
import "./BaseConstituency.sol";

contract MasterConstituency is Ownable {
    enum ConstituencyType {FEDERAL, STATE}
    uint totalVotes;

    modifier isValidConstituencyType(uint _constituencyType){
        require(uint(ConstituencyType.STATE) >= _constituencyType);
        _;
    }

    modifier isUniqueParty(string _name){
        for (uint x = 0; x < parties.length; x++) {
            if (compareStrings(parties[x].name, _name)) {
                revert();
            }
        }
        _;
    }

    struct Party {
        string name;
        string abbreviation;
    }

    struct Candidate {
        string name;
        uint numVotes;
        bool validCandidate;
    }

    struct Voter {
        bool hasVoted;
        bool validFederalVote;
        bool validStateVote;
        uint federalCandidateVote;
        uint stateCandidateVote;
    }

    Candidate[] candidates;
    Party[] parties;

    mapping(string => Voter) votes;
    mapping(string => address) constituencies;
    mapping(uint => uint) candidateToParty;

    function initialiseConstituency(
        uint _constituencyType,
        string _constituencyCode,
        string _constituencyName) public onlyOwner() isValidConstituencyType(_constituencyType) returns (address) {
        address constituency = new BaseConstituency(_constituencyType, _constituencyName);
        constituencies[_constituencyCode] = constituency;

        return constituency;
    }

    function registerParty(string _name, string _abbreviation) public onlyOwner() isUniqueParty(_name) {
        parties.push(Party(_name, _abbreviation));
    }

    function getConstituencyAddress(string _constituencyCode) public view returns (address) {
        return constituencies[_constituencyCode];
    }

    function compareStrings(string a, string b) public pure returns (bool){
        bytes32 c = keccak256(abi.encodePacked(a));
        bytes32 d = keccak256(abi.encodePacked(b));

        return c == d;
    }
}