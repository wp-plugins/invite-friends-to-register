<?php


/**
 * Validate an email address
 */
function invfr_validate_email( $email ) {
	$regex = '/([a-z0-9_.-]+)'. # name
	'@'. # at
	'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains
	'.'. # period
	'([a-z]+){2,10}/i'; # domain extension 

	if( $email == '' ) 
		return false;
	else
		$eregi = preg_replace( $regex, '', $email );
 
	return empty( $eregi ) ? true : false;
}

/**
 * Returns the settings array with defaults
 */
function invfr_get_settings() {
	$saved = (array) get_option( 'invfr_settings' );
	$defaults = array(
		'subject'	=> __( 'Your friend has invited you to register at %sitename%', 'invfr' ),
		'message'	=> __( "Dear %friendname%,\n\nI think you would really enjoy being a member at %sitename%. Follow this link to register now: %inviteurl%\n\nYour friend,\n%username%", 'invfr' ),
		'redirect'	=> get_option( 'siteurl' ) . '/wp-admin/profile.php',
	);

	$defaults = apply_filters( 'invfr_default_settings', $defaults );

	$settings = wp_parse_args( $saved, $defaults );
	$settings = array_intersect_key( $settings, $defaults );

	return $settings;
}

/**
 * Subject and email tokens used for merging
 */
function invfr_tokens( $array = true ) {
	$tokens = '<code>%sitename%</code>, <code>%siteurl%</code>, <code>%friendname%</code>, <code>%friendemail%</code>, <code>%username%</code>, <code>%useremail%</code>, <code>%inviteurl%</code';
	return $tokens;
}

/**
 * Token replacement
 * @param string $string A string that contains tokens for replacing by user defined values
 * @param array $postdata An array of data from a submitted form
 * @param integar $key Default: null, the array key of the current item
 */
function invfr_tokens_replacement( $string, $postdata, $key = null ) {
	
	$postdata = (array) $postdata;

	if ( strpos( $string, '%' ) === false )
		return trim( preg_replace( '/\s+/u', ' ', $string ) );

	$replacements = array(
		'%sitename%'	=> get_option( 'blogname' ),
		'%siteurl%'		=> get_option( 'siteurl' ),
		'%friendname%'	=> $postdata['friend_name'][$key],
		'%friendemail%'	=> $postdata['friend_email'][$key],
		'%username%'	=> $postdata['user_name'],
		'%useremail%'	=> $postdata['user_email'],
		'%inviteurl%'	=> invfr_invite_url( $postdata['user_id'] )
	);

	foreach ( $replacements as $var => $repl ) {
		$string = str_replace( $var, $repl, $string );
	}

	return trim( $string );
}