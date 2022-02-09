var bip39 = require('bip39')
var pbkdf2 = require('pbkdf2')
var hdkey = require('hdkey')
var Buffer = require('buffer/').Buffer 
var BIP84 = require('bip84')
var crypto = require('crypto')
var bitcoin = require('bitcoinjs-lib')
var bip32 = require('bip32')
const ElectrumCli = require('electrum-client')
const IPFS = require('ipfs-http-client')

const algorithm = 'aes-256-ctr';
//const secretKey = 'vOVH6sdmpNWjRRIqCc7rdxs01lwHzfr3';
//const iv = crypto.randomBytes(16);


// text = mnemonic
// key = password
const encrypt = ((val, password, iv) => {
    let cipher = crypto.createCipheriv(algorithm, password, iv);
    let encrypted = cipher.update(val, 'utf8', 'base64');
    encrypted += cipher.final('base64');
    return encrypted;
  });
  
  const  decrypt = ((encrypted, password, iv) => {
    let decipher = crypto.createDecipheriv(algorithm, password, iv);
    let decrypted = decipher.update(encrypted, 'base64', 'utf8');
    return (decrypted + decipher.final('utf8'));
  });
  

module.exports = {
    IPFS: IPFS,
    ElectrumCli: ElectrumCli,
    bip32: bip32,
    bip39: bip39,
    pbkdf2: pbkdf2,
    hdkey: hdkey,
    Buffer: Buffer,
    BIP84: BIP84,
    crypto: crypto,
    encrypt: encrypt,
    decrypt: decrypt,
    bitcoin: bitcoin,
};


