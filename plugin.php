<?php
/**
 * Plugin Name:       Responsive Grid Extension
 * Plugin URI:        git@github.com:bob-moore/Responsive-Grid-Extension.git
 * Author:            Bob Moore
 * Author URI:        https://www.bobmoore.dev
 * Description:       Add responsive and custom grid template columns
 * Version:           0.1.3
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

function create_responsive_grid_extension_plugin(): void
{
	$plugin = new ResponsiveGridExtension(
		plugin_dir_url( __FILE__ ),
		plugin_dir_path( __FILE__ )
	);

	$plugin->mount();
}
create_responsive_grid_extension_plugin();
