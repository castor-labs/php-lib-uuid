# Changelog

## [3.0.0](https://github.com/castor-labs/php-lib-uuid/compare/2.0.0...3.0.0) (2025-01-03)


### ⚠ BREAKING CHANGES

* Removed Brick\Math\BigNumber from Time classes

### Features

* Performance improvements ([#55](https://github.com/castor-labs/php-lib-uuid/issues/55)) ([3273897](https://github.com/castor-labs/php-lib-uuid/commit/3273897ff4bbb2866836d9892ccbf899d03ae19c))

## [2.0.0](https://github.com/castor-labs/php-lib-uuid/compare/1.0.0...2.0.0) (2024-07-17)


### ⚠ BREAKING CHANGES

* `Castor\Uuid\Version6::getTime` now returns `Castor\Uuid\System\Gregorian`

### Features

* Implemented version 7 UUID ([#6](https://github.com/castor-labs/php-lib-uuid/issues/6)) ([48779cc](https://github.com/castor-labs/php-lib-uuid/commit/48779cc185009d12a49dbfaf0cee708b2abe6a7d))

## [1.0.0](https://github.com/castor-labs/php-lib-uuid/compare/0.2.0...1.0.0) (2024-07-16)


### ⚠ BREAKING CHANGES

* As part of the implementation, some internal classes were moved to a different namespace

### Features

* Implement UUID version 6 ([#4](https://github.com/castor-labs/php-lib-uuid/issues/4)) ([e721754](https://github.com/castor-labs/php-lib-uuid/commit/e72175441381cb6ba3c1d80309bad60afbacb33d))

## [0.2.0](https://github.com/castor-labs/php-lib-uuid/compare/v0.1.0...0.2.0) (2024-07-05)


### Features

* Lazy uuid support ([b775865](https://github.com/castor-labs/php-lib-uuid/commit/b7758658bb30bbe52340b992a96f22133a77e4af))
* New router ([b1f088d](https://github.com/castor-labs/php-lib-uuid/commit/b1f088d684b9e44a7b08ea480eb6680e75a55c79))
* Removed castor/random as a dependency ([#3](https://github.com/castor-labs/php-lib-uuid/issues/3)) ([2a5feeb](https://github.com/castor-labs/php-lib-uuid/commit/2a5feeb03865548186211358c3c8f0e2409629a2))
