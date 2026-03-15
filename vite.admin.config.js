import path from 'path';
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import { v4wp } from '@kucrut/vite-for-wp';

export default defineConfig( {
	plugins: [
		v4wp( {
			input: 'src/admin/main.jsx',
			outDir: 'assets/admin/dist',
		} ),
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
