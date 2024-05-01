<?php
/**
 * Plugin Name: AV Link Manager
 * Plugin URI:  https://github.com/Relivian/av-linkman
 * Description: Plugin that requests user confirmation before leaving the site.
 * Version:     1.0
 * Author:      Relivian
 * Author URI:  https://dev.aeonsvirtue.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include the settings class
include_once plugin_dir_path(__FILE__) . 'AV_Link_Manager_Settings.php';

class AV_Link_Manager {
    public function __construct() {
        add_action('init', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function load_textdomain() {
        load_plugin_textdomain('av-linkman', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public static function get_default_settings() {
        return array(
            'av_link_manager_text' => 'You are now leaving the site ${SITE_TITLE} and navigate to ${DESTINATION_URL}',
            'av_link_manager_continue_text' => 'Continue',
            'av_link_manager_cancel_text' => 'Cancel',
            'new_tab' => 'no'
        );
    }

    public static function activate() {
        $default_settings = self::get_default_settings();
        $existing_options = get_option('av_link_manager_settings', []);
        // ensure each key in default settings is present, do not overwrite existing
        foreach ($default_settings as $key => $value) {
            if (!isset($existing_options[$key])) {
                $existing_options[$key] = $value;
            }
        }
        update_option('av_link_manager_settings', $existing_options);
    }    
    
    public function enqueue_scripts() {
        if (is_single() || is_page()) { // Adjust these conditions as necessary
            $options = get_option('av_link_manager_settings');
            $current_lang = $this->get_current_language();

            // create language-specific keys for popup text and button labels
            $popupTextKey = "av_link_manager_text" . ($current_lang ? "_{$current_lang}" : '');
            $continueTextKey = "av_link_manager_continue_text" . ($current_lang ? "_{$current_lang}" : '');
            $cancelTextKey = "av_link_manager_cancel_text" . ($current_lang ? "_{$current_lang}" : '');

            // retrieve the localized texts or use the default settings if not available
            $popupText = isset($options[$popupTextKey]) ? $options[$popupTextKey] : $options['av_link_manager_text'];
            $continueText = isset($options[$continueTextKey]) ? $options[$continueTextKey] : $options['av_link_manager_continue_text'];
            $cancelText = isset($options[$cancelTextKey]) ? $options[$cancelTextKey] : $options['av_link_manager_cancel_text'];

            // replace ${SITE_TITLE} with the actual site title
            $resolvedText = str_replace('${SITE_TITLE}', get_bloginfo('name'), $popupText);

            // setup script data to localize the script properly
            $script_data = array(
                'newTab' => isset($options['new_tab']) && $options['new_tab'] === 'yes',
                'popupText' => $resolvedText,
                'continueText' => $continueText,
                'cancelText' => $cancelText
            );

            wp_enqueue_script('av-linkman-js', plugins_url('assets/js/linkman.js', __FILE__), array('jquery'), '1.0', true);
             wp_localize_script('av-linkman-js', 'avLinkman', $script_data);
            $version = filemtime(plugin_dir_path(__FILE__) . 'assets/css/linkman.css'); // using time will invalidate browser cache
            wp_enqueue_style('av-linkman-css', plugins_url('assets/css/linkman.css', __FILE__), array(), $version);
        }
    }

    private function get_current_language() {
        // get the default WordPress locale language part only, if specific language plugins are not installed
        $langCode = substr(get_locale(), 0, 2); 
        if (function_exists('icl_object_id')) {
            $langCode = ICL_LANGUAGE_CODE;
        } elseif (function_exists('pll_current_language')) {
            $langCode = pll_current_language();
        }
        return $langCode;
     }
} 

new AV_Link_Manager();

