# Rich Variables Changelog

## 1.0.30 - 2022.01.12

### Added

* Add `.gitattributes` & `CODEOWNERS`
* Add linting to build
* Add compression of assets
* Add bundle visualizer

## 1.0.29 - 2021.04.15

### Changed

* Cache the Rich Variables menu in local storage, to increase performance for multiple rich text fields per
  page (https://github.com/nystudio107/craft-richvariables/issues/29)

## 1.0.28 - 2021.04.06

### Added

* Added `make update` to update NPM packages
* Added `make update-clean` to completely remove `node_modules/`, then update NPM packages

### Changed

* More consistent `makefile` build commands
* Use Tailwind CSS `^2.1.0` with JIT
* Move settings from the `composer.json` “extra” to the plugin main class
* Move the manifest service registration to the constructor
* Remove deprecated ManifestController

## 1.0.27 - 2021.03.03

### Changed

* Dockerized the buildchain, using `craft-plugin-manifest` for the webpack HMR bridge

## 1.0.26 - 2021.02.13

### Changed

* Update to webpack 5 buildchain

## 1.0.25 - 2020.04.02

### Added

* Added back in support for the Preparse field

### Fixed

* Restored the missing icon from the drop-down menu

### Changed

* Updated to latest npm dependencies via `npm audit fix` for both the primary app and the docs

## 1.0.24 - 2019.11.04

### Changed

* Updated to latest npm dependencies via `npm audit fix`

## 1.0.23 - 2019.05.24

### Changed

* Updated build system

## 1.0.22 - 2019.04.22

### Changed

* Updated Twig namespacing to be compliant with deprecated class aliases in 2.7.x

## 1.0.21 - 2018.10.05

### Changed

* Updated build process

## 1.0.20 - 2018.08.23

### Added

* Added welcome screen after install
* Added a new b&w menu icon to match Redactor styles

### Changed

* Moved to a modern webpack build config for the Control Panel
* Added install confetti

## 1.0.19 - 2018.05.19

### Changed

* Fixed a regression in the Settings that caused an exception to be thrown

## 1.0.18 - 2018.05.18

### Changed

* Added support for Redactor 2.x plugin from Pixel & Tonic
* Refactor to load JS/CSS conditionally based on the version of the Redactor plugin
* Install event handlers after all plugins have loaded
* Fixed missing translations

## 1.0.17 - 2018.03.02

### Changed

* Fixed the controller URL which stopped working in Craft CMS 3 RC13
* Fixed deprecation errors from Craft CMS 3 RC13
* Code cleanup

## 1.0.16 - 2018.02.01

### Added

* Renamed the composer package name to `craft-richvariables`

## 1.0.15 - 2017.12.12

### Changed

* Only load our AssetBundle on non-console Control Panel requests

## 1.0.14 - 2017.12.06

### Changed

* Updated to require craftcms/cms `^3.0.0-RC1`

## 1.0.13 - 2017.10.22

### Changed

* Craft 3 RC 1 compatibility

## 1.0.12 - 2017.07.15

### Changed

* Craft 3 beta 20 compatibility

## 1.0.11 - 2017.04.20

### Changed

* Added support for `RichText` fields to be selected as variables

## 1.0.10 - 2017.04.20

### Changed

* Register the Redactor plugin via `RichText::EVENT_REGISTER_REDACTOR_PLUGIN`

## 1.0.9 - 2017.03.24

### Changed

* `hasSettings` -> `hasCpSettings` for Craft 3 beta 8 compatibility

## 1.0.8 - 2017.03.13

### Fixed

* The handle used for translations is now all lowercase

## 1.0.7 - 2017.03.12

### Added

* Added `craft/cms` as a composer dependency
* Added code inspection typehinting for the plugin

## 1.0.6 - 2017.02.24

### Fixed

* Fixed a styling issue with Redactor 2.2

### Changed

* Removed the unused `icon-mask.svg` file
* Removed the unused `config.php` file

## 1.0.5 - 2017.02.09

### Changed

* Removed `allowAnonymous` from the controller, since we only want to work for logged in users
* Cleaned up `composer.json`

## 1.0.4 - 2017.02.08

### Changed

* Improved how we retrieved the settings, using RichVariables::$plugin
* Removed the Issues from the README.md

## 1.0.3 - 2017.02.07

### Fixed

* Fixed an issue where the user might be redirected errantly to JSON settings on login

### Changed

* Removed the spaces inside of the inserted `<ins>` tags

## 1.0.2 - 2017.02.06

### Added

* Added a setting to control whether the Rich Variables menu should be text or an icon

### Changed

* Harmonized the code with the Craft 2 version

## 1.0.1 - 2017.02.05

### Fixed

- Fixed the icon by passing it in through our JavaScript response

## 1.0.0 - 2017.02.05

### Added

- Initial port to Craft 3
