# NitroWPInitial — Reference Notes

Architectural snapshot of the existing plugin used as inspiration for NitroSparkWPPlugin.

---

## Plugin Structure

```
NitroWPInitial/
├── plugin.php                  # Entry point — bootstraps the NitroWPInitial class
├── includes/
│   ├── Admin/Menu.php          # WP admin menu registration
│   ├── Assets/Admin.php        # Admin script enqueue + wp_localize_script
│   ├── Assets/Frontend.php     # Frontend script enqueue
│   ├── Core/Api.php            # Bootstraps the REST API router
│   ├── Core/Template.php       # Custom page template support
│   ├── Controllers/
│   │   ├── Auth/Actions.php    # Auth callback + debug controller
│   │   └── NitroSpark/
│   │       ├── Auth.php        # Permission check for protected routes
│   │       ├── Posts.php       # Post CRUD controller
│   │       ├── Media.php       # Media controller
│   │       ├── Taxonomy.php    # Categories + pages controller
│   │       └── WooCommerce.php # WC products controller
│   ├── Routes/Api.php          # All REST route definitions (single file)
│   ├── Interfaces/
│   ├── Models/
│   └── Traits/Base.php         # Singleton helper trait
├── src/
│   ├── admin/                  # React admin app (Vite entry: src/admin/main.jsx)
│   │   ├── main.jsx
│   │   ├── routes.jsx
│   │   └── pages/ (dashboard, inbox, settings, charts, login, error)
│   └── frontend/               # React frontend app (separate Vite build)
│       ├── main.jsx
│       └── routes.jsx
├── assets/
│   ├── admin/dist/             # Built admin React app
│   └── blocks/block-1/        # Registered Gutenberg block
├── vite.admin.config.js        # Vite build for admin React app
├── vite.frontend.config.js     # Vite build for frontend React app
└── config/plugin.php           # Plugin config (dev/prod NitroSpark URL toggle)
```

---

## React Embedding Pattern

The admin page renders a bare `<div>` mount point — React hydrates it:

```php
// includes/Admin/Menu.php
public function admin_page() {
    echo '<div id="myplugin" class="myplugin-app"></div>';
}
```

The React app (`src/admin/main.jsx`) mounts into that div. Built with **Vite** using the `@kucrut/vite-for-wp` plugin which handles WP asset manifest and versioning.

React is loaded as a WP dependency (`'react', 'react-dom'`) — not bundled.

Data is passed from PHP to React via `wp_localize_script`:

```php
wp_localize_script( 'wordpress-plugin-boilerplate', 'wordpressPluginBoilerplate', [
    'isAdmin'     => is_admin(),
    'apiUrl'      => rest_url(),
    'userInfo'    => [ 'username', 'avatar' ],
    'isConnected' => ! empty( get_option( 'nitrospark_auth_key' ) ),
    'connectUrl'  => '<generated URL with state token>',
]);
```

---

## Admin React Routes (Hash Router)

Uses `react-router-dom` with `createHashRouter` (URL hash-based, no server routing needed):

| Hash path    | Component   |
|-------------|-------------|
| `/`          | Dashboard   |
| `/dashboard` | Dashboard   |
| `/inbox`     | Inbox       |
| `/settings`  | Settings    |
| `/charts`    | Charts      |
| `/login`     | LoginPage   |

All wrapped in `ApplicationLayout` (shell with sidebar/header).

---

## REST API Endpoints

All routes under namespace prefix: **`nitrospark/v1`** → full path: `/wp-json/nitrospark/v1/...`

### Public Routes (no auth)
| Method | Path          | Controller                          |
|--------|---------------|-------------------------------------|
| POST   | `/callback`   | `Auth\Actions@callback`             |
| GET    | `/debug`      | `Auth\Actions@debug`                |

### Protected Routes (auth via `NitroSpark\Auth@permission`)
| Method | Path                        | Controller                               |
|--------|-----------------------------|------------------------------------------|
| GET    | `/actions/posts`            | `NitroSpark\Posts@get_all`               |
| POST   | `/actions/posts`            | `NitroSpark\Posts@create`                |
| POST   | `/actions/posts/{id}`       | `NitroSpark\Posts@update`                |
| POST   | `/actions/posts/{id}/meta`  | `NitroSpark\Posts@update_meta`           |
| GET    | `/actions/media`            | `NitroSpark\Media@get_all`               |
| GET    | `/actions/media/{id}`       | `NitroSpark\Media@get_one`               |
| POST   | `/actions/media`            | `NitroSpark\Media@upload`                |
| GET    | `/actions/categories`       | `NitroSpark\Taxonomy@get_categories`     |
| GET    | `/actions/pages`            | `NitroSpark\Taxonomy@get_pages`          |
| GET    | `/actions/wc/products`      | `NitroSpark\WooCommerce@get_products`    |
| GET    | `/actions/wc/products/categories` | `NitroSpark\WooCommerce@get_product_categories` |

Routes are registered via a custom `Libs\API\Route` wrapper (fluent builder) that calls WP's `register_rest_route` under the hood. The router config is in one file: `includes/Routes/Api.php`.

---

## Auth / Connection Pattern

1. Plugin generates a random 32-char `state` token (stored as a WP transient, 10 min TTL)
2. Admin page opens an external NitroSpark app URL with `?callback_url=&site_url=&site_name=&state=`
3. NitroSpark app POSTs `{ auth_key, state }` to `/wp-json/nitrospark/v1/callback`
4. Plugin validates state token, stores `auth_key` in `wp_options` (`nitrospark_auth_key`)
5. Subsequent API calls include the `auth_key`; `Auth@permission` validates it

---

## Other Notable Patterns

- **Singleton via Trait**: All classes use `use Base;` trait which provides `get_instance()` — keeps constructors clean
- **Gutenberg Block**: Registers a block from `assets/blocks/block-1/` via `register_block_type`
- **Config file** (`config/plugin.php`): `nitrospark_debug` bool to toggle dev/prod app URLs — no env vars needed
- **Extensibility hooks**: `do_action('nitrowpinitial_api', $route)` and `apply_filters('nitrowpinitial_submenu_pages', ...)` allow third-party extension
- **WooCommerce graceful degradation**: WC routes return 503 when WooCommerce is inactive
- **Separate Vite builds**: `vite.admin.config.js` and `vite.frontend.config.js` are independent build targets
