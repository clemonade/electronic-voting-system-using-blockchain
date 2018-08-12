import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';

let Election = contract(electionArtifacts);
let account;

window.App = {
    start: function () {
        let self = this;

        Election.setProvider(web3.currentProvider);

        if (window.location.pathname.includes('admin')) {
            web3.eth.getAccounts(function (err, accs) {
                if (err != null) {
                    self.setStatus('text-danger Error fetching accounts.');
                    return;
                }

                if (accs.length === 0) {
                    self.setStatus('text-warning Couldn\'t get any accounts. Ensure Ethereum client is configured correctly.');
                    return;
                }

                //web3.eth.getAccounts() only returns the one selected account
                //https://github.com/MetaMask/metamask-extension/issues/3207
                account = accs[0];
            });
        }

        setInterval(function () {
            if (web3.eth.accounts[0] !== account) {
                account = web3.eth.accounts[0];
                //someFunction()
            }
        }, 100);

        self.populateInfo();
    },

    //Only displays the last message.
    setStatus: function (message) {
        if (message[0] === ' ') {
            $('#status').text(message);
        } else {
            let bs4class = message.substr(0, message.indexOf(' '));
            let status = message.substr(message.indexOf(' ') + 1);

            $('#status')
                .addClass(bs4class)
                .text(status);
        }
    },

    populateInfo: function () {
        let self = this;
        let election;

        Election.deployed().then((instance) => {
            election = instance;

            election.owner.call().then((address) => {
                $('#admin').val(address);

                web3.eth.getBalance(address, (err, balance) => {
                    $('#balance').val(web3.fromWei(balance, "ether") + " ETH");
                });
            });

            $('#address').val(election.address);

            self.setInitialisation(federals);
            self.setInitialisation(states);
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving contract information.');
        });
    },

    setInitialisation: function (constituencies) {
        let self = this;
        let election;
        Election.deployed().then((instance) => {
            election = instance;
            let promises = [];
            for (let constituency of constituencies) {
                let code = constituency.code;
                promises.push(election.getConstituency.call(code));
            }
            Promise.all(promises).then(() => {
                for (let x = 0; x < promises.length; x++) {
                    let code = constituencies[x].code;
                    let name = constituencies[x].name;
                    let jcode = constituencies[x].code.replace(/\./g, '\\.').replace(/\//g, '\\/');
                    promises[x].then((value) => {
                        if (value[0] === false) {
                            $('#' + jcode)
                                .click(() => {
                                    self.initialiseConstituency(code, name)
                                });
                        } else {
                            $('#' + jcode)
                                .text('Initialised')
                                .attr('disabled', 'disabled');
                            $('#' + jcode + 'V')
                                .removeAttr('disabled');
                        }
                    });
                }
            });
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving initialisation status.');
        });
    },

    initialiseConstituency: function (code, name) {
        let self = this;
        let election;
        let type;

        if (code.includes('N')) {
            type = 1;
        } else {
            type = 0;
        }

        Election.deployed().then((instance) => {
            election = instance;
            return election.initialiseConstituency(type, code, name, {from: account});
        }).then(() => {
            self.setStatus('text-success Constituency ' + code + ' initialised.')
        }).catch((e) => {
            console.log(e);
            self.setStatus('text-danger Error initialising constituency.');
        });
    },
};
