<?php
/**
 * Plugin Name:       Responsive Grid Extension
 * Plugin URI:        https://github.com/bob-moore/Responsive-Grid-Extension
 * Author:            Bob Moore
 * Author URI:        https://www.bobmoore.dev
 * Description:       Add responsive and custom grid template columns
 * Version:           0.1.6
 * Requires at least: 6.7
 * Tested up to:      7.0
 * Requires PHP:      8.2
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       responsive-grid-extension
 *
 * @package           responsive-grid-extension
 * @author            Bob Moore <bob@bobmoore.dev>
 * @license           GPL-2.0-or-later <https://www.gnu.org/licenses/gpl-2.0.html>
 * @link              https://www.bobmoore.dev
 */

use Bmd\ResponsiveGridExtension\ServiceLoader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/scoped/autoload.php';

new ServiceLoader();
