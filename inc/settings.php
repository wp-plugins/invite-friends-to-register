<?php


/**
 * Create settings
 *
 * Settings:
 * - email subject
 * - email message
 * - redirect url 
 */
add_action( 'admin_init', 'invfr_settings_init' );
function invfr_settings_init() {
	// register
	register_setting( 'invfr_settings', 'invfr_settings', 'invfr_settings_validate' );
	// section
	add_settings_section( 'invfr_general', '', '__return_false', 'invite-friends-settings' );
	// fields
	add_settings_field( 'subject', __( 'Email Subject', 'invfr' ), 'invfr_subject_field', 'invite-friends-settings', 'invfr_general' );
	add_settings_field( 'message', __( 'Email Message', 'invfr' ), 'invfr_message_field', 'invite-friends-settings', 'invfr_general' );
	add_settings_field( 'redirect', __( 'Redirect URL', 'invfr' ), 'invfr_redirect_field', 'invite-friends-settings', 'invfr_general' );
}

/**
 * Returns the settings array with defaults
 *
 * @param	string	$field	The settings field you want to be returned
 * @return	string			The field either from the saved settings or the default
 */
function invfr_get_settings( $field ) {
	
	$saved = (array) get_option( 'invfr_settings' );
	$defaults = array(
		'subject'	=> __( 'Your friend has invited you to register at %sitename%', 'invfr' ),
		'message'	=> __( "Dear %friendname%,\n\nI think you would really enjoy being a member at %sitename%. Follow this link to register now: %inviteurl%\n\nYour friend,\n%username%", 'invfr' ),
		'redirect'	=> get_option( 'siteurl' ) . '/wp-admin/profile.php',
	);

	$defaults = apply_filters( 'invfr_default_settings', $defaults );

	$settings = wp_parse_args( $saved, $defaults );
	$settings = array_intersect_key( $settings, $defaults );

	return $settings[$field];
}

/**
 * Field callback functions
 */
function invfr_subject_field() {
	?>
	<input type="text" class="regular-text" name="invfr_settings[subject]" id="subject" value="<?php echo esc_attr( invfr_get_settings( 'subject' ) ); ?>" /><br />
	<label class="description" for="subject"><?php _e( 'Available tokens: ', 'invfr' ); echo invfr_tokens(); ?></label>
	<?php
}
function invfr_message_field() {
	?>
	<textarea rows="5" cols="80" name="invfr_settings[message]" id="message"><?php echo wp_kses_stripslashes( invfr_get_settings( 'message' ) ); ?></textarea><br />
	<label class="description" for="message"><?php _e( 'Available tokens: ', 'invfr' ); echo invfr_tokens(); ?></label>
	<?php
}
function invfr_redirect_field() {
	?>
	<input type="text" class="regular-text" name="invfr_settings[redirect]" id="redirect" value="<?php echo esc_url_raw( invfr_get_settings( 'redirect' ) ); ?>" /><br />
	<label class="description" for="redirect"><?php _e( 'URL to redirect the friend to after they register.', 'invfr' ); ?></label>
	<?php
}

/**
 * The settings page callback
 */
function invfr_settings_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Invite Friends Settings', 'invfr' ); ?></h2>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'invfr_settings' );
				do_settings_sections( 'invite-friends-settings' );
			
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see invfr_settings_init()
 * @todo set up Reset Options action
 *
 * @param array $input Unknown values.
 * @return array Sanitized theme options ready to be stored in the database.
 */
function invfr_settings_validate( $input ) {
	$output = array();

	// The sample text input must be safe text with no HTML tags
	if ( isset( $input['subject'] ) && ! empty( $input['subject'] ) )
		$output['subject'] = sanitize_text_field( $input['subject'] );

	// The sample text input must be safe text with no HTML tags
	if ( isset( $input['message'] ) && ! empty( $input['message'] ) )
		$output['message'] = wp_kses_stripslashes( $input['message'] );

	// The sample text input must be safe text with no HTML tags
	if ( isset( $input['redirect'] ) && ! empty( $input['redirect'] ) )
		$output['redirect'] = esc_url_raw( $input['redirect'] );

	return apply_filters( 'invfr_settings_validate', $output, $input );
}
