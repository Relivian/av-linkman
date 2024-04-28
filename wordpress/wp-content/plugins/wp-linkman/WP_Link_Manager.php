<?php
/**
 * Plugin Name: WP Link Manager
 * Plugin URI:  https://github.com/Relivian/wp-linkman
 * Description: Plugin that requests user confirmation when leaving the site.
 * Version:     1.0
 * Author:      Relivian
 * Author URI:  https://dev.aeonsvirtue.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the settings class
include_once plugin_dir_path(__FILE__) . 'WP_Link_Manager_Settings.php';

class WP_Link_Manager {
    public function __construct() {
        add_action('init', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function load_textdomain() {
        load_plugin_textdomain('wp-linkman', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public static function activate() {
        $default_settings = array(
            'wp_link_manager_text' => 'You are now leaving the site ${SITE_TITLE} and navigate to ${DESTINATION_URL}',
            'new_tab' => 'no'  // 'no' doesn't need translation
        );
        update_option('wp_link_manager_settings', $default_settings);
    }
    
    public function enqueue_scripts() {
        // Enqueue the JavaScript file
        wp_enqueue_script('wp-linkman-js', plugins_url('assets/js/linkman.js', __FILE__), array('jquery'), '1.0', true);

        // Attach localization data to the script
        $options = get_option('wp_link_manager_settings');
        $popupText = isset($options['wp_link_manager_text']) ? $options['wp_link_manager_text'] : '';
        // Substitute the placeholder for SITE_TITLE with actual site title in the popup text
        $popupText = str_replace('${SITE_TITLE}', get_bloginfo('name'), $popupText);

        $script_data = array(
            'newTab' => isset($options['new_tab']) && $options['new_tab'] == 'yes' ? true : false,
            'popupText' => $popupText
        );

        wp_localize_script('wp-linkman-js', 'wpLinkman', $script_data);

        // Set translations for the script (make sure the .json translation files are correctly generated and located)
        wp_set_script_translations('wp-linkman-js', 'wp-linkman', plugin_dir_path(__FILE__) . 'languages/');

        // Enqueue the CSS file
        wp_enqueue_style('wp-linkman-css', plugins_url('assets/css/linkman.css', __FILE__));
    }   
}

new WP_Link_Manager();
