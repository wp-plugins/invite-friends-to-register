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
 * Field callback functions
 */
function invfr_subject_field() {
	$settings = invfr_get_settings();
	?>
	<input type="text" class="regular-text" name="invfr_settings[subject]" id="subject" value="<?php echo esc_attr( $settings['subject'] ); ?>" /><br />
	<label class="description" for="subject"><?php _e( 'Available tokens: ', 'invfr' ); echo invfr_tokens( false ); ?></label>
	<?php
}
function invfr_message_field() {
	$settings = invfr_get_settings();
	?>
	<textarea rows="5" cols="80" name="invfr_settings[message]" id="message"><?php echo wp_filter_nohtml_kses( $settings['message'] ); ?></textarea><br />
	<label class="description" for="message"><?php _e( 'Available tokens: ', 'invfr' ); echo invfr_tokens( false ); ?></label>
	<?php
}
function invfr_redirect_field() {
	$settings = invfr_get_settings();
	?>
	<input type="text" class="regular-text" name="invfr_settings[redirect]" id="redirect" value="<?php echo esc_url_raw( $settings['redirect'] ); ?>" /><br />
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
		<?php settings_errors(); ?>

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
		$output['subject'] = esc_attr( $input['subject'] );

	// The sample text input must be safe text with no HTML tags
	if ( isset( $input['message'] ) && ! empty( $input['message'] ) )
		$output['message'] = wp_filter_nohtml_kses( $input['message'] );

	// The sample text input must be safe text with no HTML tags
	if ( isset( $input['redirect'] ) && ! empty( $input['redirect'] ) )
		$output['redirect'] = esc_url_raw( $input['redirect'] );

	return apply_filters( 'invfr_settings_validate', $output, $input );
}
