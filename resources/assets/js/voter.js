import {default as contract} from 'truffle-contract';
import electionArtifacts from '../../../build/contracts/Election.json';
import {sha256} from "js-sha256";

let Election = contract(electionArtifacts);

window.App = {
    start: function () {
        let self = this;

        Election.setProvider(web3.currentProvider);
    },

    setStatus: function (message) {
        if (message[0] === ' ') {
            $('#status').text(message);
        } else {
            let bs4class = message.substr(0, message.indexOf(' '));
            let status = message.substr(message.indexOf(' ') + 1);

            $('#status')
                .removeClass()
                .addClass(bs4class)
                .text(status);
        }
    },

    populateFederalDropdown: function (state_id) {
        let self = this;
        let element = $('#federalconstituency');
        let div = $('.stateconstituency');

        element.empty();
        div.show();

        for (let constituency of federals) {
            if (constituency.state === state_id) {
                $('<option/>')
                    .val(constituency.id)
                    .text(constituency.code + ' ' + constituency.name)
                    .appendTo(element);
            }
        }

        if (state_id === '14') {
            div.hide();
            $('#stateconstituency').empty();
        } else {
            self.populateStateDropdown(element.val())
        }
    },

    populateStateDropdown: function (federal_id) {
        let element = $('#stateconstituency');

        element.empty();

        let federal;
        for (let constituency of federals) {
            if (constituency.id === parseInt(federal_id)) {
                federal = constituency;
            }
        }

        for (let constituency of states) {
            let code = constituency.code;
            if (code.includes(federal.code)) {
                $('<option/>')
                    .val(constituency.id)
                    .text(constituency.code + ' ' + constituency.name)
                    .appendTo(element);
            }
        }
    },

    //Frontend validation god.
    //idk what happens if frontend passes but backend fails.
    validateRegister: function () {
        let self = this;
        let valid = true;

        let n = $('#name');
        let i = $('#nric');
        let g = $('input[name="gender"]:checked');
        let s = $('#state');
        let fc = $('#federalconstituency');
        let sc = $('#stateconstituency');

        let name = n.val();
        let nric = i.val();
        let gender = g.length;
        let state = s.val();
        let federalconstituency = fc.val();
        let stateconstituency = sc.val();

        if (name === "") {
            valid = false;
            n.addClass('is-invalid');
        } else {
            n.removeClass('is-invalid');
        }

        //DEV
        let regex = new RegExp();
        //let regex = new RegExp(/^([0-9]{2})([0-1]{1})([0-9]{1})([0-3]{1})([0-9]{1})([0-9]{6})$/);

        if (nric === "") {
            valid = false;
            i.addClass('is-invalid');
            $('#nricfeedback').text('NRIC is required.')
        } else {
            if (regex.test(nric)) {
                i.removeClass('is-invalid');
            } else {
                valid = false;
                i.addClass('is-invalid');
                $('#nricfeedback').text('NRIC is invalid.');
            }
        }

        if (gender !== 1) {
            valid = false;
            $('#gender').addClass('is-invalid');
        } else {
            $('#gender').removeClass('is-invalid');
        }

        if (state === null) {
            valid = false;
            s.addClass('is-invalid');
        } else {
            s.removeClass('is-invalid');
        }

        if (federalconstituency === null) {
            valid = false;
            fc.addClass('is-invalid');
        } else {
            fc.removeClass('is-invalid');
        }

        //For state constituency hiding/emptying
        if (!$('.stateconstituency:hidden').length) {
            if (stateconstituency === null) {
                valid = false;
                sc.addClass('is-invalid');
            }
            else {
                sc.removeClass('is-invalid');
            }
        }

        return valid;
    },

    validate: function () {
        let self = this;
        let valid = true;

        //j.
        let jname = $('#name');
        let jnric = $('#nric');
        let jnonce = $('#nonce');

        let name = jname.val().toUpperCase();
        let nric = jnric.val();
        let nonce = jnonce.val();

        if (name === "") {
            valid = false;
            jname.addClass('is-invalid');
        } else {
            jname.removeClass('is-invalid');
        }

        if (nric === "") {
            valid = false;
            jnric.addClass('is-invalid');
        } else {
            jnric.removeClass('is-invalid');
        }

        if (nonce === "") {
            valid = false;
            jnonce.addClass('is-invalid');
        } else {
            jnonce.removeClass('is-invalid');
        }

        if (valid) {
            self.verify(name, nric, nonce);
        }
    },

    verify: function (name, nric, nonce) {
        let self = this;
        let election;

        let hash = sha256(name + nric + sha256(nonce));

        Election.deployed().then((instance) => {
            election = instance;
            return election.getVoter.call(hash);
        }).then((value) => {
            if (value[0]) {
                $('#bool')
                    .val('VOTED')
                    .removeClass('text-danger')
                    .addClass('text-success');
                $('#hash').val(hash);

                if (value[1]) {
                    $('#federal').val('VALID');
                } else {
                    $('#federal').val('INVALID');
                }

                if (value[2]) {
                    $('#state').val('VALID');
                } else {
                    $('#state').val('INVALID');
                }

                self.setStatus('text-success Voter information found.');
            } else {
                $('#bool')
                    .val('DID NOT VOTE')
                    .removeClass('text-success')
                    .addClass('text-danger');
                $('#hash').val('INAPPLICABLE');
                $('#federal').val('INAPPLICABLE');
                $('#state').val('INAPPLICABLE');

                self.setStatus('text-warning Voter information not found.');
            }
        }).catch(function (e) {
            console.log(e);
            self.setStatus('text-danger Error retrieving voter information.');
        });
    },
};
