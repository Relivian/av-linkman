<?php

class WP_Link_Manager_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
    }
    
    public function add_admin_menu() {
        add_options_page(__('WP Link Manager', 'wp-linkman'), __('WP Link Manager', 'wp-linkman'), 'manage_options', 'wp_link_manager', array($this, 'options_page'));
    }    

//    public function add_admin_menu() {
//        add_options_page('WP Link Manager', 'WP Link Manager', 'manage_options', 'wp_link_manager', array($this, 'options_page'));
//    }

    public function settings_init() {
        register_setting('wpLinkManager', 'wp_link_manager_settings');

        add_settings_section(
            'wp_link_manager_wpLinkManager_section', 
            __('Configure your settings below:', 'wp-linkman'), 
            array($this, 'settings_section_callback'), 
            'wpLinkManager'
        );

        add_settings_field(
            'wp_link_manager_text', 
            __('Popup Text', 'wp-linkman'), 
            array($this, 'settings_text_render'), 
            'wpLinkManager', 
            'wp_link_manager_wpLinkManager_section'
        );
    }

    public function settings_text_render() {
        $options = get_option('wp_link_manager_settings');
        $text = isset($options['wp_link_manager_text']) ? $options['wp_link_manager_text'] : '';
        ?>
        <textarea name='wp_link_manager_settings[wp_link_manager_text]' rows='5' style='width: 100%;'><?php echo esc_textarea($text); ?></textarea>
        <?php
    }

    public function settings_section_callback() {
        echo __('Set the popup text and other configurations.', 'wp-linkman');
    }

    public function options_page() {
        ?>
        <form action='options.php' method='post'>
            <h2>WP Link Manager</h2>
            <?php
            settings_fields('wpLinkManager');
            do_settings_sections('wpLinkManager');
            submit_button();
            ?>
        </form>
        <?php
    }
}

new WP_Link_Manager_Settings();
