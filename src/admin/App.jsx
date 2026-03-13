import { Outlet, NavLink } from 'react-router-dom';
import './app.css';

export default function App() {
	return (
		<div className="nitrospark-wrap">
			<header className="nitrospark-header">
				<h1>NitroSpark</h1>
				<nav className="nitrospark-nav">
					<NavLink to="/dashboard">Dashboard</NavLink>
				</nav>
			</header>
			<main className="nitrospark-main">
				<Outlet />
			</main>
		</div>
	);
}
