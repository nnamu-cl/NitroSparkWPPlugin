import { Button } from '@/components/ui/button';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/components/ui/card';
import Logo from '@/components/Logo';

export default function ConnectPage() {
	const connectUrl = window.nitrospark?.connectUrl;

	function handleConnect() {
		if ( ! connectUrl ) return;
		window.open( connectUrl, '_blank' );
	}

	return (
		<div className="flex items-center justify-center " style={ {   minHeight: 'calc(100vh - 100px)' } }>
			<Card className="mx-auto max-w-sm">
				<CardHeader>
					<Logo className="mb-4 h-10 w-auto text-primary" />
					<CardTitle className="text-2xl">Connect your account</CardTitle>
					<CardDescription>
						This plugin requires a connected NitroSpark account to function.
						Please connect your account to continue.
					</CardDescription>
				</CardHeader>
				<CardContent>
					<p className="mb-4 text-sm text-muted-foreground">
						Connecting creates a dedicated &ldquo;NitroSpark&rdquo; user on this site.
						NitroSpark publishes through that user, which can only create and edit
						posts and upload media. It has no access to plugins, themes, users or
						settings, and it cannot sign in.
					</p>
					<Button className="w-full" onClick={ handleConnect }>
						Connect NitroSpark account
					</Button>
				</CardContent>
			</Card>
		</div>
	);
}
