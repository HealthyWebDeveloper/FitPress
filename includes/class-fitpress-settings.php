<?php

/**
 * Class for FitPress Settings
 *
 * @since 1.2.1
 *
 * @package FitPress
 * @author http://www.yaconiello.com/blog/how-to-handle-wordpress-settings/
 */

if(!class_exists('FitPress_Settings'))
{
    class FitPress_Settings
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
        } // END public function __construct
    
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            // register your plugin's settings
            register_setting('fitpress_settings-group', 'fitpress_consumer_key');
            register_setting('fitpress_settings-group', 'fitpress_consumer_secret');
            // register_setting('fitpress_settings-group', 'fitpress_fitbit_token');
            // register_setting('fitpress_settings-group', 'fitpress_fitbit_secret');

            // add your settings section
            add_settings_section(
                'fitpress_settings-section', 
                __( 'FitPress Settings', 'fitpress' ), 
                array(&$this, 'settings_section_fitpress_settings'), 
                'fitpress_settings'
            );
        
            // add your setting's fields
            add_settings_field(
                'fitpress_settings-consumer_key', 
                __( 'Consumer Key', 'fitpress' ), 
                array(&$this, 'settings_field_input_text'), 
                'fitpress_settings', 
                'fitpress_settings-section',
                array(
                    'field' => 'fitpress_consumer_key'
                )
            );
            add_settings_field(
                'fitpress_settings-consumer_secret', 
                __( 'Consumer Secret', 'fitpress' ), 
                array(&$this, 'settings_field_input_text'), 
                'fitpress_settings', 
                'fitpress_settings-section',
                array(
                    'field' => 'fitpress_consumer_secret'
                )
            );
             // add your setting's fields
            // add_settings_field(
            //     'fitpress_settings-fitbit_token', 
            //     __( 'Fitbit Token', 'fitpress' ), 
            //     array(&$this, 'settings_field_input_text'), 
            //     'fitpress_settings', 
            //     'fitpress_settings-section',
            //     array(
            //         'field' => 'fitpress_fitbit_token'
            //     )
            // );
            // add_settings_field(
            //     'fitpress_settings-fitbit_secret', 
            //     __( 'Fitbit Secret', 'fitpress' ), 
            //     array(&$this, 'settings_field_input_text'), 
            //     'fitpress_settings', 
            //     'fitpress_settings-section',
            //     array(
            //         'field' => 'fitpress_fitbit_secret'
            //     )
            // );
            // Possibly do additional admin_init tasks
        } // END public static function activate
    
        public function settings_section_fitpress_settings()
        {
            // Think of this as help text for the section.
            _e('These settings do things for the FitPress friend plugin.', 'fitpress');
        }
    
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
    
        /**
         * add a menu
         */     
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
            // add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
            add_options_page(
                'FitPress Settings', 
                'FitPress Settings', 
                'manage_options', 
                'fitpress_settings', 
                array(&$this, 'plugin_settings_page')
            );

            // http://www.billrobbinsdesign.com/custom-post-type-admin-page/
            // add_submenu_page(
            // 	'edit.php?post_type=friend', 
            // 	__('Friend Admin', 'fitpress'), 
            // 	__('Friend Settings', 'fitpress'), 
            // 	'edit_posts', 
            // 	basename(__FILE__), 
            // 	array(&$this, 'plugin_settings_page')
            // 	);

        } // END public function add_menu()

        /**
         * Menu Callback
         */     
        public function plugin_settings_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()


    } // END class FitPress_Settings

    $fitpress_settings = new FitPress_Settings();    
} // END if(!class_exists('FitPress_Settings'))
