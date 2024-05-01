<?php

class AV_Link_Manager_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
    }
    
    public function add_admin_menu() {
        add_options_page(__('AV Link Manager', 'av-linkman'), __('AV Link Manager', 'av-linkman'), 'manage_options', 'av_link_manager', array($this, 'options_page'));
    }    

    public function settings_section_callback() {
        echo esc_html(__('Set the popup text and other configurations.', 'av-linkman'));
    }

    public function options_page() {
        ?>
        <form action='options.php' method='post'>
            <h2>AV Link Manager</h2>
            <?php
            settings_fields('avLinkManager');
            do_settings_sections('avLinkManager');
            submit_button();
            ?>
        </form>
        <?php
    }
    
    public function new_tab_field_render() {
        $options = get_option('av_link_manager_settings');
        $new_tab = isset($options['new_tab']) ? $options['new_tab'] : '';
        echo '<input type="checkbox" name="av_link_manager_settings[new_tab]" ' . esc_attr(checked($new_tab, 'yes', false)) . ' value="yes"> ' . esc_html(__('Yes', 'av-linkman'));
    }
    
    public function settings_init() {
        register_setting('avLinkManager', 'av_link_manager_settings');

        add_settings_section(
            'av_link_manager_avLinkManager_section', 
            __('Configure your settings below:', 'av-linkman'), 
            array($this, 'settings_section_callback'), 
            'avLinkManager'
        );

        add_settings_field(
            'new_tab', 
            __('Open links in new tab', 'av-linkman'), 
            array($this, 'new_tab_field_render'), 
            'avLinkManager', 
            'av_link_manager_avLinkManager_section'
        );    

        $this->add_language_fields();    
    }
    
    private function add_language_fields() {
        $languages = $this->get_active_languages();
        foreach ($languages as $lang => $label) {
            // Popup Text Field
            // translators: %s is the language label used for popup text.
            $popupText = sprintf(__("Popup Text (%s)", 'av-linkman'), $label);
            add_settings_field(
                "av_link_manager_text_{$lang}", 
                $popupText, 
                function() use ($lang) {
                    $this->settings_text_render('av_link_manager_text', $lang);
                }, 
                'avLinkManager', 
                'av_link_manager_avLinkManager_section'
            );

            // Continue Button Text Field
            // translators: %s is the language label used for continue button text.
            $continueButtonText = sprintf(__("Continue Button Text (%s)", 'av-linkman'), $label);
            add_settings_field(
                "av_link_manager_continue_text_{$lang}",
                $continueButtonText,
                function() use ($lang) {
                    $this->settings_text_render('av_link_manager_continue_text', $lang);
                },
                'avLinkManager',
                'av_link_manager_avLinkManager_section'
            );

            // Cancel Button Text Field
            // translators: %s is the language label used for cancel button text.
            $cancelButtonText = sprintf(__("Cancel Button Text (%s)", 'av-linkman'), $label);
            add_settings_field(
                "av_link_manager_cancel_text_{$lang}",
                $cancelButtonText,
                function() use ($lang) {
                    $this->settings_text_render('av_link_manager_cancel_text', $lang);
                },
                'avLinkManager',
                'av_link_manager_avLinkManager_section'
            );
        }
    }
    
    public function settings_text_render($option_name, $lang = '') {
        $options = get_option('av_link_manager_settings');
        $defaults = AV_Link_Manager::get_default_settings();

        // define the key for fetching stored settings or default settings
        $key = $lang ? "{$option_name}_{$lang}" : $option_name;
        $default_text = isset($defaults[$option_name]) ? $defaults[$option_name] : '';

        // get the current setting or use default if not set
        $text = isset($options[$key]) && !empty($options[$key]) ? $options[$key] : $default_text;

        // Determine if this is the popup text or a button label
        if ($option_name == 'av_link_manager_text') {
            // textarea for popup text
            echo "<textarea name='av_link_manager_settings[" . esc_attr($key) . "]' rows='2' style='width: 90%;'>" . esc_textarea($text) . "</textarea>";
        } else {
            // input for button labels
            echo "<input type='text' name='av_link_manager_settings[" . esc_attr($key) . "]' value='" . esc_attr($text) . "'>";
        }
    }

    private function get_active_languages() {
        if (function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=0');
            return array_column($languages, 'native_name', 'language_code');
        } elseif (function_exists('pll_languages_list')) {
            $languages = pll_languages_list(array('fields' => array('slug', 'name')));
            return array_combine(array_column($languages, 'slug'), array_column($languages, 'name'));
        }
        return array('' => 'Default');
    }
}

new AV_Link_Manager_Settings();
