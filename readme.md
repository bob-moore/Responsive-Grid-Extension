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

1. Copy this package into your WordPress `wp-content/plugins/` directory.
2. Install dependencies with `npm install` if you need to rebuild assets.
3. Build assets with `npm run build`.
4. Activate **Responsive Grid Extension** in the WordPress admin.

### As a Composer dependency

1. Require the package in the consuming project.
2. Ensure the package is installed inside a web-accessible WordPress path.
3. Make sure `vendor/autoload.php` is available for the package bootstrap file.
4. Load `responsive-grid-extensions.php` from the consuming plugin or theme.

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

### 0.1.0

- Initial release.
- Added responsive Group block grid extensions for columns and rows.

## License

GPL-2.0-or-later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html).