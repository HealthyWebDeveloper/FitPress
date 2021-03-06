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
                'fitpress_fitbit_token' => array('label' => 'FitBit Token' ),
                'fitpress_fitbit_secret' => array('label' => 'FitBit Secret' ),

				'fitpress_fitbit_aboutMe' => array('label' => 'FitBit About Me' ),               	
                'fitpress_fitbit_age' => array('label' => 'FitBit Age' ),
               	'fitpress_fitbit_avatar' => array('label' => 'FitBit Avatar' ),
                'fitpress_fitbit_avatar150' => array('label' => 'FitBit Avatar 150' ),
                'fitpress_fitbit_displayName' => array('label' => 'FitBit Display Name' ),
                'fitpress_fitbit_fullName' => array('label' => 'FitBit Full Name' ),
                'fitpress_fitbit_nickname' => array('label' => 'FitBit Nickname' ),
                'fitpress_fitbit_encodedId' => array('label' => 'FitBit Encoded Id' ),
                
                /*
				'fitpress_fitbit_averageDailySteps' => array('label' => 'FitBit Average Daily Steps' ),
                'fitpress_fitbit_dateOfBirth' => array('label' => 'FitBit Date Of Birth' ),
                
                'fitpress_fitbit_distanceUnit' => array('label' => 'FitBit Distance Units' ),
                
                // 'fitpress_fitbit_features' => array('label' => 'FitBit Features', 'process' => 'serialize' ),
				'fitpress_fitbit_foodsLocale' => array('label' => 'FitBit Foods Locale' ),
				
				'fitpress_fitbit_gender' => array('label' => 'FitBit Gender' ),
				'fitpress_fitbit_glucoseUnit' => array('label' => 'FitBit Glucose Unit' ),
				'fitpress_fitbit_height' => array('label' => 'FitBit Height' ),
				'fitpress_fitbit_heightUnit' => array('label' => 'FitBit Height Unit' ),
				'fitpress_fitbit_locale' => array('label' => 'FitBit Locale' ),
				'fitpress_fitbit_memberSince' => array('label' => 'FitBit Member Since' ),
				'fitpress_fitbit_nickname' => array('label' => 'FitBit Nickname' ),
				'fitpress_fitbit_offsetFromUTCMillis' => array('label' => 'FitBit Offset From UTC Millis' ),
				'fitpress_fitbit_startDayOfWeek' => array('label' => 'FitBit Start Day Of Week' ),
				'fitpress_fitbit_strideLengthRunning' => array('label' => 'FitBit Stride Length Running' ),
				'fitpress_fitbit_strideLengthWalking' => array('label' => 'FitBit Stride Length Walking' ),
				'fitpress_fitbit_timezone' => array('label' => 'FitBit Timezone' ),
				// 'fitpress_fitbit_topBadges' => array('label' => 'FitBit Top Badges', 'process' => 'serialize' ),
				'fitpress_fitbit_waterUnit' => array('label' => 'FitBit Water Unit' ),
				'fitpress_fitbit_waterUnitName' => array('label' => 'FitBit Water Unit Name' ),
				'fitpress_fitbit_weight' => array('label' => 'FitBit Weight' ),
				'fitpress_fitbit_weightUnit' => array('label' => 'FitBit Weight Unit' ),
				*/						                
				
				'fitpress_fitbit_TrackerSteps' => array('label' => 'FitBit Tracker Steps' ),
				'fitpress_fitbit_TrackerDistance' => array('label' => 'FitBit Tracker Distance' ),
				'fitpress_fitbit_TrackerFloors' => array('label' => 'FitBit Tracker Floors' ),
				'fitpress_fitbit_TrackerElevation' => array('label' => 'FitBit Tracker Elevation' ),
				'fitpress_fitbit_timeInBed' => array('label' => 'FitBit Time in Bed' ),
				
				
				'fitpress_fitbit_sleepMinutesAsleep' => array('label' => 'FitBit Sleep Minutes Asleep' ),
            );

        public static function bp_page_nav(){
            global $bp;

            global $fitbit_php;

            if(!is_user_logged_in() || !is_object($fitbit_php) ) return '';
         
            $user_domain = bp_displayed_user_domain() ? bp_displayed_user_domain() : bp_loggedin_user_domain();
            
            $profile_link = trailingslashit( $user_domain . $bp->profile->slug );
            
            bp_core_new_subnav_item( array(
                'name' => FITPRESS_TAB_NAME,
                'slug' => 'fitpress',
                'parent_url' => $profile_link,
                'parent_slug' => $bp->profile->slug,
                'screen_function' => array( 'FitPress_BuddyPress', 'page_screen' ),
                'position' => 20,
                'user_has_access' => current_user_can('edit_users'),
         
            ) );


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
                // echo '<img src="'.get_user_meta( $user_id,  'fitpress_fitbit_avatar150', true ).'" />';
                echo '<div class="row clearfix">';
                echo '<div class="col-sm-6 col-md-4 col-lg-3 text-center focus-box">';
                echo '<div class="service-icon"><i style="background:url('.$json->user->avatar150.') no-repeat center;width:100%; height:100%;" class="pixeden"></i> <!-- FOCUS ICON--></div>';
                echo '<h3 class="red-border-bottom">'.$json->user->fullName.'</h3>';
                
                echo '</div>';

                echo '<div class="col-small-6 col-md-8 col-lg-9">';
                echo '<h3>'.__( 'Additional Profile Details', 'fitpress').'</h3><ul>';
                
                $month_ini = new DateTime("first day of last month");
				$month_end = new DateTime("last day of last month");

				// echo $month_ini->format('Y-m-d'); // 2012-02-01
				// echo $month_end->format('Y-m-d'); // 2012-02-29
                /*

                $steps = $fitbit_php->getTimeSeries( 'steps', $month_ini, $month_end);
                
                $total_steps = 0;
                foreach($steps as $step){
	                $total_steps += $step->value;
                }
                
                echo sprintf( __( '<li>Total Steps Last Month: %s</li>', 'fitpress' ), number_format( $total_steps ) );

				// fitpress_fitbit_activitiesTrackerSteps

				$distance = $fitbit_php->getTimeSeries( 'distance', $month_ini, $month_end);
                
                $total_distance = 0;
                foreach($distance as $steps){
	                $total_distance += $steps->value;
                }
                
                echo sprintf( __( '<li>Total Distance Last Month: %s</li>', 'fitpress' ), number_format( $total_distance ) );
                
                // fitpress_fitbit_activitiesTrackerDistance
                
                $floors = $fitbit_php->getTimeSeries( 'floors', $month_ini, $month_end);
                
                $total_floors = 0;
                foreach($floors as $floor){
	                $total_floors += $floor->value;
                }
                
                echo sprintf( __( '<li>Total Floors Last Month: %s</li>', 'fitpress' ), number_format( $total_floors ) );

				// fitpress_fitbit_activitiesTrackerFloors
				
				$minutes = $fitbit_php->getTimeSeries( 'minutesAsleep', $month_ini, $month_end);
                
                $total_asleep = 0;
                foreach($minutes as $minute){
	                $total_asleep += $minute->value;
                }
                
                echo sprintf( __( '<li>Total Asleep Last Month: %s Minutes</li>', 'fitpress' ), number_format( $total_asleep ) );
                
*/
                // fitpress_fitbit_sleepMinutesAsleep
                
                
/*                 tracker_caloriesOut',  */


                
/*                 'tracker_steps',  */

				$user_id = get_current_user_id();

				$month = $fitbit_php->getTimeSeries( 'tracker_steps', $month_ini, $month_end);
                
                $total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
                
                echo sprintf( __( '<li>Total Steps Last Month: %s</li>', 'fitpress' ), number_format( $total ) );

				$total = number_format($total, 3, '.', '');

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_TrackerSteps', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_TrackerSteps', true ) != $total )
                    wp_die('An error occurred');
                
                
                
/*                 'tracker_distance',  */

				$month = $fitbit_php->getTimeSeries( 'tracker_distance', $month_ini, $month_end);
                
                $total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
                       
                         
                echo sprintf( __( '<li>Total Distance Last Month: %s</li>', 'fitpress' ), number_format( $total ) );

				$total = number_format($total, 3, '.', '');

                  // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_TrackerDistance', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_TrackerDistance', true ) != $total )
                    wp_die('An error occurred');

                
                
/*                 'tracker_floors',  */

				$month = $fitbit_php->getTimeSeries( 'tracker_floors', $month_ini, $month_end);
                
                $total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
                
                echo sprintf( __( '<li>Total Floors Last Month: %s floors</li>', 'fitpress' ), number_format( $total ) );

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_TrackerFloors', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_TrackerFloors', true ) != $total )
                    wp_die('An error occurred');
                
                
/*                 'tracker_elevation' */

				$month = $fitbit_php->getTimeSeries( 'tracker_elevation', $month_ini, $month_end);
                
                $total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
                
                echo sprintf( __( '<li>Total Elevation Last Month: %s</li>', 'fitpress' ), number_format( $total ) );

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_TrackerElevation', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_TrackerElevation', true ) != $total )
                    wp_die('An error occurred');
     
/*                  'startTime',  */
                 
                 
/*                  'timeInBed',  */

				$month = $fitbit_php->getTimeSeries( 'timeInBed', $month_ini, $month_end);
                
                $total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
                
                echo sprintf( __( '<li>Total Time In Bed Last Month: %s minutes</li>', 'fitpress' ), number_format( $total ) );

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_timeInBed', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_timeInBed', true ) != $total )
                    wp_die('An error occurred');
                 
                 
/*                  'minutesAsleep', */

				$month = $fitbit_php->getTimeSeries( 'minutesAsleep', $month_ini, $month_end);
                
                $total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
                
                echo sprintf( __( '<li>Total Minutes Asleep Last Month: %s minutes</li>', 'fitpress' ), number_format( $total ) );

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_sleepMinutesAsleep', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_sleepMinutesAsleep', true ) != $total )
                    wp_die('An error occurred');
                 
                 
/*                  'weight',  */

/* 				$month = $fitbit_php->getTimeSeries( 'weight', "today", NULL); */
                
                /*
				$total = 0;
                foreach($month as $day){
	                $total += $day->value;
                }
				*/
/*
  				var_dump($month);
  				
  				$total = 0; // $month->weight;
                
                echo sprintf( __( '<li>Final Weight Last Month: %s minutes</li>', 'fitpress' ), number_format( $total ) );

                 // will return false if the previous value is the same as $new_value
                update_user_meta( $user_id, 'fitpress_fitbit_weight', $total );

                // so check and make sure the stored value matches $new_value
                if ( get_user_meta($user_id,  'fitpress_fitbit_weight', true ) != $total )
                    wp_die('An error occurred');

*/
                 
                 
/*                  'bmi',  */
                 
/*                  'fat' */
                 
                 
                
                echo '</ul></div>';
                echo '</div>';

                echo '<hr />';

                if( isset( $json->user->topBadges ) && count( $json->user->topBadges ) ){
                    echo '<div class="clearfix">';
                    echo '<h3>'.__( 'Top Badges', 'fitpress' ).'</h3>';

                    echo '<div class="row clearfix">';
                    foreach( $json->user->topBadges as $badge ){

                        echo '<div class="col-sm-6 col-md-4 col-lg-3 text-center focus-box">';
                        // echo '<img src="'.$badge->image125px.'" />';
                        echo '<div class="service-icon"><i style="background:url('.$badge->image125px.') no-repeat center;width:100%; height:100%;" class="pixeden"></i> <!-- FOCUS ICON--></div>';
                        // echo '<div class="text-center">'.$badge->name.'</div>';
                        echo '<h3 class="red-border-bottom">'.$badge->name.'</h3>';
                        echo '</div>';

                    }
                    echo '</div></div><hr />';
                }


                $json = $fitbit_php->getFriends();

                if( isset( $json->friends ) && count( $json->friends ) ){
                    echo '<div class="clearfix">';
                    echo '<h3 class="clearfix">'.__( 'Friends', 'fitpress' ).'</h3>';

                    echo '<div class="row clearfix">';
                    foreach($json->friends  as $friend ){

                        echo '<div class="col-sm-6 col-md-4 col-lg-3 text-center focus-box">';
                        echo '<div class="service-icon"><i style="background:url('.$friend->user->avatar150.') no-repeat center;width:100%; height:100%;" class="pixeden"></i> <!-- FOCUS ICON--></div>';
                        echo '<h3 class="red-border-bottom">'.$friend->user->displayName.'</h3>';
                        echo '</div>';

                    }
                    echo '</div>';
                    echo '</div>';

                }


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
                        <th><label for="<?php echo $key; ?>"><?php echo $field['label']; ?></label></th>
            
                        <td>
                            <input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo esc_attr( get_the_author_meta( $key, $user->ID ) ); ?>" class="regular-text" /><br />
                            <span class="description"><?php printf( __('Your %s value.', 'fitpress' ), $field['label']); ?></span>
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


// if ( !function_exists('wp_new_user_notification') ) {
//     function wp_new_user_notification( ) {}
// }
