"""
Rules for counting votes, and how the tracker applies them.

Run from the repository root:
    python3 -m unittest discover -s scripts/tests -v
"""
import os
import sys
import unittest
from datetime import datetime

sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..'))
os.environ.setdefault('DB_PORT', '3306')

import vote_validation as rules  # noqa: E402

CID = 'Qmaow2cUCwQoYmKM37cgXLCFAtJFXz7h2xSrKCQDt6nrxw'


class Chain:
    """A tiny chain of decoded transactions."""

    def __init__(self):
        self.txs = {}
        self.n = 0

    def add(self, vin, vout):
        self.n += 1
        txid = f'{self.n:064x}'
        self.txs[txid] = {'txid': txid, 'vin': vin, 'vout': [dict(v, n=i) for i, v in enumerate(vout)]}
        return txid

    def get(self, txid):
        return self.txs.get(txid)

    def pay(self, address, value):
        return {'value': value, 'scriptPubKey': {'asm': 'OP_DUP OP_HASH160 00 OP_EQUALVERIFY OP_CHECKSIG', 'address': address}}

    def funding(self, address, value=0.5):
        """An ordinary earlier payment to a citizen's civic address."""
        return self.add([{'coinbase': '00'}], [self.pay(address, value)])

    def issuance(self, addresses, extra_outputs=()):
        """A ballot issuance: one input per participant, change back to each,
        then one 0.1 MARS ballot per participant."""
        vin = [{'txid': self.funding(address), 'vout': 0} for address in addresses]
        vout = [self.pay(address, 0.3) for address in addresses]
        vout += [self.pay(f'ballot-{self.n}-{i}', 0.1) for i in range(len(addresses))]
        vout += list(extra_outputs)
        return self.add(vin, vout)

    def vote(self, issuance, index, tag='PRY', cid=CID):
        """Spends the index-th ballot of an issuance into a vote."""
        ballots = [v['n'] for v in self.txs[issuance]['vout'] if rules.zubrins(v['value']) == rules.BALLOT_ZUBRINS]
        data = f'{tag}_{cid}'.encode().hex()
        txid = self.add([{'txid': issuance, 'vout': ballots[index]}], [{'value': 0, 'scriptPubKey': {'asm': f'OP_RETURN {data}'}}])
        return self.txs[txid]


CITIZENS = {'Malice', 'Mbob', 'Mcarol', 'Mdave', 'Merin', 'Mfrank'}
is_citizen = CITIZENS.__contains__


class VoteRules(unittest.TestCase):
    def setUp(self):
        self.chain = Chain()

    def examine(self, tx):
        return rules.examine_vote(tx, self.chain.get, is_citizen)

    def test_a_vote_from_a_citizen_issuance_is_accepted(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        ballot, reason = self.examine(self.chain.vote(join, 1))
        self.assertIsNone(reason)
        self.assertEqual(ballot['ballot_txid'], join)
        self.assertEqual(sorted(ballot['citizens']), ['Malice', 'Mbob', 'Mcarol'])

    def test_a_bare_data_transaction_is_not_a_vote(self):
        # What anyone could publish: a data output funded by an ordinary coin.
        coin = self.chain.funding('Mallory', 0.1)
        data = f'PRY_{CID}'.encode().hex()
        txid = self.chain.add([{'txid': coin, 'vout': 0}], [{'value': 0, 'scriptPubKey': {'asm': f'OP_RETURN {data}'}}])
        ballot, reason = self.examine(self.chain.get(txid))
        self.assertIsNone(ballot)
        self.assertTrue(reason)

    def test_the_spent_output_must_be_exactly_a_ballot(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        data = f'PRY_{CID}'.encode().hex()
        # Spending a change output of a genuine issuance is not a vote.
        txid = self.chain.add([{'txid': join, 'vout': 0}], [{'value': 0, 'scriptPubKey': {'asm': f'OP_RETURN {data}'}}])
        self.assertIn('not a 0.1 MARS ballot', self.examine(self.chain.get(txid))[1])

    def test_an_issuance_with_an_outsider_issues_no_ballots(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mallory'])
        self.assertIn('not a citizen', self.examine(self.chain.vote(join, 0))[1])

    def test_an_issuance_needs_three_distinct_participants(self):
        self.assertIn('fewer than 3', self.examine(self.chain.vote(self.chain.issuance(['Malice', 'Mbob']), 0))[1])
        self.assertIn('more than once', self.examine(self.chain.vote(self.chain.issuance(['Malice', 'Malice', 'Mbob']), 0))[1])

    def test_a_vote_has_one_input_and_one_output(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        vote = self.chain.vote(join, 0)
        with_change = dict(vote, vout=vote['vout'] + [self.chain.pay('Malice', 0.05)])
        self.assertIn('exactly one output', self.examine(with_change)[1])
        two_ballots = dict(vote, vin=vote['vin'] + [{'txid': join, 'vout': 4}])
        self.assertIn('exactly one ballot', self.examine(two_ballots)[1])

    def test_unreadable_history_is_refused_rather_than_guessed(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        vote = self.chain.vote(join, 0)
        funding = self.chain.get(join)['vin'][0]['txid']
        del self.chain.txs[funding]
        self.assertIn('cannot be attributed', self.examine(vote)[1])
        del self.chain.txs[join]
        self.assertIn('could not be read', self.examine(vote)[1])

    def test_amounts_are_compared_exactly(self):
        self.assertEqual(rules.zubrins(0.1), 10_000_000)
        self.assertEqual(rules.zubrins('0.10000000'), 10_000_000)
        self.assertEqual(rules.zubrins(0.09999999), 9_999_999)
        self.assertEqual(rules.zubrins(0.29999999999999998), 30_000_000)

    def test_voting_window(self):
        self.assertTrue(rules.voting_open(150, 100, 200))
        self.assertTrue(rules.voting_open(100, 100, 200))
        self.assertFalse(rules.voting_open(200, 100, 200))
        self.assertFalse(rules.voting_open(99, 100, 200))
        self.assertFalse(rules.voting_open(150, None, 200))
        self.assertFalse(rules.voting_open(150, 100, None))

    def test_a_citizen_is_credited_once_per_proposal(self):
        self.assertEqual(rules.issuance_allowance(['Malice', 'Mbob', 'Mcarol'], []), ['Malice', 'Mbob', 'Mcarol'])
        self.assertEqual(rules.issuance_allowance(['Malice', 'Mdave', 'Merin'], ['Malice', 'Mbob', 'Mcarol']), ['Mdave', 'Merin'])
        self.assertEqual(rules.issuance_allowance(['Malice', 'Mbob', 'Mcarol'], ['Malice', 'Mbob', 'Mcarol']), [])


class FakeDatabase:
    """Just enough of the tracker's tables to exercise cache_vote."""

    def __init__(self, chain, opens, closes):
        self.chain = chain
        self.proposal = {'id': 14, 'screening_ends_at': opens, 'voting_ends_at': closes}
        self.votes = []
        self.credits = []
        self.result = None
        self.commits = 0

    def cursor(self):
        return self

    def commit(self):
        self.commits += 1

    def rollback(self):
        pass

    def execute(self, query, params=()):
        q = ' '.join(query.split())
        if q.startswith('SELECT id, screening_ends_at'):
            self.result = [self.proposal] if params[0] == CID else []
        elif q.startswith('SELECT 1 FROM votes WHERE txid'):
            self.result = [1 for v in self.votes if v['txid'] == params[0]]
        elif q.startswith('SELECT userid FROM citizen'):
            self.result = [{'userid': params[0]}] if params[0] in CITIZENS else []
        elif q.startswith('SELECT user_id FROM'):
            self.result = []
        elif q.startswith("SELECT 1 FROM feed WHERE tag = 'CT'"):
            self.result = [1]
        elif q.startswith('SELECT address, join_txid FROM ballot_credits'):
            self.result = [dict(c) for c in self.credits if c['proposal_id'] == params[0]]
        elif q.startswith('INSERT IGNORE INTO ballot_credits'):
            if not any(c['proposal_id'] == params[0] and c['address'] == params[2] for c in self.credits):
                self.credits.append({'proposal_id': params[0], 'join_txid': params[1], 'address': params[2]})
        elif q.startswith('SELECT COUNT(*) AS n FROM ballot_credits'):
            self.result = [{'n': sum(1 for c in self.credits if c['proposal_id'] == params[0] and c['join_txid'] == params[1])}]
        elif q.startswith('SELECT COUNT(*) AS n FROM votes'):
            self.result = [{'n': sum(1 for v in self.votes if v['proposal_id'] == params[0] and v['ballot_txid'] == params[1])}]
        elif q.startswith('INSERT INTO votes'):
            self.votes.append({'vote': params[0], 'proposal_id': params[1], 'txid': params[2], 'ballot_txid': params[5]})
        else:
            raise AssertionError(f'unexpected query: {q}')

    def fetchone(self):
        return self.result[0] if self.result else None

    def fetchall(self):
        return list(self.result or [])


try:
    import track_blockchain as tracker
except Exception as error:  # dependencies of the tracker are not installed
    tracker = None
    IMPORT_ERROR = error


@unittest.skipIf(tracker is None, 'tracker dependencies not installed')
class TrackerCounting(unittest.TestCase):
    def setUp(self):
        self.chain = Chain()
        # The website stores the window in UTC; block times arrive as local time.
        self.db = FakeDatabase(self.chain, datetime(2026, 11, 13, 0, 0), datetime(2026, 11, 27, 0, 0))
        tracker.get_tx_details = self.chain.get
        self.during = datetime.fromtimestamp(datetime(2026, 11, 20, 12, 0).timestamp())

    def count(self, vote_tx, when=None, tag='PRY', cid=CID):
        tracker.cache_vote(self.db, self.db, tag, cid, vote_tx, 3600000, when or self.during)

    def test_counts_one_vote_per_ballot_of_a_citizen_issuance(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        for index, tag in enumerate(['PRY', 'PRN', 'PRA']):
            self.count(self.chain.vote(join, index, tag), tag=tag)
        self.assertEqual([v['vote'] for v in self.db.votes], ['Y', 'N', 'A'])
        self.assertEqual(len(self.db.credits), 3)

    def test_ignores_stuffed_votes(self):
        coin = self.chain.funding('Mallory', 0.1)
        data = f'PRY_{CID}'.encode().hex()
        for _ in range(5):
            txid = self.chain.add([{'txid': coin, 'vout': 0}], [{'value': 0, 'scriptPubKey': {'asm': f'OP_RETURN {data}'}}])
            self.count(self.chain.get(txid))
        self.assertEqual(self.db.votes, [])

    def test_ignores_votes_outside_the_voting_window(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        self.count(self.chain.vote(join, 0), when=datetime(2026, 11, 12, 12, 0))
        self.count(self.chain.vote(join, 1), when=datetime(2026, 11, 28, 12, 0))
        self.assertEqual(self.db.votes, [])
        self.count(self.chain.vote(join, 2))
        self.assertEqual(len(self.db.votes), 1)

    def test_the_window_is_judged_in_utc_whatever_the_server_timezone(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        from datetime import timezone
        opening = datetime(2026, 11, 13, 0, 0, tzinfo=timezone.utc).timestamp()
        self.count(self.chain.vote(join, 0), when=datetime.fromtimestamp(opening - 60))
        self.assertEqual(len(self.db.votes), 0)
        self.count(self.chain.vote(join, 1), when=datetime.fromtimestamp(opening + 60))
        self.assertEqual(len(self.db.votes), 1)

    def test_a_citizen_in_two_issuances_gains_no_extra_vote(self):
        first = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        second = self.chain.issuance(['Malice', 'Mdave', 'Merin'])
        for index in range(3):
            self.count(self.chain.vote(first, index))
        for index in range(3):
            self.count(self.chain.vote(second, index))
        # Five citizens took part, so five votes count, not six.
        self.assertEqual(len(self.db.votes), 5)
        self.assertEqual(sorted(c['address'] for c in self.db.credits), ['Malice', 'Mbob', 'Mcarol', 'Mdave', 'Merin'])

    def test_an_issuance_of_already_counted_citizens_adds_nothing(self):
        first = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        again = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        for index in range(3):
            self.count(self.chain.vote(first, index))
            self.count(self.chain.vote(again, index))
        self.assertEqual(len(self.db.votes), 3)

    def test_an_extra_ballot_sized_output_cannot_add_a_vote(self):
        # A participant whose change happens to be exactly 0.1 MARS.
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'], extra_outputs=[self.chain.pay('Malice', 0.1)])
        for index in range(4):
            self.count(self.chain.vote(join, index))
        self.assertEqual(len(self.db.votes), 3)

    def test_processing_a_block_twice_counts_its_votes_once(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        vote = self.chain.vote(join, 0)
        self.count(vote)
        self.count(vote)
        self.assertEqual(len(self.db.votes), 1)

    def test_unknown_or_malformed_proposal_references_are_ignored(self):
        join = self.chain.issuance(['Malice', 'Mbob', 'Mcarol'])
        self.count(self.chain.vote(join, 0, cid='%'), cid='%')
        self.count(self.chain.vote(join, 1, cid='QmUnknown'), cid='QmUnknown')
        self.assertEqual(self.db.votes, [])

    def test_lifecycle_for_a_proposal_first_seen_on_chain(self):
        mined = datetime.fromtimestamp(datetime(2026, 11, 10, 0, 0, tzinfo=__import__('datetime').timezone.utc).timestamp())
        life = tracker.proposal_lifecycle('operational', mined)
        self.assertEqual(str(life['screening_ends_at']), '2026-11-13 00:00:00')
        self.assertEqual(str(life['voting_ends_at']), '2026-11-27 00:00:00')
        self.assertEqual(str(life['timelock_ends_at']), '2026-11-30 00:00:00')
        self.assertIsNone(tracker.proposal_lifecycle('signal', mined)['timelock_ends_at'])
        self.assertEqual(tracker.proposal_tier('law'), 'legislative')
        self.assertEqual(tracker.proposal_tier('Operational'), 'operational')
        self.assertEqual(tracker.proposal_tier('anything else'), 'signal')


if __name__ == '__main__':
    unittest.main()
