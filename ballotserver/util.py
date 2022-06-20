import json
from binascii import hexlify, unhexlify
import sys
from Crypto import Random
from Crypto.Cipher import AES, PKCS1_OAEP
from Crypto.Cipher import PKCS1_v1_5 as Cipher_PKCS1_v1_5
from Crypto.PublicKey import RSA

from Crypto.Random import get_random_bytes
from base64 import urlsafe_b64encode as b64enc, urlsafe_b64decode as b64dec

# Constants
symmetricKeySizeBytes = int(128/8)
encMsgKeyBytes = 128
rsaKeySize = 3072

publicKeyDecryptError = "This is an rsa PUBLIC key, but an rsa PRIVATE key is required for decryption."
decryptionFailedError = "Decryption failed. Encrypted message is not valid."

def pack_tx(source, target, amount):
    return json.dumps({'source': str(source), 'target': str(target), 'amount': str(amount)})

def unpack_tx(data):
    return json.loads(data)

def pack_multi_tx(ins, outs):
    data = {'in': ins, 'out':outs }
    return json.dumps(data)

def unpack_multi_tx(data):
    return json.loads(data)

def generate_keypair():
    random_gen = Random.new().read
    return RSA.generate(1024, random_gen)

def public_key(keypair):
    return hexlify(keypair.publickey().exportKey('DER'))

def encrypt_v2(epkey, msg):
    key_pub = RSA.importKey(unhexlify(str(epkey)))
    cipher = Cipher_PKCS1_v1_5.new(key_pub)
    print(str(msg).encode())
    return hexlify(cipher.encrypt(str(msg).encode()))
    #return hexlify(RSA.importKey(unhexlify(str(epkey))).encrypt(str(msg), 'x')[0])
    
def decrypt_v2(keypair, emsg):
    key_priv = RSA.importKey(keypair)
    decipher = Cipher_PKCS1_v1_5.new(key_priv)
    return decipher.decrypt(unhexlify(emsg), None).decode()
    #return keypair.decrypt(unhexlify(emsg))



#################
def encrypt(epkey, message):
    """
    Applies public key (hybrid) encryption to a given message when supplied
    with a path to a public key (RSA in PEM format).
    """
    # Load the recipients pubkey
    recipientKey = RSA.importKey(unhexlify(epkey))

    # Encrypt the message with AES-GCM using a newly selected key
    messageKey, ctext = aesEncrypt(message)

    # Encrypt the message key and prepend it to the ciphertext
    cipher = PKCS1_OAEP.new(recipientKey)
    encMsg = cipher.encrypt(messageKey) + ctext

    # Format the message into b64
    return b64enc(encMsg).decode("utf-8")


def decrypt(keypair, ctext):
    """
    Decrypts an encrypted message with a private (RSA) key.
    Returns: (err, message)
    """
    privkey = None
    #privkey = RSA.importKey(keypair)
    privkey = keypair

    # Verify that this is a private key
    if not privkey.has_private():
        return (publicKeyDecryptError, None)

    # Verify the JEE and extract the encrypted message
    encBytes = b64dec(ctext)

    # Separate the encrypted message key from the symmetric-encrypted portion.
    encKey, ctext = encBytes[:encMsgKeyBytes], encBytes[encMsgKeyBytes:]

    # Recover the message key
    msgKey = PKCS1_OAEP.new(privkey).decrypt(encKey)

    # Recover the underlying message
    try:
        return aesDescrypt(msgKey, ctext).decode("utf-8")
    except ValueError:
        return ""


def aesEncrypt(message):
    """
    Encrypts a message with a fresh key using AES-GCM.
    Returns: (key, ciphertext)
    """
    try:
        tag = ""
        ctext = ""
        key = get_random_bytes(AES.block_size)
        cipher = AES.new(key, AES.MODE_GCM)
        ctext, tag = cipher.encrypt_and_digest(message.encode("utf-8"))
    except:
        reason = 'Error: %s - %s' % sys.exc_info()[:2]
        print(reason)

    # Concatenate (nonce, tag, ctext) and return with key
    return key, (cipher.nonce + tag + ctext)

def aesDescrypt(key, ctext):
    """
    Decrypts and authenticates a ciphertext encrypted with with given key.
    """
    # Break the ctext into components, then decrypt
    nonce,tag,ct = (ctext[:16], ctext[16:32], ctext[32:])
    cipher = AES.new(key, AES.MODE_GCM, nonce)
    return cipher.decrypt_and_verify(ct, tag)


if __name__ == "__main__":
    # Test encryption
    keypair = generate_keypair()
    pubkey = public_key(keypair)
    msg = "helloworld"
    emsg = encrypt(pubkey, msg)
    dmsg = decrypt(keypair, emsg)
    print(pubkey)
    print(msg)
    print(emsg)
    print(dmsg)


