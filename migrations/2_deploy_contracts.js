var Ownable = artifacts.require('./Ownable.sol')
var Election = artifacts.require('./Election.sol')

module.exports = function (deployer) {
  deployer.deploy(Ownable)
  deployer.link(Ownable, Election)
  deployer.deploy(Election)
}
