<?php

/**
 * echos a hidden field for storing the referrer's ID in $_POST
 */
add_action( 'register_form', 'invfr_referrer_field');
function invfr_referrer_field() {
	$referrer = '';
	if ( $_REQUEST['invfr'] ) $referrer = $_REQUEST['invfr'];
	elseif ( $_POST['invfr_referrer'] ) $referrer = $_POST['invfr_referrer'];
	if ( $referrer != '' )
		echo '<input type="hidden" name="invfr_referrer" value="' . $referrer . '" />';
}

/**
 * adds the new users's ID as a friend to the referrer, and the referrer ID to the new user
 */
add_action( 'user_register', 'invfr_register_hook' );
function invfr_register_hook( $user_id ) {
	$referrer = $_POST['invfr_referrer'];
	if ( $referrer ) {
		// add new user's ID as a friend to the referrer
		add_user_meta( $referrer, 'invfr_friend', $user_id );
		// add referrer's ID to the new user
		add_user_meta( $user_id, 'invfr_referrer', $referrer );
	}
}

/**
 * returns the invitation url
 */
function invfr_invite_url( $user_id ) {
	extract( invfr_get_settings() ); // get settings: $subject, $message, $redirect
	$url = get_option( 'siteurl' ) . '/wp-login.php?action=register&invfr=' . $user_id . '&redirect_to=' . $redirect;
	return $url;
}