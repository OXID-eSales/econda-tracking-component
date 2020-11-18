# Change Log for Econda Tracking component

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.0.6] - 2020-12-02

### Changed
- Change visibility of `OxidEsales\PersonalizationModule\Component\Tracking\TrackingCodeGenerator::initializePageView()` and TrackingCodeGenerator class members to protected.

## [1.0.5] - 2020-06-16

### Fixed
- Fix js cache refresh issue by adding file timestamp [PR-1](Added timestamp to URL to allow caching)

## [1.0.4] - 2019-07-05

### Added
- Add php 7.3 travis run

### Fixed
- Fix empty userId in thank you page for order with guest user.

## [1.0.3] - 2019-04-29

### Changed
- Do not include script to code generator if it's not set.

## [1.0.2] - 2018-12-04

### Changed
- Remove usage of deprecated method - `\OxidEsales\EshopCommunity\Core\Base::getConfig`.

## [1.0.1] - 2018-11-19

### Added
- Introduce `OxidEsales\EcondaTrackingComponent\Adapter\ProductPreparation\ProductInterface`.

## [1.0.0] - 2018-11-15


[1.0.6]: https://github.com/OXID-eSales/econda-tracking-component/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/OXID-eSales/econda-tracking-component/compare/v1.0.4...v1.0.5
[1.0.4]: https://github.com/OXID-eSales/econda-tracking-component/compare/v1.0.3...v1.0.4
