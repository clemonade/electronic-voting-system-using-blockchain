pragma solidity ^0.4.24;

import "./Ownable.sol";

/*
@title Election smart contract
@author Clement Tong
*/
contract Election is Ownable {
    event NewConstituency(string constituencyCode, string constituencyName, uint constituencyType);
    event NewParty(uint partyId, string name, string abbreviation);
    event NewCandidate(uint candidateId, string name, uint partyId, string constituencyCode);
    event NewVote(bool validFederalVote, bool validStateVote, uint federalCandidateId, uint stateCandidateIds);

    enum ConstituencyType {FEDERAL, STATE}
    uint public totalVotes;

    /**
    @notice Checks if the voter has already voted
    @dev -
    @param _voterHash Hash of voter
    */
    modifier hasNotVoted(string _voterHash){
        require(votes[_voterHash].hasVoted != true);
        _;
    }

    /**
    @notice Checks if the constituency type is valid
    @dev -
    @param _constituencyType Type of constituency
    */
    modifier isValidConstituencyType(uint _constituencyType){
        require(uint(ConstituencyType.STATE) >= _constituencyType);
        _;
    }

    /**
    @notice Checks if the constituency does not exist
    @dev -
    @param _constituencyCode Code of constituency
    */
    modifier isNotExistingConstituency(string _constituencyCode){
        require(constituencies[_constituencyCode].initialised != true);
        _;
    }

    /**
    @notice Checks if the constituency already exists
    @dev -
    @param _constituencyCode Code of constituency
    */
    modifier isExistingConstituency(string _constituencyCode){
        require(constituencies[_constituencyCode].initialised);
        _;
    }

    /**
    @notice Checks if the party does not exist
    @dev -
    @param _partyName Name of party
    */
    modifier isNotExistingParty(string _partyName){
        for (uint x = 0; x < parties.length; x++) {
            require(compareStrings(parties[x].name, _partyName) != true);
        }
        _;
    }

    /**
    @notice Checks if the party is valid
    @dev -
    @param _partyId ID of a party
    */
    modifier isValidParty (uint _partyId){
        require(parties.length > _partyId);
        _;
    }

    /**
    @notice Checks if the candidate is valid
    @dev -
    @param _candidateId ID of a candidate
    */
    modifier isValidCandidate(uint _candidateId){
        require(candidates.length > _candidateId);
        _;
    }

    struct Constituency {
        bool initialised;
        uint numVotes;
        uint[] candidates;
        string constituencyName;
        ConstituencyType constituencyType;
    }

    struct Party {
        uint partyId;
        string name;
        string abbreviation;
    }

    struct Candidate {
        uint candidateId;
        uint numVotes;
        string name;
        bool validCandidate;
    }

    struct Voter {
        bool hasVoted;
        bool validFederalVote;
        bool validStateVote;
        uint federalCandidateVote;
        uint stateCandidateVote;
    }

    Party[] public parties;
    Candidate[] public candidates;

    mapping(string => Voter) votes;
    mapping(string => Constituency) constituencies;
    mapping(uint => uint) candidateToParty;
    mapping(uint => string) candidateToConstituency;

    constructor() public {
        totalVotes = 0;
    }

    /**
    @notice Initialises a new constituency
    @dev -
    @param _constituencyType Type of constituency
    @param _constituencyCode Code of constituency
    @param _constituencyName Name of constituency
    */
    function initialiseConstituency(
        uint _constituencyType,
        string _constituencyCode,
        string _constituencyName) public onlyOwner() isValidConstituencyType(_constituencyType) isNotExistingConstituency(_constituencyCode) {

        constituencies[_constituencyCode].initialised = true;
        constituencies[_constituencyCode].numVotes = 0;
        constituencies[_constituencyCode].constituencyName = _constituencyName;
        constituencies[_constituencyCode].constituencyType = ConstituencyType(_constituencyType);

        emit NewConstituency(_constituencyCode, _constituencyName, _constituencyType);
    }

    /**
    @notice Registers a new party
    @dev -
    @param _partyName Name of party
    @param _partyAbbreviation Abbreviation of name of party
    */
    function registerParty(
        string _partyName,
        string _partyAbbreviation) public onlyOwner() isNotExistingParty(_partyName) {
        uint partyId = parties.length;
        Party memory party = Party(partyId, _partyName, _partyAbbreviation);
        parties.push(party);

        emit NewParty(partyId, _partyName, _partyAbbreviation);
    }

    /**
    @notice Registers a new candidate
    @dev -
    @param _constituencyCode Code of contesting constituency
    @param _candidateName Name of new candidate
    @param _partyId ID of a registered party
    */
    function registerCandidate(
        string _constituencyCode,
        string _candidateName,
        uint _partyId) public onlyOwner() isExistingConstituency(_constituencyCode) isValidParty(_partyId) {

        uint candidateId = candidates.length;
        candidateToParty[candidateId] = _partyId;
        candidateToConstituency[candidateId] = _constituencyCode;
        constituencies[_constituencyCode].candidates.push(candidateId);
        Candidate memory candidate = Candidate(candidateId, 0, _candidateName, true);
        candidates.push(candidate);

        emit NewCandidate(candidateId, _candidateName, _partyId, _constituencyCode);
    }

    /**
    @notice Votes for federal constituency and state constituency candidates
    @dev _voterHash is calculated as: SHA-256(voterName + voterNRIC + SHA-256(voterNonce))
    @dev Array must contain candidateId(s) belonging to their originating constituency
    @dev Pass in dummy array if no candidate(s) is/are selected, [0,0,0] if the constituency is not applicable
    @param _voterHash Hash of voter
    @param _federalCandidateVote Array of voted federal constituency candidates
    @param _stateCandidateVote Array of voted state constituency candidates
    */
    function vote(
        string _voterHash,
        uint[] _federalCandidateVote,
        uint[] _stateCandidateVote) public onlyOwner() hasNotVoted(_voterHash) {

        uint federalCandidateId = _federalCandidateVote[0];
        uint stateCandidateId = _stateCandidateVote[0];

        totalVotes++;
        constituencies[candidateToConstituency[federalCandidateId]].numVotes++;
        if (isExistingStateConstituency(_stateCandidateVote)) {
            constituencies[candidateToConstituency[stateCandidateId]].numVotes++;
        }
        votes[_voterHash].hasVoted = true;

        bool validFederalVote = isValidVote(_federalCandidateVote);
        bool validStateVote = isValidVote(_stateCandidateVote);

        if (validFederalVote) {
            votes[_voterHash].validFederalVote = true;
            votes[_voterHash].federalCandidateVote = federalCandidateId;
            candidates[federalCandidateId].numVotes++;
        }

        if (validStateVote) {
            votes[_voterHash].validStateVote = true;
            votes[_voterHash].stateCandidateVote = stateCandidateId;
            candidates[stateCandidateId].numVotes++;
        }

        emit NewVote(validFederalVote, validStateVote, federalCandidateId, stateCandidateId);
    }

    /**
    @notice Checks if the vote is valid
    @dev Valid if array length is exactly one(1)
    @param _vote Array of voted constituency candidates
    @return Validity of the vote
    */
    function isValidVote(uint[] _vote) public pure returns (bool){
        bool validity = false;

        if (_vote.length == 1) {
            validity = true;
        }

        return validity;
    }

    /**
    @notice Checks if the vote is [0,0,0]
    @dev -
    @param _vote Array of state constituency candidates
    @return Existence of state constituency
    */
    function isExistingStateConstituency(uint[] _vote) public pure returns (bool){
        bool exists = true;

        for (uint x = 0; x < 3; x++) {
            if (_vote[x] != 0) {
                return exists;
            }
        }

        exists = false;
        return exists;
    }

    /**
    @notice Invalidates a candidate
    @dev -
    @param _candidateId ID of a candidate
    */
    function invalidateCandidate(uint _candidateId) public onlyOwner() isValidCandidate(_candidateId) {
        candidates[_candidateId].validCandidate = false;
    }

    /**
    @notice Compares a pair of strings for equality
    @dev -
    @param a First string
    @param b Second string
    @return Equality of the strings
    */
    function compareStrings(string a, string b) public pure returns (bool){
        bytes32 c = keccak256(abi.encodePacked(a));
        bytes32 d = keccak256(abi.encodePacked(b));

        return c == d;
    }

    /**
    @dev GETTERS
    */
    function getConstituency(string _constituencyCode) public view returns (bool, uint, uint[], string, ConstituencyType){
        bool initialised = constituencies[_constituencyCode].initialised;
        uint numVotes = constituencies[_constituencyCode].numVotes;
        uint[] memory constituencyCandidates = constituencies[_constituencyCode].candidates;
        string memory constituencyName = constituencies[_constituencyCode].constituencyName;
        ConstituencyType constituencyType = constituencies[_constituencyCode].constituencyType;

        return (initialised, numVotes, constituencyCandidates, constituencyName, constituencyType);
    }

    function getParty(uint _partyId) public view returns (string, string){
        string memory name = parties[_partyId].name;
        string memory abbreviation = parties[_partyId].abbreviation;

        return (name, abbreviation);
    }

    function getCandidate(uint _candidateId) public view returns (uint, string, bool, uint, string){
        uint numVotes = candidates[_candidateId].numVotes;
        string memory name = candidates[_candidateId].name;
        bool validCandidate = candidates[_candidateId].validCandidate;
        uint party = candidateToParty[_candidateId];
        string memory constituency = candidateToConstituency[_candidateId];

        return (numVotes, name, validCandidate, party, constituency);
    }

    function getVoter(string _hash) public view returns (bool, bool, bool, uint, uint){
        bool hasVoted = votes[_hash].hasVoted;
        bool validFederalVote = votes[_hash].validFederalVote;
        bool validStateVote = votes[_hash].validStateVote;
        uint federalCandidateVote = votes[_hash].federalCandidateVote;
        uint stateCandidateVote = votes[_hash].stateCandidateVote;

        return (hasVoted, validFederalVote, validStateVote, federalCandidateVote, stateCandidateVote);
    }

    function getPartiesLength() public view returns (uint){
        return parties.length;
    }
}
