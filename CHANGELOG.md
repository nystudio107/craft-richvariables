# Rich Variables Changelog

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
