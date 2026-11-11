"""
Decides whose record a transaction is.

A civic record (an application, an endorsement, a post, a proposal) belongs to
the address that signed the transaction: the address whose coins it spends.
Where the coins go says nothing about who wrote the record, because anyone can
send coins to any address.

Pure: the chain lookup is passed in, so this is testable without a node.
"""


def output_address(vout):
    script = vout.get("scriptPubKey", {})
    addresses = script.get("addresses") or []
    return addresses[0] if addresses else script.get("address")


def signer_addresses(transaction, get_tx):
    """Addresses whose outputs this transaction spends, in input order, without repeats."""
    seen = []
    for vin in transaction.get("vin", []):
        if vin.get("coinbase") is not None:
            continue
        previous = get_tx(vin.get("txid")) if vin.get("txid") else None
        try:
            address = output_address(previous["vout"][vin["vout"]]) if previous else None
        except (IndexError, KeyError, TypeError):
            address = None
        if address and address not in seen:
            seen.append(address)
    return seen


def record_author(transaction, get_tx, is_known=lambda address: True):
    """
    The address a record is credited to, or None if no signer can be established.

    The largest non-data output is the usual place a wallet returns its coins, so
    it is preferred when it is also a signer. Otherwise the first signer that the
    registry knows is used. An address that only receives coins is never chosen.
    """
    signers = signer_addresses(transaction, get_tx)
    if not signers:
        return None
    largest, largest_value = None, 0
    for vout in transaction.get("vout", []):
        if "OP_RETURN" in vout.get("scriptPubKey", {}).get("asm", ""):
            continue
        if vout.get("value", 0) > largest_value:
            largest, largest_value = output_address(vout), vout.get("value", 0)
    if largest in signers:
        return largest
    for address in signers:
        if is_known(address):
            return address
    return None
