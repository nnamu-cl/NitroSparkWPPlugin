import {Outlet} from 'react-router-dom';
import {Button} from '@/components/ui/button';
import './app.css';
import {ArrowUpRightIcon, RefreshCwIcon} from "lucide-react";
import Logo from '@/components/Logo';

export default function App() {
    const appUrl      = window.nitrospark?.appUrl      ;
    const connectUrl  = window.nitrospark?.connectUrl  ;

    return (
        <div className="nitrospark-app p-6 max-w-6xl mx-auto">
            <header className="flex items-center justify-between gap-6 pb-4 mb-6 border-b">
                <h1 className="m-0 p-0 leading-none">
                    <Logo className="h-9 w-auto text-primary" />
                </h1>
                <nav className="flex gap-2">
                    <div className="flex items-start gap-2">
                        { connectUrl && (
                            <Button
                                variant="outline"
                                size="lg"
                                onClick={ () => window.open(connectUrl, '_blank') }
                                title="Reconnect your NitroSpark account"
                            >
                                <RefreshCwIcon className="mr-2 h-4 w-4" />
                                Reconnect
                            </Button>
                        ) }
                        <Button className="text-sm" size="lg" onClick={ () => window.open(appUrl, '_blank') }>
                            <p className="text-2xl font-black"> Go to NitroSpark </p>
                        </Button>
                        <Button size="icon-lg" aria-label="Open NitroSpark" onClick={ () => window.open(appUrl, '_blank') }>
                            <ArrowUpRightIcon/>
                        </Button>
                    </div>
                </nav>
            </header>
            <main>
                <Outlet/>
            </main>
        </div>
    );
}
