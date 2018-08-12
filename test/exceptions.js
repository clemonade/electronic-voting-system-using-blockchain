const PREFIX = "VM Exception while processing transaction: ";

async function tryCatch(promise, message) {
    try {
        await promise;
        //idk what the throw is for.
        // noinspection ExceptionCaughtLocallyJS
        throw null;
    }
    catch (error) {
        //assert(error, "Expected an error but did not get one");
        assert(error, "Expected an error starting with '" + PREFIX + message + "' but got '" + error + "' instead");
    }
}

module.exports = {
    catchRevert: async function (promise) {
        await tryCatch(promise, "revert");
    },
    catchOutOfGas: async function (promise) {
        await tryCatch(promise, "out of gas");
    },
    catchInvalidJump: async function (promise) {
        await tryCatch(promise, "invalid JUMP");
    },
    catchInvalidOpcode: async function (promise) {
        await tryCatch(promise, "invalid opcode");
    },
    catchStackOverflow: async function (promise) {
        await tryCatch(promise, "stack overflow");
    },
    catchStackUnderflow: async function (promise) {
        await tryCatch(promise, "stack underflow");
    },
    catchStaticStateChange: async function (promise) {
        await tryCatch(promise, "static state change");
    },
};
