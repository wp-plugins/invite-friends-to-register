=== Invite Friends to Register ===
Contributors: tammyhart
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tammyhartdesigns%40gmail%2ecom&item_name=Invite%20Friends%20to%20Register%20Plugin&no_shipping=0&no_note=1
Tags: invite, users, mail, registration
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 0.3
License: GPLv2 or later

Use this plugin to allow users to invite their friends to register on your site.

== Description ==

This plugin adds a page to the admin under Users that includes a form for users to send email invitations to their friends to register at your site. You can also add the form to the front end of your site with the `invite_friends()` template tag or the `[invite_friends]` shortcode.

**IMPORTANT:** This plugin will not allow invited friends to register unless you have registration turned on.

= Features =
* Invite multiple friends at one time
* Customize the email subject and message that is sent
* Customize the URL to redirect a user to after they register
* Store successful registered users in the inviter's user meta data
* Store the inviter in the new user's meta data

== Installation ==

Upload the plugin folder to your wp-content/plugins directory or find and install from the plugin admin area.
Activate and let user's invite their friends under Users > Invite Friends!

= Optional Next Steps: =
* Add a front end form with the `invite_friends()` template tag or the `[invite_friends]` shortcode
* Customize the plugin's settings found under Settings > Invite Friends

== Changelog ==

= 0.3 - November 13, 2012 =
* Removed invfr_validate_email() from functions.php since it is no longer used
* added a filter to the tokens: invr_tokens
* added a filter to token replacements: invfr_token_replacements
* Changed return of invfr_get_settings() from an array of settings, the individually specified field
* Fixed data sanitization on the settings fields

= 0.2 - September 15, 2012 =
* Moved load textdomain to init
* Replaced invfr_validate_email() with is_email()
* Updated Ajax sendmail method
(Special thanks to [Pippin Williamson](http://www.pippinsplugins.com) for the code review and these fix suggestions!)

= 0.1 - August 31, 2012 =
* First Release