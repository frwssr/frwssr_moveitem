# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## v0.1 - 2022-12-08
### Added
- Initial release – somewhat of a preview version.

## v0.2 - 2023-09-29
### Fixed
- PHP 8.x doesn’t cope as well with “undefined” variables or arrays as previous versions. This release fixes an error stemming from that.
- Also, MySQL 8.x has a changed syntax for excluding items in a `SELECT` statement. `… WHERE itemID = {itm} (NOT itemRev = {itemRev})` now reads `… WHERE itemID = {itm} AND itemRev NOT IN ({itemRev})'`, thus no longer breaking the process of moving.
### Enhanced
- Also, this release throws DB connection errors more reliably, which makes troubleshooting a lot easier.