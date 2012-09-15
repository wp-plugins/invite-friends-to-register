<?php
/*
Plugin Name: Invite Friends to Register
Plugin URI: http://tammyhartdesigns.com/plugins-themes
Description: Use this plugin to allow users to invite their friends to register on your site.
Version: 0.2
Author: Tammy Hart
Author URI: http://tammyhartdesigns.com
License: GPLv2 or later
*/

/*
Copyright (c) 2012, Tammy Hart 

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


define( 'INVFR_URL', plugin_dir_url( __FILE__ ) );
define( 'INVFR_PATH', plugin_dir_path( __FILE__ ) );

// Localization
add_action( 'init', 'invfr_textdomain' );
function invfr_textdomain() {
	load_plugin_textdomain( 'invfr', false, INVFR_URL . '/lang' );
}

// Add the invite form page under Users and a settings page under Settings
add_action( 'admin_menu', 'invfr_add_pages' );
function invfr_add_pages() {
	add_users_page( __( 'Invite Friends to Register', 'invfr' ), __( 'Invite Friends', 'invfr' ), 'read', 'invite-friends', 'invfr_invite_friends_page' );
	add_options_page( __( 'Invite Friends Settings', 'invfr' ), __( 'Invite Friends', 'invfr' ), 'manage_options', 'invite-friends-settings', 'invfr_settings_page' );
}

// Include necessary files
include( INVFR_PATH . '/inc/functions.php' );
include( INVFR_PATH . '/inc/settings.php' );
include( INVFR_PATH . '/inc/inviteform.php' );
include( INVFR_PATH . '/inc/registration.php' );
include( INVFR_PATH . '/inc/sendmail.php' );

// enqueue stuff
add_action( 'wp_footer', 'invfr_enqueue' );
add_action( 'admin_footer', 'invfr_enqueue' );
function invfr_enqueue() {
	global $invfr_add_scripts;
	
	if ( ! $invfr_add_scripts )
		return;
		
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'invfr', INVFR_URL . 'invfr.js' );
	wp_enqueue_style( 'invfr', INVFR_URL . 'invfr.css' );

}
