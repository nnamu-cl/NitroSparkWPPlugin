<?php
declare( strict_types=1 );
namespace NitroSpark\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Menu {

    /**
     * NitroSpark rocket menu icon (single-colour SVG, recoloured by WordPress to match the admin colour scheme).
     */
    private const MENU_ICON = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjIwIDIwIDQ3MiA0NzIiPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuMDAwMDAwLDUxMi4wMDAwMDApIHNjYWxlKDAuMTAwMDAwLC0wLjEwMDAwMCkiPjxwYXRoIGZpbGw9ImJsYWNrIiBkPSJNNDQxMCA0NzIxIGMtMTYwIC0zNyAtMzkxIC0xMTkgLTU4OSAtMjA4IGwtNjUgLTI5IDMxMyAtMjY4IGMxNzIgLTE0NyAzMTQgLTI2NiAzMTYgLTI2NCAyIDIgMjEgNzUgNDMgMTYzIDQ5IDE5NSA4MSAzODYgODkgNTIzIDcgMTE4IDE2IDExMSAtMTA3IDgzeiBNMzUzNyA0MzY1IGMtODg5IC00ODkgLTE2OTkgLTE0MzMgLTIwNDIgLTIzNzggbC0zNCAtOTQgMzU3IC0zMDYgMzU3IC0zMDUgNDUgMjUgYzk1OCA1NDMgMTcxMiAxNDE2IDIwODYgMjQxNyAyNSA2NiA0MSAxMjIgMzYgMTI1IC01IDMgLTE1OCAxMzQgLTM0MSAyOTEgLTE4MyAxNTcgLTMzNiAyODYgLTMzOSAyODcgLTQgMiAtNjAgLTI2IC0xMjUgLTYyeiBtMTE4IC01MDQgYzExNiAtNTYgMTc5IC0xNTIgMTc5IC0yNzcgMSAtMjY2IC0zMTMgLTQwNCAtNTE1IC0yMjYgLTI1OCAyMjYgMjcgNjUyIDMzNiA1MDN6IE0xMzkzIDI5NTggYy04MyAtMTIwIC03ODQgLTEyMTAgLTc4MSAtMTIxMyA0IC01IDc4MSAzMTQgNzkxIDMyNCA1IDUgMzUgNjkgNjYgMTQzIDkxIDIxMyAyMTggNDUxIDM2OSA2ODggNTEgODAgNjIgNzUgLTE3OSA4NCAtOTYgNCAtMTg4IDEwIC0yMDUgMTIgLTI5IDUgLTM1IDEgLTYxIC0zOHogTTMwODAgMTc4MyBjLTE4MSAtMTYxIC00NzEgLTM3NCAtNjUwIC00NzggbC03NSAtNDQgLTEwMSAtNDIxIGMtNTUgLTIzMSAtOTcgLTQxOSAtOTMgLTQxOCA0IDIgMjQ3IDIxNyA1NDAgNDc4IDI5MyAyNjEgNTQyIDQ4MiA1NTMgNDkxIDIwIDE2IDIwIDE4IC0yMiAyMjEgLTI0IDExMiAtNDYgMjE1IC01MCAyMjkgLTcgMjUgLTkgMjQgLTEwMiAtNTh6IE0xMzE1IDE2NTYgYy0xNjggLTYzIC0yNjggLTE2OSAtNDMwIC00NTYgLTQ4IC04NSAtMTAwIC0xNzIgLTExNiAtMTkyIGwtMzAgLTM4IDQxIDAgYzUxIDAgOTEgMjAgMTU1IDc4IDU0IDQ4IDE0MSAxMDIgMTY1IDEwMSAxMCAwIDYgLTYgLTEwIC0xNyAtMTQxIC05NiAtMjEyIC0yMTkgLTI4NSAtNDk3IC00MCAtMTQ5IC02MSAtMjAxIC05OCAtMjM2IGwtMjIgLTIxIDEwMCA0IGMxNTcgNiAyNTkgNjkgMzk3IDI0NyA3NCA5NSAxMzcgMTU5IDE4NyAxOTIgNjUgNDIgNzAgMzUgOSAtMTQgLTkzIC03NSAtMTIyIC0xMzYgLTE1MyAtMzE3IGwtNSAtMzQgMzIgMzEgYzE4IDE3IDEwNSA3MSAxOTMgMTE4IDI5OSAxNjMgNDE1IDI3OCA0ODUgNDgwIDMwIDg4IDI1IDg4IC0zMSAwIC02NSAtMTAxIC0xNzAgLTIwNCAtMjUxIC0yNDQgLTkyIC00NiAtMTExIC00MiAtNTkgMTQgMzggNDEgOTEgMTE3IDEyMCAxNzQgbDEzIDIzIC02NCAtNyBjLTE4MCAtMTkgLTM0MSAtODcgLTQ1OCAtMTkyIC01MiAtNDcgLTUyIC00NyAtNDYgLTE4IDMgMTcgMTMgNzIgMjEgMTIzIDMzIDE5NSA3MSAyNTUgMjM3IDM3MyAyMiAxNiAyMSAxNyAtNDYgMjQgLTgxIDkgLTE3OSAtNiAtMjM3IC0zNiAtNDggLTI0IC00OSAtMjcgNyA4OSA1NCAxMTIgMTM1IDIwMSAyMjMgMjQ1IDY2IDMzIDM5IDM1IC00NCAzeiIvPjwvZz48L3N2Zz4=';

    public function register(): void {
        add_action( 'admin_menu', [ $this, 'add_menu_pages' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'open_external_links_in_new_tab' ] );
    }

    public function add_menu_pages(): void {
        add_menu_page(
            __( 'NitroSpark', 'nitrospark' ),
            __( 'NitroSpark', 'nitrospark' ),
            'manage_options',
            'nitrospark',
            [ $this, 'render_page' ],
            self::MENU_ICON,
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

    /**
     * Opens the external NitroSpark submenu links in a new tab.
     */
    public function open_external_links_in_new_tab(): void {
        wp_register_script( 'nitrospark-menu', false, [], NITROSPARK_VERSION, true );
        wp_enqueue_script( 'nitrospark-menu' );
        wp_add_inline_script(
            'nitrospark-menu',
            "document.querySelectorAll( '#toplevel_page_nitrospark .wp-submenu a' ).forEach( function ( a ) {"
            . " if ( a.href.indexOf( 'http' ) === 0 && a.href.indexOf( window.location.origin ) === -1 ) {"
            . " a.target = '_blank'; a.rel = 'noopener noreferrer'; } } );"
        );
    }

    public function render_page(): void {
        echo '<div id="nitrospark-admin" class="nitrospark-app"></div>';
    }
}