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

// Remove the NitroSpark publisher account, keeping its posts by handing
// them to the administrator who connected the site (or any administrator).
$nitrospark_service_user_id = (int) get_option( 'nitrospark_service_user_id', 0 );
if ( $nitrospark_service_user_id && get_user_meta( $nitrospark_service_user_id, 'nitrospark_service_user', true ) ) {
	require_once ABSPATH . 'wp-admin/includes/user.php';

	$nitrospark_reassign = (int) get_option( 'nitrospark_connected_by', 0 );
	if ( ! $nitrospark_reassign || ! get_user_by( 'id', $nitrospark_reassign ) ) {
		$nitrospark_admins   = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
				'fields' => 'ID',
			)
		);
		$nitrospark_reassign = $nitrospark_admins ? (int) $nitrospark_admins[0] : 0;
	}

	if ( $nitrospark_reassign ) {
		wp_delete_user( $nitrospark_service_user_id, $nitrospark_reassign );
	}
}
remove_role( 'nitrospark_publisher' );

delete_option( 'nitrospark_auth_key' );
delete_option( 'nitrospark_auth_user_id' );
delete_option( 'nitrospark_connected_by' );
delete_option( 'nitrospark_service_user_id' );
delete_option( 'nitrospark_debug_log' );
