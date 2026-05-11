=== Responsive Grid Extension ===
Contributors: Bob Moore
Tags: grid, group block, layout, responsive, blocks
Requires at least: 6.7
Tested up to: 7.0
Stable tag: 0.1.6
Requires PHP: 8.2
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add responsive column and row controls to the core/group block when it uses Grid layout.

== Description ==

Responsive Grid Extension extends the core/group block with responsive Grid layout controls in the block sidebar.

What it does:

* Adds responsive grid controls to core/group in the block editor.
* Lets you set grid template columns for desktop, tablet, and mobile.
* Lets you set grid template rows for desktop, tablet, and mobile.
* Keeps the native Group block workflow instead of introducing a custom block.
* Outputs responsive classes and CSS custom properties on the frontend.
* Loads frontend styles only when a rendered Grid Group block needs them.

This plugin is distributed through GitHub releases and includes a scoped updater so WordPress can surface updates from this repository.

== Installation ==

= Install as a WordPress plugin =

1. Download the latest release zip from GitHub.
2. In WordPress admin, go to Plugins > Add New Plugin > Upload Plugin.
3. Upload the zip and activate Responsive Grid Extension.

= Install via Composer (library usage) =

1. Require the package:

`composer require bmd/responsive-grid-extension`

2. Ensure Composer autoloading is loaded:

`require_once __DIR__ . '/vendor/autoload.php';`

3. Instantiate and mount the service:

`use Bmd\ResponsiveGridExtension\Plugin;`
`$plugin = new Plugin( $dependency_url, $dependency_path );`
`$plugin->mount();`

The constructor expects the URL and filesystem path to the Responsive Grid Extension dependency root, not the file where you call it.

== Frequently Asked Questions ==

= Is this plugin in the WordPress Plugin Directory? =

No. It is distributed via GitHub releases.

= Does this plugin support updates in wp-admin? =

Yes. It includes a GitHub updater integration so WordPress can detect updates from this repo.

= Does this create a new block? =

No. It extends the core WordPress Group block (`core/group`).

= When do the controls appear? =

The responsive controls are intended for Group blocks using the Grid layout type.

== Changelog ==

= 0.1.6 =

* Refactored PHP architecture into dedicated Plugin, ServiceLoader, and Utilities classes.
* Renamed plugin entrypoint to responsive-grid-extension.php.
* Split GitHub Actions lint workflow into separate CSS, JS, and PHP workflows.
* Updated Composer namespace and autoloading to Bmd\ResponsiveGridExtension.
* Updated PHPUnit tests to cover new Plugin class.

= 0.1.5 =

* Refined the PHP plugin architecture around a dedicated bootstrapper, plugin service, and utility helper.
* Updated Composer autoloading for the new `Bmd\ResponsiveGridExtension` namespace structure.
* Renamed the standalone plugin entrypoint to `responsive-grid-extension.php`.
* Added separate GitHub Actions lint workflows for CSS, JS, and PHP.
* Optimized frontend asset loading so responsive grid styles enqueue only when a rendered Grid Group block is present.
* Rebuilt scoped updater dependencies.

= 0.1.4 =

* Added scoped GitHub updater bootstrap using bmd/github-wp-updater from vendor/scoped.
* Added release and instruction baselines under .github for scoped updater and production packaging workflows.

= 0.1.3 =

* Introduced a basic plugin interface defining the mount, setUrl, and setPath contract.
* Constructor accepts optional URL and path parameters for flexible asset resolution when used as a Composer dependency.
* Plugin bootstrap is wrapped in a named function.

= 0.1.2 =

* Added mount method to register all WordPress hooks in one call.
* Simplified plugin bootstrap.

= 0.1.1 =

* Moved the main class into inc for Composer PSR-4 autoloading.
* Fixed asset path resolution after directory restructure.

= 0.1.0 =

* Initial release.
* Added responsive Group block grid column and row controls.

== Upgrade Notice ==

= 0.1.5 =

Updates plugin internals, Composer namespacing, the standalone plugin entrypoint, GitHub Actions lint workflows, scoped updater dependencies, and conditional frontend style loading.
