# Changelog
All notable changes to TaskRunner will be documented in this file.

This format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased
### Added
- Example task code in the README file.
- Some unit tests for TaskRunner and TaskConfig.

### Removed
- HHVM was removed from the Travis configuration file since it is no longer supported (http://hhvm.com/blog/2017/09/18/the-future-of-hhvm.html)

### Changed
- The configDir parameter was moved out of the TaskRunner constructor and into a new TaskConfig class to allow for testing.

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