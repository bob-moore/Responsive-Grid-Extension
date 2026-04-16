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
 * - $plugin = new ResponsiveGridExtension();
 * - add_action( 'enqueue_block_editor_assets', [ $plugin, 'enqueueEditorScript' ] );
 * - add_action( 'wp_enqueue_scripts', [ $plugin, 'enqueueFrontendStyle' ] );
 * - add_filter( 'render_block_core/group', [ $plugin, 'processGridBlock' ], 10, 2 );
 */
class ResponsiveGridExtension
{
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

        $assets = $this->getScriptAssets();
        $version = $assets['version'] ?? (string) filemtime( $script_file );
        $src = $this->buildUrl( 'index.js' );

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
    * @param string $handle Style handle.
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
        $src = $this->buildUrl( $relative_path );

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
        return wp_normalize_path(
            dirname( __DIR__ ) . '/build/' . ltrim( $relative_path, '/' )
        );
    }

    /**
    * Resolve a build file path into a public URL.
    *
    * This supports packages loaded from plugins, themes, or vendor directories
    * as long as the file is under ABSPATH.
    *
    * @param string $relative_path Relative file path inside build.
    *
    * @return string
    */
    protected function buildUrl( string $relative_path ): string
    {
        $absolute_path = $this->buildPath( $relative_path );
        $resolved_path = realpath( $absolute_path );

        if ( false !== $resolved_path ) {
            $absolute_path = wp_normalize_path( $resolved_path );
        }

        $content_dir = wp_normalize_path( WP_CONTENT_DIR );

        // Preferred path: anything in wp-content maps cleanly via content_url().
        if ( str_starts_with( $absolute_path, $content_dir ) ) {
            $relative = ltrim(
                substr( $absolute_path, strlen( $content_dir ) ),
                '/'
            );

            return content_url( $relative );
        }

        $root_dir = wp_normalize_path( ABSPATH );

        // Fallback path: anything under ABSPATH can still resolve with site_url().
        if ( str_starts_with( $absolute_path, $root_dir ) ) {
            $relative = ltrim(
                substr( $absolute_path, strlen( $root_dir ) ),
                '/'
            );

            return site_url( $relative );
        }

        return '';
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
            $version = $asset['version'] ?? null;

            return [
                'dependencies' => is_array( $dependencies ) ? $dependencies : [],
                'version' => is_string( $version ) ? $version : null,
            ];
        }

        return [
            'dependencies' => [],
            'version' => null,
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
    * @param string               $block_content : html output of the block.
    * @param array<string, mixed> $block : parsed block.
    *
    * @return string
    */
    function processGridBlock( string $block_content, array $block ): string
    {

        // Only process Group blocks using the Grid layout mode.
        if ( 'grid' !== ( $block['attrs']['layout']['type'] ?? false ) ) {
            return $block_content;
        }

        /**
         * Merge known breakpoint keys and strip empty values.
         */
        $columns = array_filter( array_merge(
            [
                'desktop' => '',
                'tablet'  => '',
                'mobile'  => '',
            ],
            $block['attrs']['responsiveGridColumns'] ?? []
        ) );

        $rows = array_filter( array_merge(
            [
                'desktop' => '',
                'tablet'  => '',
                'mobile'  => '',
            ],
            $block['attrs']['responsiveGridRows'] ?? []
        ) );
        
        /**
         * Do not bother continuing to process if no responsive attributes
         * are present
         */
        if ( empty( $columns ) && empty( $rows ) ) {
            return $block_content;
        }

        $styles     = [];
        $classNames = [];
        /**
        * Build custom column classes and styles.
        */
        if ( ! empty( $columns ) ) {
            $classNames[] = 'has-responsive-grid-columns';

            foreach ( $columns as $size => $value ) {

                $classNames[] = sprintf(
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
            $classNames[] = 'has-responsive-grid-rows';

            foreach ( $rows as $size => $value ) {

                $classNames[] = sprintf(
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

        if ( true === $processor->next_tag( ['class_name' => 'wp-block-group'] ) ) {
            $inline_styles = $processor->get_attribute( 'style' ) ?? '';
            $existing_classes = $processor->get_attribute( 'class' ) ?? '';

            $inline_styles .= str_ends_with( $inline_styles, ';' ) ? '' : ';';

            foreach ( $styles as $key => $value ) {
                $inline_styles .= "{$key}: {$value}; ";
            }
            $all_classes = array_unique(
                array_merge(
                    explode( ' ', $existing_classes ),
                    $classNames
                )
            );
            $processor->set_attribute( 'style', trim( $inline_styles ) );
            $processor->set_attribute( 'class', trim( implode( ' ', $all_classes ) ) );

            $block_content = $processor->get_updated_html();
        }

        return $block_content;
    }
}
