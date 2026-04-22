# Responsive Grid Extension

Responsive Grid Extension adds responsive grid column and row controls to the core Group block for the block editor.

## Overview

This package is built for WordPress projects that need per-breakpoint grid template control without replacing the native Group block workflow. It adds editor controls for responsive grid columns and rows, then applies matching classes and CSS custom properties on render.

## Features

- Extends the core Group block instead of introducing a custom block.
- Adds responsive grid column controls for desktop, tablet, and mobile.
- Adds responsive grid row controls for desktop, tablet, and mobile.
- Loads editor and frontend assets from the package build directory.
- Works as a standard plugin or as a Composer-installed package included by another plugin or theme.

## Requirements

- WordPress 6.7 or later
- PHP 8.2 or later
- Node.js 18.12 or later for local development

## Installation

### As a WordPress plugin

1. Download the latest installable ZIP from the [GitHub Releases page](https://github.com/bob-moore/Responsive-Grid-Extension/releases).
2. In WordPress admin, go to **Plugins > Add New Plugin > Upload Plugin**.
3. Upload the ZIP and activate **Responsive Grid Extension**.

### As a Composer dependency

1. Require the package from your consuming plugin or theme:

```bash
composer require bmd/responsive-grid-extension
```

2. Instantiate the plugin class and register its hooks in your bootstrap code:

```php
<?php

use Bmd\ResponsiveGridExtension;

$plugin = new ResponsiveGridExtension();

$plugin->mount();
```

3. Ensure Composer autoloading is active in the consuming plugin or theme.
4. Keep the package in a WordPress-accessible location so built assets can be loaded.

## Usage

1. Insert a core Group block.
2. Set the Group block layout type to `grid`.
3. Open the block inspector.
4. Enter custom grid template values for columns and rows per device.

Example values:

- Columns: `repeat(3, 1fr)`
- Columns: `2fr 1fr`
- Rows: `auto auto`
- Rows: `minmax(120px, auto) 1fr`

## Development

Install dependencies:

```bash
npm install
```

Start development build:

```bash
npm run start
```

Create production assets:

```bash
npm run build
```

## Changelog

### 0.1.2

- Added `mount()` method to `NavBlockEnhancements` that registers all WordPress hooks in one call (`enqueue_block_assets` and `render_block_core/navigation`).
- Simplified plugin bootstrap: replaced individual `add_action`/`add_filter` calls with `$plugin->mount()`.
- When using the library via Composer, call `$plugin->mount()` after instantiation instead of wiring hooks manually.

### 0.1.1

- Moved main class into `inc/` directory for Composer PSR-4 autoloading under `Bmd\`.
- Public class is now `Bmd\ResponsiveGridExtension` (previously `Bmd\ResponsiveGridExtension\Plugin`).
- Fixed asset path resolution after directory restructure.

### 0.1.0

- Initial release.
- Added responsive Group block grid extensions for columns and rows.

## License

GPL-2.0-or-later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html).