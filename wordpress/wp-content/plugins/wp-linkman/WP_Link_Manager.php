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

    public static function get_default_settings() {
        return array(
            'wp_link_manager_text' => 'You are now leaving the site ${SITE_TITLE} and navigate to ${DESTINATION_URL}',
            'wp_link_manager_continue_text' => 'Continue',
            'wp_link_manager_cancel_text' => 'Cancel',
            'new_tab' => 'no'
        );
    }

    public static function activate() {
        $default_settings = self::get_default_settings();
        $existing_options = get_option('wp_link_manager_settings', []);
        // Ensure each key in default settings is present, do not overwrite existing
        foreach ($default_settings as $key => $value) {
            if (!isset($existing_options[$key])) {
                $existing_options[$key] = $value;
            }
        }
        update_option('wp_link_manager_settings', $existing_options);
    }    
    
     public function enqueue_scripts() {
        $options = get_option('wp_link_manager_settings');
        $current_lang = $this->get_current_language();

        // Create language-specific keys for popup text and button labels
        $popupTextKey = "wp_link_manager_text" . ($current_lang ? "_{$current_lang}" : '');
        $continueTextKey = "wp_link_manager_continue_text" . ($current_lang ? "_{$current_lang}" : '');
        $cancelTextKey = "wp_link_manager_cancel_text" . ($current_lang ? "_{$current_lang}" : '');

        // Retrieve the localized texts or use the default settings if not available
        $popupText = isset($options[$popupTextKey]) ? $options[$popupTextKey] : $options['wp_link_manager_text'];
        $continueText = isset($options[$continueTextKey]) ? $options[$continueTextKey] : $options['wp_link_manager_continue_text'];
        $cancelText = isset($options[$cancelTextKey]) ? $options[$cancelTextKey] : $options['wp_link_manager_cancel_text'];

        // Replace ${SITE_TITLE} with the actual site title
        $popupText = str_replace('${SITE_TITLE}', get_bloginfo('name'), $popupText);
        
        // Setup script data to localize the script properly
        $script_data = array(
            'newTab' => isset($options['new_tab']) && $options['new_tab'] === 'yes',
            'popupText' => $popupText,
            'continueText' => $continueText,
            'cancelText' => $cancelText
        );

        wp_enqueue_script('wp-linkman-js', plugins_url('assets/js/linkman.js', __FILE__), array('jquery'), '1.0', true);
        wp_localize_script('wp-linkman-js', 'wpLinkman', $script_data);
        wp_enqueue_style('wp-linkman-css', plugins_url('assets/css/linkman.css', __FILE__));
    }
   
   
    
    private function get_current_language() {
        if (function_exists('icl_object_id')) {
            return ICL_LANGUAGE_CODE;
        } elseif (function_exists('pll_current_language')) {
            return pll_current_language();
        }
        return '';
    }
}

new WP_Link_Manager();
