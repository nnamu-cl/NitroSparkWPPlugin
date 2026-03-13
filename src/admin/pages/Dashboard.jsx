export default function Dashboard() {
	const data = window.nitrospark || {};

	return (
		<div className="nitrospark-dashboard">
			<h2>Dashboard</h2>
			<p>Welcome to NitroSpark. The plugin is active and running.</p>
			{ data.userInfo && (
				<p>Logged in as: <strong>{ data.userInfo.username }</strong></p>
			) }
		</div>
	);
}
