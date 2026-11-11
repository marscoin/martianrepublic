"""
Whose record is it.

Run from the repository root:
    python3 -m unittest discover -s scripts/tests -v
"""
import os
import sys
import unittest

sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..'))

import record_author as rules  # noqa: E402


def out(address, value):
    return {'value': value, 'scriptPubKey': {'asm': 'OP_DUP ...', 'address': address}}


DATA = {'value': 0, 'scriptPubKey': {'asm': 'OP_RETURN 47505f516d'}}


class RecordAuthor(unittest.TestCase):

    def setUp(self):
        self.txs = {
            'a' * 64: {'vout': [out('ALICE', 5)]},
            'b' * 64: {'vout': [out('BOB', 5), {'value': 1, 'scriptPubKey': {'asm': 'x', 'addresses': ['CAROL']}}]},
        }
        self.get = self.txs.get

    def spend(self, *inputs):
        return [{'txid': txid, 'vout': n} for txid, n in inputs]

    def test_a_wallet_writing_its_own_record(self):
        tx = {'vin': self.spend(('a' * 64, 0)), 'vout': [out('ALICE', 4.99), DATA]}
        self.assertEqual('ALICE', rules.record_author(tx, self.get))

    def test_sending_coins_to_someone_does_not_make_the_record_theirs(self):
        # Bob pays Alice the largest output and attaches a record.
        tx = {'vin': self.spend(('b' * 64, 0)), 'vout': [out('ALICE', 4.9), out('BOB', 0.05), DATA]}
        self.assertEqual('BOB', rules.record_author(tx, self.get))

    def test_record_with_all_coins_sent_away_still_belongs_to_the_signer(self):
        tx = {'vin': self.spend(('b' * 64, 0)), 'vout': [out('ALICE', 4.99), DATA]}
        self.assertEqual('BOB', rules.record_author(tx, self.get))

    def test_prefers_a_signer_the_registry_knows(self):
        tx = {'vin': self.spend(('b' * 64, 0), ('b' * 64, 1)), 'vout': [out('DAVE', 5.9), DATA]}
        self.assertEqual('CAROL', rules.record_author(tx, self.get, lambda a: a == 'CAROL'))

    def test_no_known_signer_means_no_author(self):
        tx = {'vin': self.spend(('b' * 64, 0)), 'vout': [out('ALICE', 4.99), DATA]}
        self.assertIsNone(rules.record_author(tx, self.get, lambda a: a == 'ALICE'))

    def test_unknown_inputs_mean_no_author(self):
        tx = {'vin': self.spend(('c' * 64, 0)), 'vout': [out('ALICE', 1), DATA]}
        self.assertIsNone(rules.record_author(tx, self.get))
        self.assertIsNone(rules.record_author({'vin': [{'txid': 'a' * 64, 'vout': 9}], 'vout': [out('ALICE', 1)]}, self.get))

    def test_signers_are_listed_once_in_input_order_and_skip_coinbase(self):
        tx = {'vin': [{'coinbase': '00'}] + self.spend(('b' * 64, 1), ('b' * 64, 0), ('b' * 64, 1)), 'vout': []}
        self.assertEqual(['CAROL', 'BOB'], rules.signer_addresses(tx, self.get))


if __name__ == '__main__':
    unittest.main()
