# arsol-wp-snippets-packet-example

## Overview
The **Arsol WP Snippets Packet Example** is a WordPress plugin designed to provide a collection of useful snippets and functionalities that enhance the WordPress experience. This plugin includes custom widgets, helper functions, and various assets to improve the user interface and performance.

## Installation
1. Download the plugin files.
2. Upload the `arsol-wp-snippets-packet-example` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Customization

### Plugin Configuration
To customize this plugin for your own use, you'll need to update the following elements:

#### 1. Folder Name
- **Current**: `arsol-wp-snippets-packet-example`
- **Action**: Rename the main plugin folder to match your desired plugin name
- **Location**: Root directory name

#### 2. Main Plugin File
- **Current**: `arsol-wp-snippets-packet-example.php`
- **Action**: Rename the main plugin file to match your folder name
- **Convention**: Should match the folder name with `.php` extension

#### 3. Plugin Header Information
Update the plugin header comment block in the main PHP file:

```php
<?php
/*
Plugin Name: Arsol WP Snippet Packet: Your Plugin Name Here
Requires Plugins: arsol-wp-snippets
Plugin URI: https://yourwebsite.com/your-plugin
Description: Your plugin description here.
Version: 1.0.0
Author: Your Name
Author URI: https://yourwebsite.com
License: GPL2
Text Domain: your-plugin-textdomain
Domain Path: /languages
*/
```

**Lines to update:**
- `Plugin Name`: Change to follow the format "Arsol WP Snippet Packet: Your Plugin Name Here" (this ensures all snippet packets appear grouped together in the plugins list)
- `Requires Plugins`: **Keep as `arsol-wp-snippets`** - this should NOT be changed as all snippet packets require the base plugin
- `Plugin URI`: Update to your plugin's homepage or repository URL
- `Description`: Write a description specific to your plugin's functionality
- `Version`: Set your initial version number
- `Author`: Replace with your name or organization
- `Author URI`: Update to your website or profile URL
- `Text Domain`: Change to a unique identifier for your plugin (used for translations)

#### 4. Additional Considerations
- Update any references to the old plugin name in code comments
- Modify CSS class prefixes if they include the plugin name
- Update function prefixes to avoid conflicts with other plugins
- Change any constants or configuration variables that reference the plugin name

## Usage
Once activated, the plugin will automatically register its files. You can find the registered scripts in the Arsol WP Snippets dashboard in the WordPress admin. To enable a snippet you must enable the checkbox and save. For debugging enable WordPress debug mode and all errors will appear in the error log with all the relevant file paths.

**Important**: The user must first place their snippets in the plugin snippets directory then add a filter function in the relevant functions file depending on the snippet type. For example, a CSS snippet must first be added to the `snippets/css/` folder and its filter function added to the `includes/functions/functions-css-snippets-loader.php` file.

### Supported Snippet Types
This plugin supports three types of snippets that are automatically detected and registered:

#### 1. CSS Snippets
- **Location**: `snippets/css/`
- **File format**: `.css` files
- **Filter**: `arsol_wp_snippets_css_addon_files`
- **Loading**: Automatically enqueued in the frontend when enabled
- **Options**: 
  - `name`: Display name for the snippet
  - `file`: URL path to the CSS file (e.g., `plugin_dir_url(__FILE__) . '../snippets/css/example.css'`)
  - `context`: 'frontend', 'admin', or 'both'
  - `position`: 'header' or 'footer' (defaults to 'header')
- **Example included**: `example.css`

#### 2. JavaScript Snippets  
- **Location**: `snippets/js/`
- **File format**: `.js` files
- **Filter**: `arsol_wp_snippets_js_addon_files`
- **Loading**: Automatically enqueued in the frontend when enabled
- **Options**:
  - `name`: Display name for the snippet
  - `file`: URL path to the JS file (e.g., `plugin_dir_url(__FILE__) . '../snippets/js/example.js'`)
  - `context`: 'frontend', 'admin', or 'both'
  - `position`: 'header' or 'footer' (defaults to 'footer')
- **Example included**: `example.js`

#### 3. PHP Snippets
- **Location**: `snippets/php/`
- **File format**: `.php` files
- **Filter**: `arsol_wp_snippets_php_addon_files`
- **Loading**: Automatically included when enabled
- **Options**:
  - `name`: Display name for the snippet
  - `file`: File system path to the PHP file (e.g., `__DIR__ . '/../snippets/php/example.php'`)
- **Example included**: `example.php`

### Managing Snippets
1. Navigate to **Arsol WP Snippets** in your WordPress admin dashboard
2. Find your packet's snippets listed by filename
3. Enable/disable individual snippets using the checkboxes
4. Click **Save** to apply changes
5. Snippets will be automatically loaded on the frontend when enabled

### Example Files
This plugin contains example snippets in each category to demonstrate proper file structure and naming conventions. These examples can be used as templates for creating your own custom snippets.

### Features
- **Custom Widgets**: Easily add custom widgets to your site.
- **Helper Functions**: Utilize various utility functions to streamline your development process.
- **Security Tweaks**: Implement best practices for securing your WordPress site.
- **Responsive Design**: Includes a grid system and modern button styles for a better user interface.
- **Performance Enhancements**: Features like lazy loading and smooth scrolling to improve site performance.

## Contributing
Contributions are welcome! Please feel free to submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.