<?php
/**
 * Plugin service loader class.
 *
 * PHP Version 8.2
 *
 * @package    Bmd\ResponsiveGridExtension
 * @author     Bob Moore <bob@bobmoore.dev>
 * @license    GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link       https://www.bobmoore.dev
 * @since      1.0.0
 */

namespace Bmd\ResponsiveGridExtension;

use Bmd\ResponsiveGridExtension\Bmd\GithubWpUpdater;

/**
 * Service loader/locator class for the responsive grid extension plugin.
 */
class ServiceLoader
{
	/**
	 * Main plugin service instance.
	 *
	 * @var Plugin|null
	 */
	protected static ?Plugin $instance = null;

	/**
	 * Constructor.
	 *
	 * Initializes the plugin service and update checker once.
	 */
	public function __construct()
	{
		$plugin_file = dirname( __DIR__ ) . '/responsive-grid-extension.php';

		if ( null === self::$instance ) {
			self::$instance = new Plugin(
				plugin_dir_url( $plugin_file ),
				plugin_dir_path( $plugin_file )
			);

			self::$instance->mount();

			$updater = new GithubWpUpdater(
				$plugin_file,
				[
					'github.user'   => 'bob-moore',
					'github.repo'   => 'Responsive-Grid-Extension',
					'github.branch' => 'main',
				]
			);

			$updater->mount();
		}
	}

	/**
	 * Get the initialized plugin service.
	 *
	 * @return Plugin|null Plugin service, or null before bootstrap has run.
	 */
	public static function getInstance(): ?Plugin
	{
		return self::$instance;
	}
}
