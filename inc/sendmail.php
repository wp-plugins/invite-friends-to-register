<?php

add_action( 'wp_ajax_invfr_process_ajax', 'invfr_sendmail');
add_action( 'wp_ajax_nopriv_invfr_process_ajax', 'invfr_sendmail');
function invfr_sendmail() {
	$post = ( !empty( $_POST ) ) ? true : false;
	
	if( $post ) {
		$subject = invfr_get_settings( 'subject' );
		$message = invfr_get_settings( 'message' );
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
		 
			if( $email && !is_email( $email ) )
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
			echo json_encode( $errors );
	}
}