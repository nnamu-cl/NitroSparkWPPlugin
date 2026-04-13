<?php
declare( strict_types=1 );
namespace NitroSpark\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Menu {

    public function register(): void {
        add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );
        add_action( 'admin_footer', [ $this, 'open_external_links_in_new_tab' ] );
    }

    public function add_menu_pages(): void {
        add_menu_page(
            __( 'NitroSpark', 'nitrospark' ),
            __( 'NitroSpark', 'nitrospark' ),
            'manage_options',
            'nitrospark',
            [ $this, 'render_page' ],
            'dashicons-admin-site-alt3',
            30
        );

        // Re-label the auto-generated first submenu item
        add_submenu_page(
            'nitrospark',
            __( 'Dashboard', 'nitrospark' ),
            __( 'Dashboard', 'nitrospark' ),
            'manage_options',
            'nitrospark',
            [ $this, 'render_page' ]
        );

        // Inject external submenu links via the global $submenu.
        global $submenu;

        $app_url = defined( 'NITROSPARK_APP_URL' )
            ? rtrim( NITROSPARK_APP_URL, '/' )
            : 'https://app.nitrospark.ai';

        $submenu['nitrospark'][] = [ __( 'Creator', 'nitrospark' ),  'manage_options', $app_url . '/creator-new' ];
        $submenu['nitrospark'][] = [ __( 'Library', 'nitrospark' ),  'manage_options', $app_url . '/library' ];
        $submenu['nitrospark'][] = [ __( 'Settings', 'nitrospark' ), 'manage_options', $app_url . '/settings' ];
        $submenu['nitrospark'][] = [ __( 'Schedule', 'nitrospark' ), 'manage_options', $app_url . '/autogrowth' ];
    }

    public function open_external_links_in_new_tab(): void {
        ?>
        <script>
            document.querySelectorAll( '#toplevel_page_nitrospark .wp-submenu a' ).forEach( function( a ) {
                if ( a.href.indexOf( 'http' ) === 0 && a.href.indexOf( window.location.origin ) === -1 ) {
                    a.target = '_blank';
                    a.rel    = 'noopener noreferrer';
                }
            });
        </script>
        <?php
    }

    public function render_page(): void {
        echo '<div id="nitrospark-admin" class="nitrospark-app"></div>';
    }
}