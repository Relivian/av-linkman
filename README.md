# Link Manager
Contributors: relivian  
Tags: links, external, popup, navigation, confirmation  
Requires at least: 5.0  
Tested up to: 6.5  
Stable tag: 1.0  
Requires PHP: 7.2  
License: GPLv3 or later  
License URI: https://www.gnu.org/licenses/gpl-3.0.html  

Link Manager is a WordPress plugin that prompts users for confirmation before leaving your site when clicking on an external link.

## Description

Link Manager opens a confirmation dialog when clicking external links, asking users if they wish to continue to the external site or cancel their navigation. This can help prevent users from leaving your website unintentionally and improve site retention. It's especially useful for websites with a lot of outbound links, such as content aggregators, news sites, or blogs.

The plugin supports multiple languages and integrates seamlessly with popular WordPress multilingual plugins like Polylang. When these plugins are detected, WP Link Manager automatically provides settings for configuring pop-up messages and button texts for each language.

## Features

- Simple, user-friendly interface in the WordPress admin.
- Customizable pop-up message text, including dynamic variables:
  - `${SITE_TITLE}`: Automatically replaced with your site's title.
  - `${DESTINATION_URL}`: Automatically replaced with the external link's URL.
- Customizable text for "Continue" and "Cancel" buttons.
- Option to open external links in a new tab, configurable via the plugin settings.
- Full compatibility with WPML and Polylang for multilingual sites.

## Installation

1. Upload `link-manager` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings -> WP Link Manager to configure the plugin.

## Frequently Asked Questions

- Can I customize the confirmation dialog?

Yes, you can customize the text of the confirmation dialog and the labels of the "Continue" and "Cancel" buttons from the plugin's settings page in the WordPress admin area.

- Does this plugin support multiple languages?

Yes, if you are using WPML or Polylang, you can set different texts for each language supported on your site.

- How do I enable links to open in a new tab?

Navigate to the plugin settings page, and you will find an option to configure links to open in a new tab. Check the option if you prefer to have external links open in new tabs.

## Screenshots

1. The main settings page where you can configure the pop-up texts and new tab functionality.
2. Example of the pop-up in action on a live website.

## Changelog

- 1.0
  * Initial release
- 1.1
  * added multilingual support and new tab functionality.

## Upgrade Notice

- 1.0
  * Initial release. Please provide feedback and report any issues you encounter.

