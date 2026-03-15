import {Outlet} from 'react-router-dom';
import {Button} from '@/components/ui/button';
import './app.css';
import {ArrowUpRightIcon} from "lucide-react";

export default function App() {
    return (
        <div className="nitrospark-app p-6 max-w-6xl mx-auto">
            <header className="flex items-center justify-between gap-6 pb-4 mb-6 border-b">
                <h1 className="text-2xl font-bold tracking-tight">NitroSpark</h1>
                <nav className="flex gap-2">
                    <div className="flex items-start gap-2">
                        <Button className="text-sm" size="lg" onClick={() => window.open('http://app.nitrospark.ai/', '_blank')}>
                            <p className="text-2xl font-black"> Go to NitroSpark </p>
                        </Button>
                        <Button size="icon-lg" aria-label="Submit" onClick={() => window.open('http://app.nitrospark.ai/', '_blank')}>
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
