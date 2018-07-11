pragma solidity ^0.4.24;

import "./Ownable.sol";

/*
@title Election smart contract
@author Clement Tong
*/
contract Election is Ownable {
    enum ConstituencyType {FEDERAL, STATE}
    uint totalVotes;

    /**
    @notice Checks if the voter has already voted
    @dev -
    @param _voterNonce Nonce of voter
    */
    modifier hasNotVoted(string _voterNonce){
        require(votes[_voterNonce].hasVoted != true);
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
    @notice Checks if the constituency already exists
    @dev -
    @param _constituencyCode Code of constituency
    */
    modifier isExistingConstituency(string _constituencyCode){
        require(constituencies[_constituencyCode].initiated);
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
        bool initiated;
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
        uint numVotesFederal;
        uint numVotesState;
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

    Party[] parties;
    Candidate[] candidates;

    mapping(string => Voter) votes;
    mapping(string => Constituency) constituencies;
    mapping(uint => uint) candidateToParty;
    mapping(uint => string) candidateToConstituency;

    constructor() public {
        totalVotes = 0;
    }

    /**
    @notice Registers a new party
    @dev -
    @param _partyName Name of party
    @param _partyAbbreviation Abbreviation of name of party
    */
    function registerParty(string _partyName, string _partyAbbreviation) public onlyOwner() isNotExistingParty(_partyName) {
        uint partyId = parties.length;
        Party memory party = Party(partyId, _partyName, _partyAbbreviation);
        parties.push(party);
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
        uint _partyId) onlyOwner() public isExistingConstituency(_constituencyCode) isValidParty(_partyId) {

        uint candidateId = candidates.length;
        candidateToParty[candidateId] = _partyId;
        candidateToConstituency[candidateId] = _constituencyCode;
        constituencies[_constituencyCode].candidates.push(candidateId);
        Candidate memory candidate = Candidate(candidateId, 0, 0, _candidateName, true);
        candidates.push(candidate);

    }

    /**
    @notice Initialises a new constituency
    @dev -
    @param _constituencyType Type of constituency
    @param _constituencyCode Code of constituency
    @param _constituencyName Name of constituency
    */
    function initiateConstituency(
        uint _constituencyType,
        string _constituencyCode,
        string _constituencyName) public isValidConstituencyType(_constituencyType) {

        constituencies[_constituencyCode].initiated = true;
        constituencies[_constituencyCode].numVotes = 0;
        constituencies[_constituencyCode].constituencyName = _constituencyName;
        constituencies[_constituencyCode].constituencyType = ConstituencyType(_constituencyType);
    }

    //TODO Cannot pass empty array as argument
    /**
    @notice Votes for federal constituency and state constituency candidates
    @dev -
    @param _voterNonce Nonce of voter
    @param _federalCandidateVote Array of voted federal constituency candidates
    @param _stateCandidateVote Array of voted state constituency candidates
    */
    function vote(
        string _voterNonce,
        uint[] _federalCandidateVote,
        uint[] _stateCandidateVote) public onlyOwner() hasNotVoted(_voterNonce) {

        uint federalCandidateId = _federalCandidateVote[0];
        uint stateCandidateId = _stateCandidateVote[0];

        totalVotes++;
        constituencies[candidateToConstituency[federalCandidateId]].numVotes++;
        constituencies[candidateToConstituency[stateCandidateId]].numVotes++;
        votes[_voterNonce].hasVoted = true;

        if (isValidVote(_federalCandidateVote)) {
            votes[_voterNonce].validFederalVote = true;
            votes[_voterNonce].federalCandidateVote = federalCandidateId;
            candidates[federalCandidateId].numVotesFederal++;
        }

        if (isValidVote(_stateCandidateVote)) {
            votes[_voterNonce].validStateVote = true;
            votes[_voterNonce].stateCandidateVote = stateCandidateId;
            candidates[stateCandidateId].numVotesState++;
        }
    }

    /**
    @notice Checks if the vote is valid
    @dev -
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
    @dev Getters
    */
    function getParty(uint _partyId) public view returns (string, string){
        string memory name = parties[_partyId].name;
        string memory abbreviation = parties[_partyId].abbreviation;

        return (name, abbreviation);
    }

    function getCandidate(uint _candidateId) public view returns (uint, uint, string, bool, uint, string){
        uint numVotesFederal = candidates[_candidateId].numVotesFederal;
        uint numVotesState = candidates[_candidateId].numVotesState;
        string memory name = candidates[_candidateId].name;
        bool validCandidate = candidates[_candidateId].validCandidate;
        uint party = candidateToParty[_candidateId];
        string memory constituency = candidateToConstituency[_candidateId];

        return (numVotesFederal, numVotesState, name, validCandidate, party, constituency);
    }

    function getVoter(string _nonce) public view returns (bool, bool, bool, uint, uint){
        bool hasVoted = votes[_nonce].hasVoted;
        bool validFederalVote = votes[_nonce].validFederalVote;
        bool validStateVote = votes[_nonce].validStateVote;
        uint federalCandidateVote = votes[_nonce].federalCandidateVote;
        uint stateCandidateVote = votes[_nonce].stateCandidateVote;

        return (hasVoted, validFederalVote, validStateVote, federalCandidateVote, stateCandidateVote);
    }

    function getConstituency(string _constituencyCode) public view returns (bool, uint, uint[], string, ConstituencyType){
        bool initiated = constituencies[_constituencyCode].initiated;
        uint numVotes = constituencies[_constituencyCode].numVotes;
        uint[] memory constituencyCandidates = constituencies[_constituencyCode].candidates;
        string memory constituencyName = constituencies[_constituencyCode].constituencyName;
        ConstituencyType constituencyType = constituencies[_constituencyCode].constituencyType;

        return (initiated, numVotes, constituencyCandidates, constituencyName, constituencyType);
    }
}
