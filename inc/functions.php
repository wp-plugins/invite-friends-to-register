<?php

/**
 * Subject and email tokens used for merging
 */
function invfr_tokens( ) {
	$tokens = '<code>%sitename%</code>, <code>%siteurl%</code>, <code>%friendname%</code>, <code>%friendemail%</code>, <code>%username%</code>, <code>%useremail%</code>, <code>%inviteurl%</code';
	return apply_filters( 'invfr_tokens', $tokens );
}

/**
 * Token replacement
 *
 * @param	string	$string		A string that contains tokens for replacing by user defined values
 * @param	array	$postdata	An array of data from a submitted form
 * @param	integar	$key		Default: null, the array key of the current item
 *
 * @return	string				The original string with tokens replaced with data from the submitted form
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

	foreach ( apply_filters( 'invfr_token_replacements', $replacements ) as $var => $repl ) {
		$string = str_replace( $var, $repl, $string );
	}

	return trim( $string );
}