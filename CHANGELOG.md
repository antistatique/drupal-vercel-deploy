# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- add official support of drupal 10.6
- add official support of drupal 11.3

### Changed
- add sirbrillig to cspell words

## [1.3.1] - 2025-11-07
### Added
- add official support of drupal 10.5
- add official support of drupal 11.2

### Fixed
- fix dependabot file

### Removed
- drop coverage of Drupal 10.0.x
- drop coverage of Drupal 10.1.x
- drop coverage of Drupal 10.2.x
- drop coverage of Drupal 10.3.x
- drop coverage of Drupal 10.4.x

## [1.3.0] - 2025-06-03
### Removed
- drop support of Drupal 9.x

## [1.2.0] - 2025-02-26
### Added
- add coverage of Drupal 10.2.x
- add Drupal GitlabCI
- add phpstan.neon in order to ignore new static() errors
- add cpsell prject words for Gitlab-CI
- add official stable support for drupal 10.4
- add official stable support for drupal 11.1

### Removed
- drop tests support on Drupal <= 9.4
- remove legacy version annotation on docker-compose.yml

### Fixed
- fix phpcs use statements sorted alphabetically
- fix usage of print_r

### Changed
- run & fix css using stylelintrc from Drupal core
- update Docker MariaDB 10.3 -> 10.6

## [1.1.0] - 2022-12-02
### Added
- add official support of drupal 9.5 & 10.0

### Removed
- drop support of drupal below 9.3.x
- remove satackey/action-docker-layer-caching on Github Actions

### Fixed
- issue #3285626 by viniciuscosta, mrinalini9, bruno.bicudo, akshaydalvi212, sourabhjain, Arturo1007, wengerk, RenatoCostaDev, beatrizramos: Fix Coding Standards

## [1.0.0] - 2022-10-21
### Added
- add dependabot for Github Action dependency
- update changelog form to follow keepachangelog format
- run tests CI (Github Actions) on all push
- drop support of Drupal 8 and better support of Drupal 9+
- add job Upgrade Status to Github Actions

### Changed
- disable deprecation notice PHPUnit

## [1.0.0-rc1] - (2020-08-10)
### Added
- first alpha release of Vercel Deploy

[Unreleased]: https://github.com/antistatique/drupal-vercel-deploy/compare/1.3.1...HEAD
[1.3.1]: https://github.com/antistatique/drupal-vercel-deploy/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/antistatique/drupal-vercel-deploy/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/antistatique/drupal-vercel-deploy/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/antistatique/drupal-vercel-deploy/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/antistatique/drupal-vercel-deploy/compare/1.0.0-rc1...1.0.0
[1.0.0-rc1]: https://github.com/antistatique/drupal-vercel-deploy/releases/tag/1.0.0-rc1
