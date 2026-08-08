<?php
/**
 * NitroSpark uninstall cleanup.
 *
 * Removes all options the plugin stored when the plugin is deleted.
 *
 * @package NitroSpark
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'nitrospark_auth_key' );
delete_option( 'nitrospark_auth_user_id' );
delete_option( 'nitrospark_debug_log' );
