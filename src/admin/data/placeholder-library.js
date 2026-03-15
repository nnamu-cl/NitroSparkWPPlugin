/**
 * Placeholder data — replace with real API calls once endpoints are live.
 *
 * @type {import('@/types/content-library').UserProfile}
 */
export const PLACEHOLDER_USER = {
	user_id: 'usr_placeholder_001',
	name: 'Alex Johnson',
	email: 'alex.johnson@example.com',
	avatar: null,
};

/**
 * @type {import('@/types/content-library').ContentLibraryEntry[]}
 */
export const PLACEHOLDER_LIBRARY = [
	{
		user_id: 'usr_placeholder_001',
		title: '10 Proven SEO Strategies to Skyrocket Your Organic Traffic in 2025',
		description:
			'A deep-dive into the SEO tactics that are still moving the needle this year — covering Core Web Vitals, topical authority, and AI-assisted content clusters.',
		content: '<p>Full article body goes here…</p>',
		key_points: [
			'Topical authority beats keyword stuffing every time',
			'Core Web Vitals directly impact ranking potential',
			'Internal linking is still massively underutilised',
			'AI-generated drafts need a human editorial layer',
		],
		domain: 'myblog.example.com',
		status: 'published',
		cover_image_url: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80',
		categories: [{ id: 3, name: 'SEO' }, { id: 7, name: 'Marketing' }],
	},
	{
		user_id: 'usr_placeholder_001',
		title: 'The Complete Guide to WooCommerce Checkout Optimisation',
		description:
			'How to cut cart abandonment by up to 30 % with targeted UX improvements, trust signals, and smart upsell placement at checkout.',
		content: '<p>Full article body goes here…</p>',
		key_points: [
			'One-page checkout reduces friction significantly',
			'Trust badges near the CTA increase conversions',
			'Order bumps can add 15–20 % to average order value',
			'Guest checkout must always be an option',
		],
		domain: 'shop.example.com',
		status: 'draft',
		cover_image_url: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&q=80',
		categories: [{ id: 5, name: 'eCommerce' }, { id: 9, name: 'Conversion' }],
	},
	{
		user_id: 'usr_placeholder_001',
		title: 'WordPress Performance: From 40 to 98 PageSpeed Score Without a CDN',
		description:
			'Step-by-step walkthrough of the exact server and plugin configuration changes that pushed a mid-traffic WordPress site into the green on all Core Web Vitals.',
		content: '<p>Full article body goes here…</p>',
		key_points: [
			'Object caching (Redis/Memcached) is non-negotiable at scale',
			'Eliminate render-blocking resources in the <head>',
			'Image optimisation alone can save 50–70 % of page weight',
			'Critical CSS inlining fixes LCP on most themes',
		],
		domain: 'techblog.example.com',
		status: 'scheduled',
		cover_image_url: 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&q=80',
		categories: [{ id: 2, name: 'WordPress' }, { id: 11, name: 'Performance' }],
	},
];
