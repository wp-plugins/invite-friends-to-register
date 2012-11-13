<?php

/**
 * returns the javascript and form code for an ajax invite form
 */
function invfr_form() {
	global $post, $invfr_add_scripts;
	
	$invfr_add_scripts = true;
	
	// only allow the form to be used if the user is logged in. 
	// Offer a login link that redirects back to the page the form 
	// was trying to be called on
	if ( !is_user_logged_in() )
		$output = sprintf( __( 'You must be logged in to invite friends. <a href="%s">Log in</a>', 'invfr'), wp_login_url( get_permalink( $post->ID ) ) );
	else {
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$user_name = $user->user_firstname ? $user->user_firstname : $user->user_nicename;
		$user_email = $user->user_email;
		
		ob_start(); ?>
		<script type="text/javascript"> 
			var jQuery = jQuery.noConflict();
			jQuery(window).load(function(){	
				jQuery('#invfr_form').submit(function() {
					// change visual indicators
					jQuery('td').removeClass('error');
					jQuery('.loading').show();
					jQuery('.submit input').attr('disabled', 'disabled');
					// validate and process form here
					var str = jQuery(this).serialize();					 
					   jQuery.ajax({
						   type: 'POST',
						   url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
						   data: str,
						   success: function(msg) {	
								jQuery('#invfr_note').ajaxComplete(function(event, request, settings) {
									msg = msg.replace(/(\s+)?.$/, "");	
									if( msg == 'sent' ) {
										result = '<div class="updated"><p><?php _e( 'Your invitation has been sent! Send another?', 'invfr' ); ?></p></div>';
										jQuery('#invfr_form input[type=text], #invfr_form input[type=email]').val('');
									} else {
										//loop through the error items to indicate which fields have errors
										msg = msg.replace(/[\[\]']+/g,'');
										msg = msg.split(',');
										jQuery.each( msg, function ( i, id ) {
											id = id.replace(/["']{1}/g, '');
											jQuery(id).parent('td').addClass('error');
										});
										result = '<div class="error"><p><?php _e( '<strong>ERROR:</strong> Check your form for the errors which are highlighted below.', 'invfr' ); ?></p></div>';
										//result = msg;
										msg = '';
									}
									jQuery(this).html(result);
									// visual indicators
									jQuery('.loading').hide();
									jQuery('.submit input').removeAttr('disabled');						 
								});					 
							}					 
						});					 
					return false;
				});			
			});
		</script>
		
		<div id="invfr_form_container">
			<p><?php echo sprintf( __( 'Enter your friend&rsquo;s name and email address to send them an invitation to register at %s', 'invfr' ), get_option( 'blogname' ) ); ?>
			<div id="invfr_note"></div>
			<form id="invfr_form" action="">
				<table class="form-table">
					<thead>
						<tr>
							<th><?php _e( 'Friend&rsquo;s Name', 'invfr' ); ?></th>
							<th><?php _e( 'Friend&rsquo;s Email', 'invfr' ); ?></th>
							<th><a class="invfr_add button" href="#">+</a></th>
						</tr>
					</thead>
					<tr>
						<td valign="top"><label for="friend_name-0" class="screen-reader-text"><?php _e( 'Friend&rsquo;s Name', 'invfr' ); ?></label>
							<input type="text" name="friend_name[0]" id="friend_name-0" value="" />
							<span class="error-msg"><?php _e( 'Enter your friend&rsquo;s name', 'invfr' ); ?></span>
						</td>
						<td valign="top"><label for="friend_email-0" class="screen-reader-text"><?php _e( 'Friend&rsquo;s Email', 'invfr' ); ?></label>
							<input type="email" name="friend_email[0]" id="friend_email-0" value="" />
							<span class="error-msg"><?php _e( 'Enter a valid email address', 'invfr' ); ?></span>
						</td>
						<td><a class="invfr_remove button" href="#">-</a></td>
					</tr>
				</table>
									
				<p class="submit"><input type="submit" value="<?php _e( 'Send Invitation', 'invfr' ); ?>" class="button button-primary" /> 
					<img src="<?php echo get_option( 'siteurl'); ?>/wp-admin/images/loading.gif" class="loading" /></p>
					
				<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
				<input type="hidden" name="user_name" value="<?php echo $user_name; ?>" />
				<input type="hidden" name="user_email" value="<?php echo $user_email; ?>" />
				<input type="hidden" name="action" value="invfr_process_ajax"/>
			</form>
		</div>
		<?php
		$output = ob_get_contents();
		ob_clean();
	}
	
	return $output;
}

// Template tag
if ( !function_exists( 'invite_friends' ) ) {
	function invite_friends( ) {
		echo invfr_form();
	}
}

// Shortcode
add_shortcode( 'invite_friends', 'invfr_form' );

/**
 * callback for the users page that allows you to invite friends from the admin
 */
function invfr_invite_friends_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Invite Friends', 'invfr' ); ?></h2>
		<?php settings_errors(); ?>

		<?php echo invfr_form(); ?>
	</div>
	<?php
}