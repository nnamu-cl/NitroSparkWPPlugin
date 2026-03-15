import { createRoot } from 'react-dom/client';
import { createHashRouter, RouterProvider } from 'react-router-dom';
import App from './App';
import Dashboard from './pages/Dashboard';
import ConnectPage from './pages/connect';

const router = createHashRouter( [
	{
		path: '/',
		element: <App />,
		children: [
			{ index: true, element: <Dashboard /> },
			{ path: 'dashboard', element: <Dashboard /> },
		],
	},
] );

const el = document.getElementById( 'nitrospark-admin' );
const data = window.nitrospark ?? {};
const isConnected = data.isConnected === '1' || data.isConnected === true;

const root = createRoot( el );
root.render( isConnected ? <RouterProvider router={ router } /> : <ConnectPage /> );
