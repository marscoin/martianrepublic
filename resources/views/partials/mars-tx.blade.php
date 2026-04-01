{{--
  Shared transaction signing module for the Martian Republic.
  Provides MarsWallet global with UTXO selection, signing, and broadcast.

  Requirements: my_bundle.js must be loaded BEFORE this partial.
  Expects: my_bundle.bitcoin, my_bundle.bip32, my_bundle.bip39, my_bundle.Buffer, Marscoin.mainnet

  Usage:
    const result = await MarsWallet.signCivicAction(civicAddr, mnemonic, 'GP_' + cid);
    const result = await MarsWallet.signSend(allAddresses, receiverAddr, amount, mnemonic);
--}}
<script>
// Marscoin network constant — define if not already defined by the page
if (typeof Marscoin === 'undefined') {
    window.Marscoin = {
        mainnet: {
            messagePrefix: "\x19Marscoin Signed Message:\n",
            bech32: "M",
            bip44: 2,
            bip32: { public: 0x043587cf, private: 0x04358394 },
            pubKeyHash: 0x32,
            scriptHash: 0x32,
            wif: 0x80,
        }
    };
}

window.MarsWallet = (function() {
    'use strict';

    const CSRF_TOKEN = '{{ csrf_token() }}';
    const DUST_AMOUNT = 0.0001;

    // ========================================================================
    // UTXO SELECTION — calls /api/mars-utxo-multi (multi-address, mempool-filtered)
    // ========================================================================

    async function getUtxos(addresses, receiver, amount) {
        const addrs = Array.isArray(addresses) ? addresses : [addresses];
        const params = new URLSearchParams();
        addrs.forEach(a => params.append('addresses[]', a));
        params.set('receiver_address', receiver);
        params.set('amount', amount);

        const response = await fetch('/api/mars-utxo-multi?' + params.toString());
        const data = await response.json();
        if (data.error) throw new Error(data.error);
        if (!data.inputs || data.inputs.length === 0) throw new Error('No UTXOs found');
        return data;
    }

    // ========================================================================
    // BROADCAST — POST to /api/broadcast with CSRF
    // ========================================================================

    async function broadcast(rawTxHex) {
        const response = await fetch('/api/broadcast', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({ rawtx: rawTxHex }),
        });
        const data = await response.json();
        if (data.txid) return data;
        const errMsg = data.error || 'Broadcast failed';
        if (errMsg.includes('bip125-replacement') || errMsg.includes('txn-mempool-conflict')) {
            throw new Error('A previous transaction is still confirming. Please wait ~2 minutes and try again.');
        }
        throw new Error(errMsg);
    }

    // ========================================================================
    // KEY DERIVATION — scheme-aware, tries all paths + mixed-lib
    // ========================================================================

    function getSigningKey(address, mnemonic) {
        const seed = my_bundle.bip39.mnemonicToSeedSync(mnemonic.trim());
        const root = my_bundle.bitcoin.bip32.fromSeed(seed, Marscoin.mainnet);
        const addressMap = window._walletAddressMap || {};
        const pathInfo = addressMap[address];

        if (pathInfo) {
            const scheme = pathInfo.scheme || 'standard';
            const chain = pathInfo.chain;
            const idx = pathInfo.index;

            if (scheme === 'genseed-mixed' && my_bundle.bip32) {
                try {
                    const acct = root.derivePath("m/44'/2'/0'/0'");
                    const node = my_bundle.bip32.fromBase58(acct.toBase58(), Marscoin.mainnet);
                    const child = node.derive(chain).derive(idx);
                    console.log('[MarsWallet] key [genseed-mixed] chain=' + chain + ' idx=' + idx);
                    return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                } catch(e) { console.warn('genseed-mixed failed:', e.message); }
            }
            if (scheme === 'standard-mixed' && my_bundle.bip32) {
                try {
                    const acct = root.derivePath("m/44'/2'/0'");
                    const node = my_bundle.bip32.fromBase58(acct.toBase58(), Marscoin.mainnet);
                    const child = node.derive(chain).derive(idx);
                    console.log('[MarsWallet] key [standard-mixed] chain=' + chain + ' idx=' + idx);
                    return my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                } catch(e) { console.warn('standard-mixed failed:', e.message); }
            }
            if (scheme === 'genseed') {
                const path = "m/44'/2'/0'/0'/" + chain + '/' + idx;
                console.log('[MarsWallet] key [genseed] ' + path);
                return my_bundle.bitcoin.ECPair.fromWIF(root.derivePath(path).toWIF(), Marscoin.mainnet);
            }
            if (scheme === 'marscoin') {
                const path = "m/44'/107'/0'/" + chain + '/' + idx;
                console.log('[MarsWallet] key [marscoin] ' + path);
                return my_bundle.bitcoin.ECPair.fromWIF(root.derivePath(path).toWIF(), Marscoin.mainnet);
            }
            // standard
            const path = "m/44'/2'/0'/" + chain + '/' + idx;
            console.log('[MarsWallet] key [standard] ' + path);
            return my_bundle.bitcoin.ECPair.fromWIF(root.derivePath(path).toWIF(), Marscoin.mainnet);
        }

        // Fallback: default BIP44
        console.log('[MarsWallet] key [default] m/44\'/2\'/0\'/0/0 for ' + address);
        return my_bundle.bitcoin.ECPair.fromWIF(
            root.derivePath("m/44'/2'/0'/0/0").toWIF(), Marscoin.mainnet
        );
    }

    // ========================================================================
    // BRUTE-FORCE KEY SEARCH — tries ALL schemes × ALL mnemonics
    // ========================================================================

    function bruteForceSign(psbt, inputIndex, mnemonic) {
        const allMnemonics = JSON.parse(localStorage.getItem('_allMnemonics') || '[]');
        const mnemonicsToTry = [...new Set([mnemonic.trim(), ...allMnemonics])];
        const basePaths = ["m/44'/2'/0'", "m/44'/2'/0'/0'", "m/44'/107'/0'"];

        for (const tryMnemonic of mnemonicsToTry) {
            const trySeed = my_bundle.bip39.mnemonicToSeedSync(tryMnemonic);
            const tryRoot = my_bundle.bitcoin.bip32.fromSeed(trySeed, Marscoin.mainnet);

            // Standard derivation paths
            for (const basePath of basePaths) {
                for (let ch = 0; ch <= 1; ch++) {
                    for (let idx = 0; idx < 20; idx++) {
                        try {
                            const child = tryRoot.derivePath(basePath + '/' + ch + '/' + idx);
                            const key = my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                            psbt.signInput(inputIndex, key);
                            console.log('[MarsWallet] brute-force signed with ' + basePath + '/' + ch + '/' + idx);
                            return true;
                        } catch(e) { /* next */ }
                    }
                }
            }

            // Mixed-lib derivations
            if (my_bundle.bip32) {
                const mixedBases = ["m/44'/2'/0'/0'", "m/44'/2'/0'"];
                for (const mb of mixedBases) {
                    try {
                        const acct = tryRoot.derivePath(mb);
                        const node = my_bundle.bip32.fromBase58(acct.toBase58(), Marscoin.mainnet);
                        for (let ch = 0; ch <= 1; ch++) {
                            const chainNode = node.derive(ch);
                            for (let idx = 0; idx < 20; idx++) {
                                try {
                                    const child = chainNode.derive(idx);
                                    const key = my_bundle.bitcoin.ECPair.fromWIF(child.toWIF(), Marscoin.mainnet);
                                    psbt.signInput(inputIndex, key);
                                    console.log('[MarsWallet] brute-force signed with mixed-lib ' + mb + '/' + ch + '/' + idx);
                                    return true;
                                } catch(e) { /* next */ }
                            }
                        }
                    } catch(e) { /* skip */ }
                }
            }
        }
        return false;
    }

    // ========================================================================
    // EXTRACT ADDRESS FROM INPUT (from raw prev tx)
    // ========================================================================

    function getAddressFromInput(input) {
        try {
            const rawTxBuf = my_bundle.Buffer.from(input.rawTx, 'hex');
            const prevTx = my_bundle.bitcoin.Transaction.fromBuffer(rawTxBuf);
            const output = prevTx.outs[input.vout];
            return my_bundle.bitcoin.address.fromOutputScript(output.script, Marscoin.mainnet);
        } catch(e) {
            return input.address || null;
        }
    }

    // ========================================================================
    // BUILD + SIGN PSBT
    // ========================================================================

    function buildAndSign(inputs, outputs, opReturnMsg, mnemonic) {
        var psbt = new my_bundle.bitcoin.Psbt({ network: Marscoin.mainnet });
        psbt.setVersion(1);
        psbt.setMaximumFeeRate(100000);

        // Add inputs
        inputs.forEach(function(input) {
            psbt.addInput({
                hash: input.txId,
                index: input.vout,
                nonWitnessUtxo: my_bundle.Buffer.from(input.rawTx, 'hex'),
            });
        });

        // Add OP_RETURN output if message provided
        if (opReturnMsg) {
            var data = my_bundle.Buffer.from(opReturnMsg);
            var embed = my_bundle.bitcoin.payments.embed({ data: [data] });
            psbt.addOutput({ script: embed.output, value: 0 });
        }

        // Add value outputs
        outputs.forEach(function(output) {
            psbt.addOutput({ address: output.address, value: output.value });
        });

        // Sign each input — try scheme-aware key first, then brute-force
        for (var i = 0; i < inputs.length; i++) {
            var inputAddr = getAddressFromInput(inputs[i]);
            var key = getSigningKey(inputAddr || inputs[i].address, mnemonic);
            try {
                psbt.signInput(i, key);
            } catch(signErr) {
                console.warn('[MarsWallet] primary sign failed for input ' + i + ', trying brute-force...');
                if (!bruteForceSign(psbt, i, mnemonic)) {
                    throw new Error('Could not sign input ' + i + ' for address ' + (inputAddr || 'unknown') +
                        '. The wallet key may not match this address.');
                }
            }
        }

        var txHex = psbt.finalizeAllInputs().extractTransaction().toHex();
        return txHex;
    }

    // ========================================================================
    // HIGH-LEVEL: Sign a civic action (OP_RETURN from civic address)
    // ========================================================================

    async function signCivicAction(civicAddress, mnemonic, opReturnMsg, amount) {
        amount = amount || DUST_AMOUNT;

        // Get UTXOs from civic address only
        var io = await getUtxos([civicAddress], civicAddress, amount);

        // Build and sign
        var txHex = buildAndSign(io.inputs, io.outputs, opReturnMsg, mnemonic);

        // Broadcast
        var result = await broadcast(txHex);
        console.log('[MarsWallet] civic tx broadcast:', result.txid);
        return result;
    }

    // ========================================================================
    // HIGH-LEVEL: Sign a send (multi-address UTXO, no OP_RETURN)
    // ========================================================================

    async function signSend(addresses, receiver, amount, mnemonic) {
        var addrs = Array.isArray(addresses) ? addresses : [addresses];
        var io = await getUtxos(addrs, receiver, amount);

        var txHex = buildAndSign(io.inputs, io.outputs, null, mnemonic);

        var result = await broadcast(txHex);
        console.log('[MarsWallet] send tx broadcast:', result.txid);
        return result;
    }

    // ========================================================================
    // LOW-LEVEL: Build and sign only (for CoinShuffle or custom broadcast)
    // ========================================================================

    function buildSignedHex(inputs, outputs, opReturnMsg, mnemonic) {
        return buildAndSign(inputs, outputs, opReturnMsg, mnemonic);
    }

    // ========================================================================
    // HUMAN-READABLE ERROR HELPER
    // ========================================================================

    function friendlyError(e) {
        var msg = e.message || 'Transaction failed';
        if (msg.includes('still confirming') || msg.includes('bip125')) {
            return 'A previous transaction is still confirming. Please wait ~2 minutes and try again.';
        }
        if (msg.includes('Insufficient') || msg.includes('No UTXOs')) {
            return 'Insufficient funds for this transaction.';
        }
        if (msg.includes('Could not sign')) {
            return 'Could not sign transaction. The wallet key may not match this address.';
        }
        return msg;
    }

    // ========================================================================
    // PUBLIC API
    // ========================================================================

    return {
        getUtxos: getUtxos,
        broadcast: broadcast,
        getSigningKey: getSigningKey,
        signCivicAction: signCivicAction,
        signSend: signSend,
        buildSignedHex: buildSignedHex,
        friendlyError: friendlyError,
        DUST_AMOUNT: DUST_AMOUNT,
    };

})();
</script>
