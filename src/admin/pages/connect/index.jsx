import { Button } from '@/components/ui/button';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/components/ui/card';

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
					<CardTitle className="text-2xl">Connect NitroSpark</CardTitle>
					<CardDescription>
						This plugin requires a connected NitroSpark account to function.
						Please connect your account to continue.
					</CardDescription>
				</CardHeader>
				<CardContent>
					<Button className="w-full" onClick={ handleConnect }>
						Connect NitroSpark account
					</Button>
				</CardContent>
			</Card>
		</div>
	);
}
