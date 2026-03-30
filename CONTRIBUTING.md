# Contributing to The Martian Republic

Thank you for your interest in building the governance infrastructure for Mars!

## Getting Started

1. Fork the repository
2. Clone your fork and set up the development environment (see [README.md](README.md#quick-start))
3. Create a feature branch: `git checkout -b feature/your-feature-name`

## Development Workflow

1. Make your changes
2. Run static analysis: `./vendor/bin/phpstan analyse --memory-limit=2G` (must pass with 0 errors)
3. Run tests: `./vendor/bin/pest --exclude-group wip` (all tests must pass)
4. Commit with a clear message describing the **why**, not just the **what**
5. Push to your fork and open a pull request

## Code Style

- PHP follows PSR-12 (enforce with `composer fix`)
- Blade templates use the custom design system (CSS variables: `--mr-void`, `--mr-dark`, `--mr-surface`, `--mr-cyan`, `--mr-mars`)
- Use `{{ }}` for all user-facing output (never `{!! !!}` without sanitization)
- Use `config()` for environment values, never `env()` outside of config files
- Use Laravel's `Process` facade instead of `shell_exec()` for external commands

## Pull Request Guidelines

- Keep PRs focused — one feature or fix per PR
- Include tests for new functionality
- Update documentation if you change behavior
- Reference any related issues

## Reporting Bugs

Open a [GitHub issue](https://github.com/marscoin/martianrepublic/issues) with:
- Steps to reproduce
- Expected vs actual behavior
- Browser/OS if relevant

## Security Vulnerabilities

Do **not** open a public issue. Email [info@marscoin.org](mailto:info@marscoin.org) instead.

## Code of Conduct

Be respectful, constructive, and collaborative. We're building a society here.
