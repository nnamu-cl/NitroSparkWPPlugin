<?php
/**
 * Dedicated, least-privilege user that NitroSpark requests run as.
 *
 * @package NitroSpark\Core
 */

namespace NitroSpark\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages the "NitroSpark" service user and its custom role.
 *
 * Requests from the NitroSpark platform never run as the administrator who
 * connected the site. They run as this user, whose role only allows writing
 * posts and uploading media (plus read-only WooCommerce catalogue access).
 * It has no access to plugins, themes, users, settings or site code, and it
 * cannot sign in interactively or use application passwords.
 */
class ServiceUser {

	public const ROLE        = 'nitrospark_publisher';
	public const OPTION      = 'nitrospark_service_user_id';
	public const META_MARKER = 'nitrospark_service_user';

	/**
	 * Capabilities granted to the service role. Nothing else.
	 *
	 * @return array<string,bool>
	 */
	public static function capabilities(): array {
		return array(
			'read'                  => true,
			'edit_posts'            => true,
			'edit_published_posts'  => true,
			// Needed by the Internal Link Injector to add links to existing posts.
			'edit_others_posts'     => true,
			'publish_posts'         => true,
			'upload_files'          => true,
			// WooCommerce: required by its REST API to list products and
			// product categories. NitroSpark only exposes read (GET) routes
			// for WooCommerce data.
			'read_private_products' => true,
			'manage_product_terms'  => true,
		);
	}

	/**
	 * Registers runtime guards for the service user.
	 */
	public static function register_hooks(): void {
		add_filter( 'authenticate', array( self::class, 'block_login' ), 100, 1 );
		add_filter( 'wp_is_application_passwords_available_for_user', array( self::class, 'block_app_passwords' ), 10, 2 );
	}

	/**
	 * Prevents anyone from signing in as the service user.
	 *
	 * @param \WP_User|\WP_Error|null $user Result of earlier authentication handlers.
	 * @return \WP_User|\WP_Error|null
	 */
	public static function block_login( $user ) {
		if ( $user instanceof \WP_User && self::is_service_user( $user->ID ) ) {
			return new \WP_Error( 'nitrospark_service_user', __( 'This account cannot be used to sign in.', 'nitrospark' ) );
		}
		return $user;
	}

	/**
	 * Prevents application passwords from being created for the service user.
	 *
	 * @param bool     $available Whether application passwords are available.
	 * @param \WP_User $user      User being checked.
	 * @return bool
	 */
	public static function block_app_passwords( $available, $user ) {
		if ( $user instanceof \WP_User && self::is_service_user( $user->ID ) ) {
			return false;
		}
		return $available;
	}

	/**
	 * Whether the given user ID is the NitroSpark service user.
	 *
	 * @param int $user_id User ID.
	 */
	public static function is_service_user( int $user_id ): bool {
		return $user_id > 0 && (bool) get_user_meta( $user_id, self::META_MARKER, true );
	}

	/**
	 * Returns the service user if it exists and is still safely scoped.
	 *
	 * Returns null when the user is missing, was given another role, or has
	 * gained any administrative capability, so requests are refused rather
	 * than run with more privileges than intended.
	 */
	public static function get(): ?\WP_User {
		$user_id = (int) get_option( self::OPTION, 0 );
		$user    = $user_id ? get_user_by( 'id', $user_id ) : false;

		if ( ! $user || ! self::is_service_user( $user->ID ) ) {
			return null;
		}

		if ( array( self::ROLE ) !== array_values( $user->roles ) ) {
			return null;
		}

		foreach ( array( 'manage_options', 'activate_plugins', 'install_plugins', 'edit_plugins', 'switch_themes', 'edit_themes', 'create_users', 'edit_users', 'promote_users', 'delete_users', 'unfiltered_html', 'edit_files' ) as $cap ) {
			if ( $user->has_cap( $cap ) ) {
				return null;
			}
		}

		return $user;
	}

	/**
	 * Creates (or repairs) the service role and user.
	 *
	 * @return \WP_User|\WP_Error
	 */
	public static function ensure() {
		self::ensure_role();

		$user_id = (int) get_option( self::OPTION, 0 );
		$user    = $user_id ? get_user_by( 'id', $user_id ) : false;

		if ( ! $user || ! self::is_service_user( $user->ID ) ) {
			$login = 'nitrospark';
			if ( username_exists( $login ) ) {
				$login = 'nitrospark-' . strtolower( wp_generate_password( 6, false, false ) );
			}

			$user_id = wp_insert_user(
				array(
					'user_login'   => $login,
					'user_pass'    => wp_generate_password( 64, true, true ),
					'display_name' => 'NitroSpark',
					'nickname'     => 'NitroSpark',
					'role'         => self::ROLE,
				)
			);

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}

			update_user_meta( $user_id, self::META_MARKER, 1 );
			update_option( self::OPTION, $user_id, false );
			$user = get_user_by( 'id', $user_id );
		}

		// Always reset to exactly the service role.
		$user->set_role( self::ROLE );

		return $user;
	}

	/**
	 * Creates the role or brings its capabilities back in line.
	 */
	private static function ensure_role(): void {
		$role = get_role( self::ROLE );

		if ( ! $role ) {
			add_role( self::ROLE, __( 'NitroSpark Publisher', 'nitrospark' ), self::capabilities() );
			return;
		}

		$wanted = self::capabilities();
		foreach ( array_keys( $role->capabilities ) as $cap ) {
			if ( ! isset( $wanted[ $cap ] ) ) {
				$role->remove_cap( $cap );
			}
		}
		foreach ( $wanted as $cap => $grant ) {
			$role->add_cap( $cap, $grant );
		}
	}
}
