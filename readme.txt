=== NitroSpark – Automated SEO and Content Marketing ===
Contributors: namusanga
Tags: seo, ai, content, automation, woocommerce
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automate your WordPress content publishing, internal linking, SEO optimization, and WooCommerce product analysis with NitroSpark AI.

== Description ==

NitroSpark AI automatically generates SEO-optimized content, publishes it directly to your WordPress site, and manages internal linking — all on autopilot.

This plugin connects your WordPress or WooCommerce site to your NitroSpark AI account, enabling the platform to publish content, inject internal links, manage images, and analyze your store's products without manual intervention.

**Key Features:**

* **Automated content publishing** — receives AI-generated blog posts and pages from your NitroSpark AI account and publishes them live or as drafts for review
* **AutoGrowth scheduled publishing** — content is written and published on the schedule you configure in your NitroSpark dashboard
* **Internal Link Injector** — automatically strengthens your site structure by adding relevant internal links to your content
* **Image delivery** — AI-generated and royalty-free images are uploaded straight into your media library
* **WooCommerce product analysis** — lets NitroSpark analyze your product catalog for market research and content targeting (requires WooCommerce)
* **Elementor support** — publishes themed content compatible with Elementor layouts

**Requirements:**

* A valid [NitroSpark AI](https://app.nitrospark.ai) account (paid plans with free trial available)
* WooCommerce (optional — required only for product analysis and market research features)
* Elementor (optional — for themed content support)

**How authentication works:**

You connect your site from the NitroSpark admin page in your WordPress dashboard. This generates a one-time connection link; after you approve the connection in your NitroSpark account, NitroSpark issues a secret key for your site. Every subsequent request from the NitroSpark platform to your site must carry this key and is executed with the permissions of the administrator who connected the site. Your WordPress username and password are never transmitted to or stored by NitroSpark AI.

**Third-Party Service Disclosure:**

This plugin connects your WordPress site to the NitroSpark AI platform ([https://app.nitrospark.ai](https://app.nitrospark.ai)). All content generation, scheduling, and AI processing occurs on NitroSpark's servers. The plugin transmits site data (site URL, site name, published content, media, and — with WooCommerce — product catalog data) and an authentication key to NitroSpark to enable automated publishing and analysis.

* [NitroSpark AI Privacy Policy](https://nitrospark.ai/privacy-policy)
* [NitroSpark AI Terms of Service](https://nitrospark.ai/terms)

== Source Code & Build Instructions ==

The admin interface of this plugin is a React application bundled with Vite. The compiled files live in `assets/admin/dist/`; the human-readable source code is in the `src/` directory of the public repository:

https://github.com/nnamu-cl/NitroSparkWPPlugin

To build from source:

1. `git clone https://github.com/nnamu-cl/NitroSparkWPPlugin`
2. `npm install`
3. `npm run build` — compiles `src/admin/` into `assets/admin/dist/`
4. `composer install --no-dev` — installs the PHP dependencies (vite-for-wp asset loader)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/nitrospark` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Open the NitroSpark menu in your WordPress admin and click Connect to link your site to your NitroSpark AI account.
4. Once connected, content publishing and automation features are controlled from your NitroSpark AI dashboard at [app.nitrospark.ai](https://app.nitrospark.ai).

== Frequently Asked Questions ==

= Do I need a NitroSpark AI account to use this plugin? =
Yes. The plugin connects your WordPress site to the NitroSpark AI platform. You need an active NitroSpark AI membership to use any features. A free trial is available at [nitrospark.ai](https://nitrospark.ai/pricing/).

= Does this plugin require WooCommerce? =
No. The core features (content publishing, internal linking, image management, AutoGrowth) work without WooCommerce. WooCommerce is only required for product analysis and market research features.

= Does NitroSpark work with Elementor? =
Yes. The plugin supports Elementor for themed content publishing.

= Where is the content generated? =
All content generation and AI processing happens on NitroSpark's servers. The plugin receives the finished content and publishes it to your WordPress site.

= Can I review content before it goes live? =
Yes. You can configure NitroSpark to publish directly to live or send content to drafts for review and approval before publishing.

= Are my WordPress credentials shared with NitroSpark? =
No. Your WordPress username and password never leave your site. The connection uses a site-specific secret key issued during the connect flow, which you can revoke at any time by disconnecting the site.

= Where can I get support? =
Contact the NitroSpark team at [nitrospark.ai/contact-us](https://nitrospark.ai/contact-us/) or email info@nitrospark.ai.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release of the plugin.
