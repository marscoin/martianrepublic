"""
Decides whether an on-chain vote counts.

A vote is a transaction with a single data output naming a proposal and a
choice. On its own that proves nothing: anyone can publish one. It counts only
if all of the following hold.

1. It spends exactly one ballot: an output of exactly 0.1 MARS created by a
   ballot issuance transaction.
2. That issuance was funded by at least MIN_VOTERS inputs, each from a
   different address, every one of them a citizen at the time of counting.
3. The vote was mined while voting on the proposal was open.
4. No citizen is counted twice on one proposal. Ballots are anonymous, so this
   is enforced per issuance: an issuance may contribute at most as many votes
   as it has citizens who were not already credited through an earlier one.
   A citizen who takes part in two issuances for the same proposal gains no
   extra vote, and both issuances are visible on the chain.

A ballot can be spent only once, so one ballot can never yield two votes.

Everything here is pure: chain and registry lookups are passed in, which keeps
the rules testable without a node or a database.
"""

from decimal import Decimal

BALLOT_ZUBRINS = 10_000_000  # 0.1 MARS
MIN_VOTERS = 3
VOTE_TAGS = {"PRY": "Y", "PRN": "N", "PRA": "A"}


def zubrins(value):
    """Exact atomic amount from a node's decimal coin value."""
    return int((Decimal(str(value)) * 100_000_000).to_integral_value())


def output_address(vout):
    script = vout.get("scriptPubKey", {})
    addresses = script.get("addresses") or []
    return addresses[0] if addresses else script.get("address")


def is_data_output(vout):
    return str(vout.get("scriptPubKey", {}).get("asm", "")).startswith("OP_RETURN")


def issuance_citizens(issuance, get_tx):
    """Addresses that funded an issuance transaction, or None if any input
    cannot be resolved to a single address."""
    addresses = []
    for vin in issuance.get("vin", []):
        if "coinbase" in vin:
            return None
        funding = get_tx(vin.get("txid"))
        if not funding:
            return None
        try:
            address = output_address(funding["vout"][vin["vout"]])
        except (IndexError, KeyError, TypeError):
            return None
        if not address:
            return None
        addresses.append(address)
    return addresses


def examine_vote(vote_tx, get_tx, is_citizen):
    """
    Checks rules 1 and 2. Returns (None, reason) when the vote cannot count,
    otherwise ({"ballot_txid", "ballot_vout", "citizens"}, None).
    """
    vouts = vote_tx.get("vout", [])
    vins = vote_tx.get("vin", [])
    if len(vouts) != 1 or not is_data_output(vouts[0]):
        return None, "a vote has exactly one output, the data record"
    if len(vins) != 1 or "coinbase" in vins[0]:
        return None, "a vote spends exactly one ballot"

    ballot_txid = vins[0].get("txid")
    ballot_vout = vins[0].get("vout")
    issuance = get_tx(ballot_txid)
    if not issuance:
        return None, "the ballot's transaction could not be read"
    try:
        ballot = issuance["vout"][ballot_vout]
    except (IndexError, KeyError, TypeError):
        return None, "the ballot output does not exist"
    if zubrins(ballot.get("value", 0)) != BALLOT_ZUBRINS:
        return None, "the spent output is not a 0.1 MARS ballot"

    citizens = issuance_citizens(issuance, get_tx)
    if citizens is None:
        return None, "the issuance has an input that cannot be attributed"
    if len(citizens) < MIN_VOTERS:
        return None, f"the issuance has fewer than {MIN_VOTERS} participants"
    if len(set(citizens)) != len(citizens):
        return None, "the issuance uses the same address more than once"
    outsiders = [address for address in citizens if not is_citizen(address)]
    if outsiders:
        return None, "the issuance includes an address that is not a citizen"

    return {"ballot_txid": ballot_txid, "ballot_vout": ballot_vout, "citizens": citizens}, None


def voting_open(block_time, opens_at, closes_at):
    """Rule 3, on Unix timestamps. A proposal without a window never opens."""
    if opens_at is None or closes_at is None:
        return False
    return opens_at <= block_time < closes_at


def issuance_allowance(citizens, already_credited):
    """Rule 4: the citizens of an issuance who may still be credited on this
    proposal, given the addresses credited through earlier issuances."""
    credited = set(already_credited)
    return [address for address in citizens if address not in credited]
