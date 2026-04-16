=== Responsive Grid Extension ===
Contributors: Bob Moore
Tags: grid, group block, layout, responsive, blocks
Requires at least: 6.7
Tested up to: 6.7
Stable tag: 0.1.1
Requires PHP: 8.2
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add responsive grid column and row controls to the core Group block.

== Description ==

Responsive Grid Extension adds responsive grid settings to the core Group block in the block editor.

Use it to define custom grid template columns and rows for desktop, tablet, and mobile without replacing the native Group block workflow.

The plugin adds editor controls and outputs matching classes and CSS custom properties on render so the frontend reflects the configured layout.

Features include:

* Responsive grid column settings for desktop, tablet, and mobile.
* Responsive grid row settings for desktop, tablet, and mobile.
* Extension of the native Group block instead of a separate custom block.
* Support for package-based usage in larger WordPress codebases.

== Installation ==

1. Download the latest installable ZIP from the GitHub Releases page: https://github.com/bob-moore/Responsive-Grid-Extension/releases
2. In WordPress admin, go to Plugins > Add New Plugin > Upload Plugin.
3. Upload the ZIP and activate the plugin through the Plugins screen in WordPress.
4. Add a Group block, set its layout to `grid`, and configure responsive columns and rows in the sidebar.

== Frequently Asked Questions ==

= Does this create a new block? =

No. It extends the core Group block.

= When do the controls appear? =

The responsive controls are intended for Group blocks using the `grid` layout type.

= Can this package be included via Composer? =

Yes.

1. Require `bmd/responsive-grid-extension` in your plugin or theme.
2. Instantiate `Bmd\ResponsiveGridExtension`.
3. Register the following hooks:
* `add_action( 'enqueue_block_editor_assets', [ $plugin, 'enqueueEditorScript' ] );`
* `add_action( 'wp_enqueue_scripts', [ $plugin, 'enqueueFrontendStyle' ] );`
* `add_filter( 'render_block_core/group', [ $plugin, 'processGridBlock' ], 10, 2 );`

Make sure Composer autoloading is active and the package is installed in a web-accessible WordPress path.

== Changelog ==

= 0.1.1 =

* Moved main class into `inc/` directory for Composer PSR-4 autoloading under `Bmd\`.
* Public class is now `Bmd\ResponsiveGridExtension` (previously `Bmd\ResponsiveGridExtension\Plugin`).
* Fixed asset path resolution after directory restructure.

= 0.1.0 =

* Initial release.
* Added responsive Group block grid column and row controls.

== Upgrade Notice ==

= 0.1.1 =

Namespace updated to `Bmd\ResponsiveGridExtension`. Update any Composer integrations that previously referenced `Bmd\ResponsiveGridExtension\Plugin`.

= 0.1.0 =

Initial release.