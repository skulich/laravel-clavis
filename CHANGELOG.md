# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Documentation
- README: clarified that rotating `APP_KEY` does not invalidate `CLAVIS_HASH` (token auth uses bcrypt `Hash::check`, not `Crypt`/`encrypt()`)

## [1.1.1] - 2026-03-31

### Security
- Dispatch `Illuminate\Auth\Events\Failed` on failed authentication attempts with masked token
- `base64_decode()` now uses strict mode to properly reject malformed `CLAVIS_HASH` values
- Added `SECURITY.md` for responsible vulnerability disclosure

### Improved
- Rector config: enabled `typeDeclarations`, `privatization`, `earlyReturn`, `codingStyle`, `withPhpSets()`
- Helper methods in `ClavisTokenCommand` changed from `protected` to `private` (class is `final`)
- Added `#[Override]` attribute to `register()` in `ClavisServiceProvider`
- CI cache key now uses `composer.json` hash instead of `composer.lock` (which is gitignored)
- Added `support` block to `composer.json` with issues and source URLs
- Added documentation comments to `config/clavis.php`

### Documentation
- README: added "Generate Token", "Rotate Token", "Failed Auth Events", "Nota Bene" sections
- Added GitHub PR template and filled in feature request issue template

### Tests
- Added tests for empty and invalid `CLAVIS_HASH` values
- Added test for middleware string alias `'clavis'`
- Added tests for `Failed` event dispatching with masked token
- All test closures now have `: void` return types and use arrow functions where applicable

## [1.1.0] - 2026-03-31

### Added
- Laravel 13.x support (`laravel/framework: ^12.0 || ^13.0`)
- `composer test12` and `composer test13` scripts for testing against specific Laravel versions locally

### Updated
- `orchestra/testbench` constraint expanded to `^10.8 || ^11.0`
- CI matrix now runs against both Laravel 12.* and 13.*
- Fixed CI workflow to correctly pin Laravel version per matrix entry during dependency installation

## [1.0.0] - 2025-12-22

- Initial implementation
