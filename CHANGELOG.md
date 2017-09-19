# Changelog
All notable changes to TaskRunner will be documented in this file.

This format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased

## [0.1.1] - 2017-09-19
### Added
- Log number of seconds individual tasks take to complete.

## [0.1.0] - 2017-09-19
### Added
- Monolog support and the ability to add multiple log channels/handlers.
- TaskException class.
- Documentation for the TaskRunner class methods.

### Changed
- TaskRunnerTest searches for a thrown TaskException instead of core Exception.
- The package now requires monolog.

### Fixed
- Added execute permissions to the executable so using 'php' is not required.