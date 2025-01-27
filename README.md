[![Test Coverage & Analysis](https://github.com/marscoin/martianrepublic/actions/workflows/test-coverage.yaml/badge.svg)](https://github.com/marscoin/martianrepublic/actions/workflows/test-coverage.yaml)
[![Coverage Status](https://coveralls.io/repos/github/marscoin/martianrepublic/badge.svg)](https://coveralls.io/github/marscoin/martianrepublic)
<p align="center"><a href="https://martianrepublic.org" target="_blank"><img src="https://github.com/marscoin/martianrepublic/blob/main/public/assets/landing/img/headerpic.png" width="100%"></a></p>

## About MartianRepublic

MartianRepublic is a first reference implementation (web-based) of a decentralized governance system. It includes the following subsystems:

- **[Wallet](https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/wallet)**
- **[Citizen](https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/citizen)**
- **[Congress](https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/congress)**
- **[Inventory](https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/inventory)**
- **[Logbook](https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/logbook)**

MartianRepublic makes extensive use of the Marscoin blockchain's immutable ledger for anchoring and timestamping and IPFS for decentralized data storage.

## Documentation

MartianRepublic documentation can be found on gitbook [documentation](https://marscoin.gitbook.io/marscoin-documentation/martian-basecamp/).


## Contributing

Thank you for considering contributing to the Martian Republic project!


## Installation

## Configuration

Make sure to setup the blockchain scanning script `applicant_detector.py` via crontab

```bash
0 1 * * * cd /var/www/martianrepublic.org/scripts && /usr/bin/python3 applicant_detector.py >> /var/log/applicant_detector.log 2>&1
```

Use the provided code to write similar blockchain parsing tools to extract and cache any piece of data that you find interesting for your particular service for the citizens of the Martian Republic.

### Dependencies

This reference implementation is built on a LAMP stack (Laravel). See documentation for the protocol that makes it all work between this web-framework and the underlying Marscoin blockchain and IPFS data network. Besides the MartianRepublic code itself, you will need:

- **[Marscoind](https://github.com/marscoin/marscoin) a local Marscoin node**
- **[IPFS](https://github.com/ipfs) for pinning local data and making it available across the (Martian) network**
- **[Pebas](https://github.com/marscoin/pebas) an API bridge to consume Marssight Explorer data**


### For Mars
Copy project onto USB stick, including a copy of the Marscoin ledger, Marscoin node, IPFS node - then take a SpaceX Starship to Mars and upon arrival bootstrap an entire economic and governance hub using MartianRepublic.

### ...In the meantime
Checkout from github, then:

`composer update`

Make sure your local Marscoin node and IPFS node are running.


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to our dev team via [info@marscoin.org](mailto:info@marscoin.org). All security vulnerabilities will be promptly addressed.

## License

The MartianRepublic is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
