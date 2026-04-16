<?php
/**
 * Plugin Name:       Responsive Grid Extension
 * Plugin URI:        git@github.com:bob-moore/Responsive-Grid-Extension.git
 * Author:            Bob Moore
 * Author URI:        https://www.bobmoore.dev
 * Description:       Add responsive and custom grid template columns
 * Version:           0.1.1
 * Requires at least: 6.7
 * Tested up to:      6.7
 * Requires PHP:      8.2
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       grid-extensions
 *
 * @package           grid-extensions
 */

use Bmd\ResponsiveGridExtension;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * All the functionality is contained in the plugin class, with actions
 * and filters added at the bottom of this file to keep things organized.
 * 
 * This way, the plugin class can be imported via composer, and included in
 * other plugins if needed, without also importing the actions and filters.
 */
$plugin = new ResponsiveGridExtension();

add_action( 'enqueue_block_editor_assets', [ $plugin, 'enqueueEditorScript' ] );
add_action( 'wp_enqueue_scripts', [ $plugin, 'enqueueFrontendStyle' ] );
add_filter( 'render_block_core/group', [ $plugin, 'processGridBlock' ], 10, 2 );
