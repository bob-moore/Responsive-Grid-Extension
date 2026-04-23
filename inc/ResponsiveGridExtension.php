<?php
/**
 * Main plugin functionality
 *
 * PHP Version 8.2
 *
 * @package    Bmd\ResponsiveGridExtension
 * @author     Bob Moore <bob@bobmoore.dev>
 * @license    GPL-2.0+ <http://www.gnu.org/licenses/gpl-2.0.txt>
 * @link       https://www.bobmoore.dev
 * @since      1.0.0
 */

namespace Bmd;

/**
 * Coordinates runtime behavior for the Responsive Grid Extension package.
 *
 * Responsibilities:
 * - Enqueue editor assets (script + styles)
 * - Enqueue frontend styles
 * - Transform rendered core/group block markup when responsive grid attributes are present
 *
 * Extension notes:
 * - Subclasses can override buildPath() and buildUrl() when integrating in custom directory layouts.
 * - Subclasses can override enqueueEditorScript() and enqueueFrontendStyle() to customize handle names or loading strategy.
 * - Subclasses can override processGridBlock() to change class/CSS variable naming conventions.
 *
 * Composer integration example:
 * - $plugin = new ResponsiveGridExtension( plugin_dir_url( __FILE__ ), plugin_dir_path( __FILE__ ) );
 * - $plugin->mount();
 */
class ResponsiveGridExtension implements BasicPlugin
{
	/**
	* URL of this plugin/package.
	*
	* Used to enqueue block editor assets.
	*
	* @var string
	*/
	protected string $url;
	/**
	* Path of the plugin/package.
	*
	* Used to locate block editor assets.
	*
	* @var string
	*/
	protected string $path;
	/**
	* Initialize the plugin.
	*
	* Sets the URL and path for this package.
	*
	* @param string $url  URL to the plugin directory.
	* @param string $path Absolute path to the plugin directory.
	*/
	public function __construct(
		string $url = '',
		string $path = ''
	) {
		$this->setUrl( ! empty( $url ) ? esc_url_raw( $url ) : plugin_dir_url( __DIR__ ) );
		$this->setPath( ! empty( $path ) ? esc_html( $path ) : plugin_dir_path( __DIR__ ) );
	}
	/**
	* Setter for the URL property.
	*
	* @param string $url string URL to set.
	*
	* @return void
	*/
	public function setUrl( string $url ): void
	{
		$this->url = trailingslashit( $url );
	}
	/**
	* Setter for the path property.
	*
	* @param string $path string path to set.
	*
	* @return void
	*/
	public function setPath( string $path ): void
	{
		$this->path = trailingslashit( $path );
	}
	/**
	 * Register all WordPress hooks for the responsive grid extension.
	 *
	 * @return void
	 */
	public function mount(): void
	{
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueueEditorScript' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueFrontendStyle' ] );
		add_filter( 'render_block_core/group', [ $this, 'processGridBlock' ], 10, 2 );
	}
	/**
	 * Enqueue editor assets for the block extension.
	 *
	 * This method enqueues:
	 * - build/index.js (editor behavior)
	 * - build/index.css (editor-specific styles)
	 * - build/frontend.css (preview parity with frontend styles)
	 *
	 * Script dependencies/version are resolved from getScriptAssets().
	 *
	 * @return void
	 */
	public function enqueueEditorScript(): void
	{
		$script_file = $this->buildPath( 'index.js' );

		if ( ! is_file( $script_file ) ) {
			return;
		}

		$assets  = $this->getScriptAssets();
		$version = $assets['version'] ?? (string) filemtime( $script_file );
		$src     = $this->buildUrl( 'index.js' );

		if ( empty( $src ) ) {
			return;
		}

		wp_enqueue_script(
			'grid-extensions-editor',
			$src,
			$assets['dependencies'],
			$version,
			true
		);

		$this->enqueueStyleFile( 'grid-extensions-editor', 'index.css' );
		$this->enqueueStyleFile( 'grid-extensions-frontend-editor', 'frontend.css' );
	}

	/**
	 * Enqueue frontend stylesheet for responsive grid rendering.
	 *
	 * @return void
	 */
	public function enqueueFrontendStyle(): void
	{
		$this->enqueueStyleFile( 'grid-extensions-frontend', 'frontend.css' );
	}

	/**
	 * Enqueue a stylesheet from the build directory if it exists.
	 *
	 * @param string $handle        Style handle.
	 * @param string $relative_path Relative file path inside build.
	 *
	 * @return void
	 */
	protected function enqueueStyleFile( string $handle, string $relative_path ): void
	{
		$style_file = $this->buildPath( $relative_path );

		if ( ! is_file( $style_file ) ) {
			return;
		}

		$assets = $this->getScriptAssets();
		// Keep style versions in sync with script build versions when available.
		$version = $assets['version'] ?? (string) filemtime( $style_file );
		$src     = $this->buildUrl( $relative_path );

		if ( empty( $src ) ) {
			return;
		}

		wp_enqueue_style(
			$handle,
			$src,
			[],
			$version
		);
	}

	/**
	 * Build an absolute path inside the package build directory.
	 *
	 * @param string $relative_path Relative file path inside build.
	 *
	 * @return string
	 */
	protected function buildPath( string $relative_path ): string
	{
		$path = apply_filters( 'responsive_grid_extension_plugin_path', $this->path );
		
		if ( '' === $path ) {
			return '';
		}

		return wp_normalize_path( $path . 'build/' . ltrim( $relative_path, '/' ) );
	}

	/**
	 * Resolve a build file path into a public URL.
	 *
	 * @param string $relative_path Relative file path inside build.
	 *
	 * @return string
	 */
	protected function buildUrl( string $relative_path ): string
	{
		$url = apply_filters( 'responsive_grid_extension_plugin_url', $this->url );
		
		if ( '' === $url ) {
			return '';
		}

		return $url . 'build/' . ltrim( $relative_path, '/' );
	}

	/**
	 * Resolve script dependency metadata from WordPress build asset files.
	 *
	 * Supports both index.asset.php (default wp-scripts output) and
	 * index.assets.php (alternate naming used in some build setups).
	 *
	 * @return array{dependencies: array<int, string>, version: string|null}
	 */
	protected function getScriptAssets(): array
	{
		$asset_candidates = [
			$this->buildPath( 'index.asset.php' ),
			$this->buildPath( 'index.assets.php' ),
		];

		foreach ( $asset_candidates as $asset_file ) {
			if ( ! is_file( $asset_file ) ) {
				continue;
			}

			$asset = include $asset_file;

			if ( ! is_array( $asset ) ) {
				continue;
			}

			$dependencies = $asset['dependencies'] ?? [];
			$version      = $asset['version'] ?? null;

			return [
				'dependencies' => is_array( $dependencies ) ? $dependencies : [],
				'version'      => is_string( $version ) ? $version : null,
			];
		}

		return [
			'dependencies' => [],
			'version'      => null,
		];
	}

	/**
	 * Inject responsive grid classes and CSS variables into rendered core/group blocks.
	 *
	 * This method reads responsiveGridColumns and responsiveGridRows attributes,
	 * then applies corresponding classes and CSS custom properties directly to the
	 * outer group wrapper in rendered HTML.
	 *
	 * Expected CSS variable pattern:
	 * - --responsive-grid-template-columns--{breakpoint}
	 * - --responsive-grid-template-rows--{breakpoint}
	 *
	 * @param string               $block_content HTML output of the block.
	 * @param array<string, mixed> $block         Parsed block.
	 *
	 * @return string
	 */
	public function processGridBlock( string $block_content, array $block ): string
	{
		// Only process Group blocks using the Grid layout mode.
		if ( 'grid' !== ( $block['attrs']['layout']['type'] ?? false ) ) {
			return $block_content;
		}

		/**
		 * Merge known breakpoint keys and strip empty values.
		 */
		$columns = array_filter(
			array_merge(
				[
					'desktop' => '',
					'tablet'  => '',
					'mobile'  => '',
				],
				$block['attrs']['responsiveGridColumns'] ?? []
			)
		);

		$rows = array_filter(
			array_merge(
				[
					'desktop' => '',
					'tablet'  => '',
					'mobile'  => '',
				],
				$block['attrs']['responsiveGridRows'] ?? []
			)
		);

		if ( empty( $columns ) && empty( $rows ) ) {
			return $block_content;
		}

		$styles      = [];
		$class_names = [];
		/**
		* Build custom column classes and styles.
		*/
		if ( ! empty( $columns ) ) {
			$class_names[] = 'has-responsive-grid-columns';

			foreach ( $columns as $size => $value ) {
				$class_names[] = sprintf(
					'has-responsive-grid-columns--%s',
					sanitize_key( $size )
				);

				$styles[ sprintf(
					'--responsive-grid-template-columns--%s',
					sanitize_key( $size )
				) ] = $value;
			}
		}
		/**
		* Build custom row classes and styles.
		*/
		if ( ! empty( $rows ) ) {
			$class_names[] = 'has-responsive-grid-rows';

			foreach ( $rows as $size => $value ) {
				$class_names[] = sprintf(
					'has-responsive-grid-rows--%s',
					sanitize_key( $size )
				);

				$styles[ sprintf(
					'--responsive-grid-template-rows--%s',
					sanitize_key( $size )
				) ] = $value;
			}
		}
		/**
		* Process the rendered markup and update the first group wrapper element.
		*/
		$processor = new \WP_HTML_Tag_Processor( $block_content );

		if ( true === $processor->next_tag( [ 'class_name' => 'wp-block-group' ] ) ) {
			$inline_styles    = $processor->get_attribute( 'style' ) ?? '';
			$existing_classes = $processor->get_attribute( 'class' ) ?? '';

			$inline_styles .= str_ends_with( $inline_styles, ';' ) ? '' : ';';

			foreach ( $styles as $key => $value ) {
				$inline_styles .= "{$key}: {$value}; ";
			}
			$all_classes = array_unique(
				array_merge(
					explode( ' ', $existing_classes ),
					$class_names
				)
			);
			$processor->set_attribute( 'style', trim( $inline_styles ) );
			$processor->set_attribute( 'class', trim( implode( ' ', $all_classes ) ) );

			$block_content = $processor->get_updated_html();
		}

		return $block_content;
	}
}
