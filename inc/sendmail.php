<?php

// allow us to use core wordpress functions
$plugin_name = 'invite-friends-to-register';
$plugin_dir = dirname(__FILE__);
$wp_dir = str_replace( DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $plugin_name . DIRECTORY_SEPARATOR . 'inc', '', $plugin_dir );
include( $wp_dir . DIRECTORY_SEPARATOR . 'wp-load.php' );

$post = ( !empty( $_POST ) ) ? true : false;

if( $post ) {
	extract( invfr_get_settings() ); // get settings: $subject, $message, $redirect
	$friends = $_POST['friend_email'];
	$errors = array();
	foreach ( $friends as $key => $friend ) {
		$name = stripslashes( $_POST['friend_name'][$key] );
		$email = trim( $_POST['friend_email'][$key] );
	 
		// Check name
		if( !$name )
			$errors[] = '#friend_name-' . $key;
			
		if( !$email )
			$errors[] = '#friend_email-' . $key;
	 
		if( $email && !invfr_validate_email( $email ) )
			$errors[] = '#friend_email-' . $key;
	}
		
	// send email 
	if( !$errors ) {
		foreach ( $friends as $key => $friend )
			$mail = wp_mail( $email, invfr_tokens_replacement( $subject, $_POST, $key ), invfr_tokens_replacement( $message, $_POST, $key ) );
		if( $mail )
			echo 'sent';
	}
	else
		echo json_encode($errors);

}