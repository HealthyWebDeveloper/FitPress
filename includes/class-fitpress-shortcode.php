<?php

/**
 * Shortcode Class Header
 *
 *
 * @since 1.0.6
 *
 * @package fitpress
 */


class FitPress_Shortcode {

	public static function fitpress_auth_func( $atts, $content = "" ) {
		$atts = shortcode_atts( array(
			'show_friends' => 'true',
			'show_badges' => 'true',
						
		), $atts, 'fitpress_auth' );
		
		global $bp;

        global $fitbit_php;


		$return = "";
		
		if(!is_user_logged_in() || !is_object($fitbit_php) ) {

			$return = __( 'You must be logged in to authorize Fitbit', 'fitpress' );
			
			$return .= ' <a href="'.wp_login_url( get_permalink() ).'">'.__( 'Log in' ).'</a>';
		
			return $return;
		}else if( 2 == $fitbit_php->sessionStatus() ){
		
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

                $json = $fitbit_php->getProfile();


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

                if( 'true' == $atts['show_badges'] && isset( $json->user->topBadges ) && count( $json->user->topBadges ) ){
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

				if( 'true' == $atts['show_friends'] ){
					
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

				 }

                
				// https://www.fitbit.com/user/profile/apps
				
				echo sprintf( '<a href="https://www.fitbit.com/user/profile/apps" class="btn btn-danger btn-xs" target="_BLANK">%s</a>', __( 'Revoke Fitbit Access', 'fitpress' ) );

                // print_r($json);

            }else{
                
				/* $url = get_the_permalink(); */

			    $url = add_query_arg('FitPress', 'authorize_page', get_permalink());

                ?>
                <a href="<?php echo $url; ?>" class="btn btn-success btn-small"><?php _e( 'Authorize with Fitbit', 'fitpress' ); ?></a>
            <?php

            }
		
		
		return $return;

	} // end fitpress_auth_func
	
	function fitpress_auth_redirect()
		{
		
			global $bp;

			global $fitbit_php;
		    
		    if( 0 != $fitbit_php->sessionStatus() || 'authorize_page' == $_GET['FitPress'] ){

                $fitbit_php->initSession( get_permalink() );

            }
		    
		    
		}
}

add_shortcode( 'fitpress_auth', array( 'FitPress_Shortcode', 'fitpress_auth_func' ) );


add_action( 'template_redirect', array( 'FitPress_Shortcode', 'fitpress_auth_redirect' ) );