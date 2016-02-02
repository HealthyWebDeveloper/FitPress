<?php

/**
 * Class for FitPress BuddyPress Class
 *
 * @since 1.2.1
 *
 * @package FitPress
 * @author https://codex.buddypress.org/plugindev/how-to-enjoy-bp-theme-compat-in-plugins/
 */

// Check that the class exists before trying to use it
if ( ! class_exists('FitPress_BuddyPress')) {

    class FitPress_BuddyPress{

        static $custom_profile_fields = array(
                'FitBit Token' => array('replacement' => 'fitpress_fitbit_token', 'process' => null ),
                'FitBit Secret' => array('replacement' => 'fitpress_fitbit_secret', 'process' => null ),
                'FitBit Avatar' => array('replacement' => 'fitpress_fitbit_avatar', 'process' => null ),
                'FitBit Avatar 150' => array('replacement' => 'fitpress_fitbit_avatar150', 'process' => null ),
                
            );

        public static function bp_page_nav(){
            global $bp;

            if(!is_user_logged_in()) return '';
         
            $user_domain = bp_displayed_user_domain() ? bp_displayed_user_domain() : bp_loggedin_user_domain();
            
            $profile_link = trailingslashit( $user_domain . $bp->profile->slug );
            
            bp_core_new_subnav_item( array(
                'name' => __( 'FitPress', 'fitpress' ),
                'slug' => 'fitpress',
                'parent_url' => $profile_link,
                'parent_slug' => $bp->profile->slug,
                'screen_function' => array( 'FitPress_BuddyPress', 'page_screen' ),
                'position' => 20,
                'user_has_access' => current_user_can('edit_users'),
         
            ) );

            global $fitbit_php;

            // $fitbit_php->resetSession();

            // var_dump($fitbit_php);

            $user_domain = bp_displayed_user_domain() ? bp_displayed_user_domain() : bp_loggedin_user_domain();
            
            $profile_link = trailingslashit( $user_domain . $bp->profile->slug );
            
            // var_dump($fitbit_php->sessionStatus());

            // wp_die();

            if( 0 != $fitbit_php->sessionStatus() || 'authorize' == $_GET['FitPress'] ){

                $fitbit_php->initSession($profile_link.'/fitpress/');


                $user_id = get_current_user_id();

                $new_value = $fitbit_php->getOAuthToken();

                // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_token', $new_value );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_token', true ) != $new_value )
                    wp_die('An error occurred');

                $new_value = $fitbit_php->getOAuthSecret();

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_secret', $new_value );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_secret', true ) != $new_value )
                    wp_die('An error occurred');

            }

        }

        public static function page_screen(){
            global $bp;
            add_action( 'bp_template_content', array( 'FitPress_BuddyPress', 'bp_page_screen_content' ) );
            bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
        }
         
        public static function bp_page_screen_content(){
            global $bp;

            global $fitbit_php;

            // var_dump($fitbit_php->sessionStatus());

            if( 2 == $fitbit_php->sessionStatus() ){

                $json = $fitbit_php->getProfile();

                $user_id = get_current_user_id();

                $new_value = $json->user->avatar;

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_avatar', $new_value );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_avatar', true ) != $new_value )
                    wp_die('An error occurred');

                $new_value = $json->user->avatar150;

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_avatar150', $new_value );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_avatar150', true ) != $new_value )
                    wp_die('An error occurred');

                echo '<h2>'.__( 'Fitbit Profile Details', 'fitpress' ).'</h2>';
                echo '<img src="'.get_user_meta($user_id,  'fitpress_fitbit_avatar150', true ).'" />';

                // print_r($json);

            }else{
                
                $user_domain = bp_displayed_user_domain() ? bp_displayed_user_domain() : bp_loggedin_user_domain();
            
                $profile_link = trailingslashit( $user_domain . $bp->profile->slug );

                $url = $profile_link.'fitpress/?FitPress=authorize';            

                ?>
                <a href="<?php echo $url; ?>" class="btn btn-success btn-small"><?php _e( 'Authorize with Fitbit' ); ?></a>
            <?php

            }

            

            // print_r($json);

            // print_r($_SESSION);



            
         
        }

        /*
        Plugin Name: BK User Custom Profiles
        Plugin URI: http://bradknowlton.com/
        Description: This is not just a plugin, it makes WordPress better.
        Author: Bradford Knowlton
        Version: 1.6.1
        Author URI: http://bradknowlton.com/
        */
                
        public static function show_extra_profile_fields( $user ) { 
            if ( current_user_can( 'manage_options' ) ) {
            /* A user with admin privileges */
            
            ?>

            <h3><?php _e( 'FitPress Extra Settings' ); ?></h3>

            <table class="form-table">
                <?php foreach(self::$custom_profile_fields as $key => $field){ ?>
                    <?php // if(  'true' != $field['private'] ){  // current_user_can( 'manage_options' ) ||  ?>
                    <tr>
                        <th><label for="<?php echo $field['replacement']; ?>"><?php echo $key; ?></label></th>
            
                        <td>
                            <input type="text" name="<?php echo $field['replacement']; ?>" id="<?php echo $field['replacement']; ?>" value="<?php echo esc_attr( get_the_author_meta( $field['replacement'], $user->ID ) ); ?>" class="regular-text" /><br />
                            <span class="description">Please enter your <?php echo $key; ?>.</span>
                        </td>
                    </tr>
                    <?php // } // end if ?>
                <?php } // end foreach ?>
            </table>
            <?php 
                
            } else {
                /* A user without admin privileges */
            }
                
        }

        function save_extra_profile_fields( $user_id ) {
            if ( !current_user_can( 'edit_user', $user_id ) )
                return false;
            /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
            // update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
        }


    }

}


add_action('bp_setup_nav', array( 'FitPress_BuddyPress', 'bp_page_nav' ), 10 );

add_action( 'show_user_profile', array( 'FitPress_BuddyPress', 'show_extra_profile_fields' ) );
add_action( 'edit_user_profile', array( 'FitPress_BuddyPress', 'show_extra_profile_fields' ) );

add_action( 'personal_options_update', array( 'FitPress_BuddyPress', 'save_extra_profile_fields' ) );
add_action( 'edit_user_profile_update', array( 'FitPress_BuddyPress', 'save_extra_profile_fields' ) );


if ( !function_exists('wp_new_user_notification') ) {
    function wp_new_user_notification( ) {}
}
