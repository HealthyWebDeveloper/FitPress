<?php
/*
Plugin Name: FitPress
Description: Adds custom post type and fun stuff for Fibit tools on a BuddyPress site, with OAuth 2.0.
Plugin URI: http://healthywebdeveloper.com
Author: Bradford Knowlton
Author URI: http://bradknowlton.com
Version: 2.0.1
License: GPL2
Text Domain: fitpress
Domain Path: /languages

GitHub Plugin URI: https://github.com/HealthyWebDeveloper/FitPress
GitHub Branch: master

Requires PHP: 5.3.0
Requires WP: 4.4
*/

/*

    Copyright (C) 2016  Bradford Knowlton brad@healthywebdeveloper.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Help with setting up gulp
// https://travismaynard.com/writing/getting-started-with-gulp

define( 'FITPRESS_PLUGIN_VERSION', '2.0.1' );  

// possible future global use
define( 'FITPRESS_PLUGIN_DIR', dirname(__FILE__).'/' );  


/** Requiring required class files */

require_once( plugin_dir_path( __FILE__ ) . 'includes/class-fitbit-api.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-fitpress-settings.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-fitpress-buddypress.php');
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-fitpress-shortcode.php');
// require_once( plugin_dir_path( __FILE__ ) . 'includes/class-friend-shortcode.php');
// require_once( plugin_dir_path( __FILE__ ) . 'includes/class-friend-post-type.php');
// require_once( plugin_dir_path( __FILE__ ) . 'includes/class-friend-ajax.php');


$consumer_key = get_site_option( 'fitpress_consumer_key', false );
$consumer_secret = get_site_option( 'fitpress_consumer_secret', false );
// $fitbit_token = get_site_option( 'fitpress_fitbit_token', false );
// $fitbit_secret = get_site_option( 'fitpress_fitbit_secret', false );
$tab_name = get_site_option( 'fitpress_tab_name', false );

define( 'FITPRESS_CONSUMER_KEY', $consumer_key );
define( 'FITPRESS_CONSUMER_SECRET', $consumer_secret );
// define( 'FITPRESS_FITBIT_TOKEN', $fitbit_token );
// define( 'FITPRESS_FITBIT_SECRET', $fitbit_secret );
define( 'FITPRESS_TAB_NAME', $tab_name );

global $fitbit_php;

if( '' != FITPRESS_CONSUMER_KEY && '' != FITPRESS_CONSUMER_SECRET ){

    $fitbit_php = new FitBitPHP( FITPRESS_CONSUMER_KEY, FITPRESS_CONSUMER_SECRET, 0, null, 'json' );

}

/**
 * Enqueue scripts
 *
 * @param string $handle Script name
 * @param string $src Script url
 * @param array $deps (optional) Array of script names on which this script depends
 * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
 * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
 */
function fitbit_frontend_enqueue_scripts() {
    wp_enqueue_script( 'fitpress-scripts', plugins_url( '/assets/js/fitpress-frontend-scripts.min.js' , __FILE__ ), array( 'jquery' ), FITPRESS_PLUGIN_VERSION, false);
    wp_enqueue_style( 'fitpress-styles', plugins_url( '/assets/css/fitpress-frontend-styles.min.css' , __FILE__ ), $deps, FITPRESS_PLUGIN_VERSION );

    // Localize the script with new data
    $translation_array = array(
        'New_Friend_Added' => __( 'New Friend Added', 'fitpress' ),
    );

    wp_localize_script( 'fitpress-scripts-localize', 'fitpress_strings', $translation_array );

    wp_enqueue_script( 'fitpress-scripts-localize' );
}

// unneeded at this time
// add_action( 'wp_enqueue_scripts', 'fitbit_frontend_enqueue_scripts' );

/**
 * Enqueue scripts
 *
 * @param string $handle Script name
 * @param string $src Script url
 * @param array $deps (optional) Array of script names on which this script depends
 * @param string|bool $ver (optional) Script version (used for cache busting), set to null to disable
 * @param bool $in_footer (optional) Whether to enqueue the script before </head> or before </body>
 */
function fitbit_backend_enqueue_scripts() {
    wp_enqueue_script( 'fitpress-scripts', plugins_url( '/assets/js/fitpress-backend-scripts.min.js' , __FILE__ ), array( 'jquery' ), FITPRESS_PLUGIN_VERSION, false);
    wp_enqueue_style( 'fitpress-styles', plugins_url( '/assets/css/fitpress-backend-styles.min.css' , __FILE__ ), $deps, FITPRESS_PLUGIN_VERSION );

    // Localize the script with new data
    $translation_array = array(
        'New_Friend_Added' => __( 'New Friend Added', 'fitpress' ),
        
    );
    wp_localize_script( 'fitpress-scripts-localize', 'fitpress_strings', $translation_array );

    wp_enqueue_script( 'fitpress-scripts-localize' );
}

// unneeded at this time
// add_action( 'admin_enqueue_scripts', 'fitbit_backend_enqueue_scripts' );


