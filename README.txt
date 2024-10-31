=== Plugin Name ===
Contributors: ema-digital
Donate link: 
Tags: rollbar, developer, error logging, error tracking
Requires at least: 4.4
Tested up to: 4.6.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables JavaScript and PHP Error logging for Rollbar.

== Description ==

Enables JavaScript and PHP Error logging for [Rollbar](https://rollbar.com/). This plugin
provides simple configuration options via the WordPress dashboard for quick and easy setup.

== Installation ==

1. Upload `rollbar-logging` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add your Rollbar configuration options for PHP and JavaScript logging to the 'Rollbar Logging'
setup page which appears under the 'Setup' menu in the WordPress dashboard.

== Frequently Asked Questions ==

= I only want to log PHP errors. Is there a way to turn off JavaScript logging? =

Yes, leave the client access token blank in the WordPress admin dashboard.

= I only want to log JavaScript errors. Is there a way to turn off PHP logging? =

Yes, leave the server access token blank in the WordPress admin dashboard.

= Is there a way to add more advanced configuration options for JavaScript? =

The plugin provides basic configuration for the Rollbar service, which will fit most use
cases. If greater control of what gets sent to Rollbar is needed, the best way to do it
is to place a separate JavaScript file in your theme that set the additional
configurations with `Rollbar.configure` at runtime as described in the
[Rollbar documentation](https://rollbar.com/docs/notifier/rollbar.js/).

== Screenshots ==

1. Options page.

== Changelog ==
= 1.1.2 =
* Update Rollbar PHP and JS to most current version
* Fix PHP warning for settings page

= 1.1.1 =
* Version bumping plugin, js, and css

= 1.1.0 =
* Added option to pause all logging
* New interface for configuration
* Adding new option to ignore specific JavaScript errors on the front end.

= 1.0.0 =
* Initial Release

== Upgrade Notice ==
= 1.1.0 =
* Easier configuration options

= 1.0.0 =
* Initial release
