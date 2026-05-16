# Agent Instructions

## Codebase summary

This package provides RFC-4122 compliant UUID value objects and helper functions. Runtime code lives in `src/` and `include/`; tests live in `tests/`.

## Development setup

```bash
devenv shell
composer install
```

No external services are required for this package.

## Checks

```bash
composer ci
composer pr
composer rector:check
composer rector
composer mago:fmt:check
composer mago:fmt
composer mago:lint
composer mago:analyze
composer test
composer test:unit
composer test:integration
composer test:e2e
```

Use `composer bench` or `composer profile` for benchmarks/profiling.
