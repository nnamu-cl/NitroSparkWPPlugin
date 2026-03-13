import { createRoot } from 'react-dom/client';
import { createHashRouter, RouterProvider } from 'react-router-dom';
import App from './App';
import Dashboard from './pages/Dashboard';

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

const root = createRoot( document.getElementById( 'nitrospark-admin' ) );
root.render( <RouterProvider router={ router } /> );
