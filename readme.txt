=== Responsive Grid Extension ===
Contributors: Bob Moore
Tags: grid, group block, layout, responsive, blocks
Requires at least: 6.7
Tested up to: 6.7
Stable tag: 0.1.0
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

1. Upload the plugin files to the `/wp-content/plugins/responsive-grid-extension` directory, or install the plugin through the WordPress plugins screen.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. If building locally, run `npm install` and `npm run build` before activation.
4. Add a Group block, set its layout to `grid`, and configure responsive columns and rows in the sidebar.

== Frequently Asked Questions ==

= Does this create a new block? =

No. It extends the core Group block.

= When do the controls appear? =

The responsive controls are intended for Group blocks using the `grid` layout type.

= Can this package be included via Composer? =

Yes. The asset loading is designed to work when the package is included by another plugin or theme, as long as it is installed in a public WordPress path.

== Changelog ==

= 0.1.0 =

* Initial release.
* Added responsive Group block grid column and row controls.

== Upgrade Notice ==

= 0.1.0 =

Initial release.