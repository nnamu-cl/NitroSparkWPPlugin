=== NitroSpark ===
Contributors: namusanga
Tags: seo, ai, content, automation, woocommerce
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Rank higher in Google and AI search on autopilot. NitroSpark AI writes SEO content, adds internal links and publishes it to your site.

== Description ==

**Do the work of a content agency without hiring one.** NitroSpark AI researches topics for your niche, writes SEO-optimised articles with images, links them into the rest of your site and publishes them to WordPress on the schedule you choose. Set it up once and your site keeps growing while you get on with running your business.

This plugin is the bridge between your WordPress or WooCommerce site and your [NitroSpark AI](https://nitrospark.ai/) account. Install it, click **Connect**, and NitroSpark can publish straight to your site with no copy-pasting, no logins and no extra steps.

= How it works =

1. **Connect** your site from the NitroSpark menu in your WordPress dashboard. It takes about a minute.
2. **Plan** your content in NitroSpark: pick your topics and keywords, train it on your brand voice and set a publishing schedule.
3. **Grow** on autopilot. Articles, images and internal links arrive on your site live or as drafts for you to approve.

= What the plugin does on your site =

* **Publishes content automatically:** blog posts and pages arrive from NitroSpark and go live straight away, or wait as drafts for your sign-off.
* **AutoGrowth scheduling:** fresh articles appear daily, weekly or on the exact days and times you set, without you logging in.
* **Internal Link Injector:** NitroSpark finds linking opportunities across your existing posts and adds relevant internal links, helping visitors and search engines explore your site.
* **Image delivery:** AI-generated and royalty-free images go straight into your Media Library, ready to use.
* **WooCommerce product insights:** NitroSpark reads your product catalogue so it can write content that sells what you actually stock.
* **Elementor friendly:** published content works with Elementor-built themes and layouts.

= Also in your NitroSpark account =

* E-E-A-T-focused, human-sounding content trained on your own knowledge base, FAQs and tone of voice
* Custom rules for every site, plus multi-site control from one dashboard
* Social media posts for Facebook, Instagram, LinkedIn and X, and email campaigns repurposed from your articles
* Organic position tracking for your keywords
* High-authority backlinks on eligible plans

See [plans and pricing](https://nitrospark.ai/pricing/).

= Built to be safe =

NitroSpark never gets admin access to your site. Connecting creates a dedicated "NitroSpark" user with a limited **NitroSpark Publisher** role that can only write posts and upload media. It cannot touch plugins, themes, users or settings, and it cannot log in. Your WordPress password is never shared, and you can disconnect at any time.

= Requirements =

* A [NitroSpark AI](https://nitrospark.ai/) account
* WooCommerce (optional, for product insights)
* Elementor (optional)

== External services ==

This plugin connects to the NitroSpark AI platform, a third-party service operated by NitroSpark. The platform is required for every feature: content generation, scheduling and AI processing happen on NitroSpark's servers, not on your site.

Nothing is sent to NitroSpark until a site administrator clicks **Connect** on the NitroSpark admin page.

* **When you click Connect:** your browser is sent to the NitroSpark app (`app.nitrospark.ai/wordpress-connect`) with your site URL, site name, the plugin's callback URL and a one-time state token. After you approve the connection, NitroSpark sends a site-specific secret key back to your site, which the plugin stores.
* **When the NitroSpark admin page loads (once connected):** the page requests your NitroSpark account details and content library from the NitroSpark app (`app.nitrospark.ai`), authenticated with the stored key.
* **While connected:** the NitroSpark platform calls this plugin's REST endpoints (authenticated with the stored key) to read, publish and update posts, upload media to your library, read categories and pages, and, if WooCommerce is active, read your products and product categories.

Deleting the plugin removes the stored key and the NitroSpark user; that user's posts are reassigned to the administrator who connected the site. You can also revoke the connection from your NitroSpark account, after which the key stops working.

* NitroSpark AI Terms of Service: https://nitrospark.ai/terms-and-conditions/
* NitroSpark AI Privacy Policy: https://nitrospark.ai/privacy-policy/

== Installation ==

1. In your WordPress dashboard go to **Plugins → Add New**, search for **NitroSpark**, then click **Install Now** and **Activate**.
2. Open the new **NitroSpark** menu and click **Connect NitroSpark account**.
3. Sign in to NitroSpark (or create an account) and approve the connection.
4. That's it. Manage your content, schedule and AutoGrowth settings from your [NitroSpark dashboard](https://nitrospark.ai/).

== Frequently Asked Questions ==

= Do I need a NitroSpark AI account? =

Yes. The plugin connects your site to the NitroSpark AI platform, where your content is created and scheduled. See [plans and pricing](https://nitrospark.ai/pricing/).

= Can I review content before it goes live? =

Yes. Choose whether NitroSpark publishes straight to live or sends everything to drafts so you can edit and approve it first.

= Does this plugin require WooCommerce? =

No. Publishing, internal linking, images and AutoGrowth all work on any WordPress site. WooCommerce is only needed for product insights.

= Does NitroSpark work with Elementor? =

Yes. Published content is compatible with Elementor themes and layouts.

= Where is the content generated? =

On NitroSpark's servers. The plugin receives the finished content and publishes it to your site, so it adds no AI processing load to your hosting.

= Is my site safe? What can NitroSpark access? =

NitroSpark never acts as an administrator. Every request runs as a dedicated "NitroSpark" user with a custom NitroSpark Publisher role. That role can create, edit and publish posts and upload media, with read-only access to WooCommerce products and product categories, which WooCommerce's API requires. It cannot manage plugins, themes, users or settings, cannot post unfiltered HTML, and cannot sign in or use application passwords. Requests are authenticated with a site-specific secret key, and your WordPress username and password are never sent to NitroSpark.

= How do I disconnect? =

Revoke the site from your NitroSpark account, or deactivate and delete the plugin. Deleting the plugin removes the stored key and the NitroSpark user.

= Where is the source code? =

The admin screen is a React app bundled with Vite. The compiled files ship in `assets/admin/dist/`, and the human-readable source code, including build instructions, is on GitHub: https://github.com/nnamu-cl/NitroSparkWPPlugin

To build it yourself, run `npm install && npm run build`, then `composer install --no-dev`.

= Where can I get help? =

Post in the [support forum](https://wordpress.org/support/plugin/nitrospark/), contact the team at [nitrospark.ai/contact-us](https://nitrospark.ai/contact-us/) or email info@nitrospark.ai.

== Changelog ==

= 1.0.2 =
* Tweak: the plugin author now shows as NitroSpark AI, linked to nitrospark.ai, on the Plugins screen.

= 1.0.1 =
* New: NitroSpark branding in the WordPress admin, with the brand colours, logo and a rocket menu icon.
* Improved: clearer plugin description, installation steps and FAQ.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.2 =
Updates the plugin author name and link. No functional changes.

= 1.0.1 =
Refreshed NitroSpark branding in your WordPress admin and an improved plugin description.
