import path from 'path';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import { v4wp } from '@kucrut/vite-for-wp';
import { wp_scripts } from '@kucrut/vite-for-wp/plugins';

export default defineConfig( {
	plugins: [
		v4wp( {
			input: 'src/admin/main.jsx',
			outDir: 'assets/admin/dist',
		} ),
		// Load React, ReactDOM and the JSX runtime from WordPress instead of
		// bundling them, so the plugin always uses the React version core ships.
		wp_scripts(),
		react( {
			jsxRuntime: 'automatic',
		} ),
		tailwindcss(),
	],
	resolve: {
		alias: {
			'@': path.resolve( __dirname, './src/admin' ),
		},
	},
} );
