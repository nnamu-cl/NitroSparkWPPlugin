import { useState, useEffect, useMemo } from 'react';
import {Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge.jsx';
import { fetchUserData, fetchContentLibrary, PAGE_SIZE } from '@/lib/api';

// ---------------------------------------------------------------------------
// Status badge
// ---------------------------------------------------------------------------

const STATUS_STYLES = {
	published: 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
	draft:     'bg-amber-50  text-amber-700  ring-1 ring-inset ring-amber-600/20',
	scheduled: 'bg-sky-50    text-sky-700    ring-1 ring-inset ring-sky-600/20',
	failed:    'bg-red-50    text-red-700    ring-1 ring-inset ring-red-600/20',
};

function StatusBadge({ status }) {
	return (
		<Badge className={`capitalize ${STATUS_STYLES[status] ?? STATUS_STYLES.draft}`}>
			{ status }
		</Badge>
	);
}

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function formatDate(iso) {
	if (!iso) return null;
	const d = new Date(iso);
	if (isNaN(d)) return null;
	return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}

const SORT_OPTIONS = [
	{ value: 'created_desc', label: 'Newest first' },
	{ value: 'created_asc',  label: 'Oldest first' },
	{ value: 'updated_desc', label: 'Recently updated' },
	{ value: 'title_asc',    label: 'Title A–Z' },
];

const STATUSES = ['all', 'published', 'draft', 'scheduled', 'failed'];

function applyFilterAndSort(items, filterStatus, sortKey) {
	let result = filterStatus === 'all'
		? [...items]
		: items.filter((e) => e.status === filterStatus);

	result.sort((a, b) => {
		switch (sortKey) {
			case 'created_asc':
				if (!a.created_at && !b.created_at) return 0;
				if (!a.created_at) return 1;
				if (!b.created_at) return -1;
				return new Date(a.created_at) - new Date(b.created_at);
			case 'updated_desc':
				if (!a.updated_at && !b.updated_at) return 0;
				if (!a.updated_at) return 1;
				if (!b.updated_at) return -1;
				return new Date(b.updated_at) - new Date(a.updated_at);
			case 'title_asc':
				return (a.title ?? '').localeCompare(b.title ?? '');
			case 'created_desc':
			default:
				if (!a.created_at && !b.created_at) return 0;
				if (!a.created_at) return 1;
				if (!b.created_at) return -1;
				return new Date(b.created_at) - new Date(a.created_at);
		}
	});

	return result;
}

// ---------------------------------------------------------------------------
// Library card
// ---------------------------------------------------------------------------

function LibraryCard({ entry }) {
	return (
		<Card className="overflow-hidden gap-0 py-0">
			{ entry.cover_image_url && (
				<div className="h-40 overflow-hidden">
					<img
						src={ entry.cover_image_url }
						alt={ entry.title }
						className="w-full h-full object-cover"
					/>
				</div>
			) }



			<CardContent className="pb-5 space-y-3">


				<div className="pt-5 pb-3">
					<div className="flex items-start justify-between gap-3">
						<CardTitle className="text-sm leading-snug line-clamp-2">
							{ entry.title }
						</CardTitle>
						<StatusBadge status={ entry.status } />
					</div>
					<CardDescription className="line-clamp-3 mt-1">
						{ entry.description }
					</CardDescription>
				</div>



			</CardContent>

			<CardFooter className="flex flex-col w-full p-2 gap-2" >

				<div className="flex items-center gap-1.5 flex-wrap w-full">
					{ (entry.categories ?? []).map((cat, i) => (
						<span
							key={ cat.id ?? i }
							className="rounded-md bg-secondary px-2 py-0.5 text-secondary-foreground"
						>
							{ cat.name ?? cat }
						</span>
					)) }
					{ entry.word_count > 0 && (
						<span className="ml-auto text-muted-foreground">
							{ entry.word_count.toLocaleString() } words
						</span>
					) }
				</div>

				<div className=" w-full flex items-center justify-between text-muted-foreground border-t">
					<span>{ formatDate(entry.created_at) ?? '—' }</span>
					{ formatDate(entry.updated_at) && (
						<span className="text-muted-foreground/70">
							Updated { formatDate(entry.updated_at) }
						</span>
					) }
				</div>
			</CardFooter>

		</Card>
	);
}

// ---------------------------------------------------------------------------
// Pagination
// ---------------------------------------------------------------------------

function Pagination({ page, totalPages, onPrev, onNext }) {
	return (
		<div className="flex items-center justify-between pt-2">
			<Button variant="outline" size="sm" onClick={ onPrev } disabled={ page <= 1 }>
				Previous
			</Button>
			<span className="text-muted-foreground">Page { page } of { totalPages }</span>
			<Button variant="outline" size="sm" onClick={ onNext } disabled={ page >= totalPages }>
				Next
			</Button>
		</div>
	);
}

// ---------------------------------------------------------------------------
// Dashboard
// ---------------------------------------------------------------------------

export default function Dashboard() {
	const wpData = window.nitrospark || {};

	const [user,        setUser]        = useState(null);
	const [library,     setLibrary]     = useState([]);
	const [total,       setTotal]       = useState(0);
	const [page,        setPage]        = useState(1);
	const [loadingUser, setLoadingUser] = useState(true);
	const [loadingLib,  setLoadingLib]  = useState(true);
	const [error,       setError]       = useState(null);
	const [filterStatus, setFilterStatus] = useState('all');
	const [sortKey,      setSortKey]      = useState('created_desc');

	const totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));

	const visibleItems = useMemo(
		() => applyFilterAndSort(library, filterStatus, sortKey),
		[library, filterStatus, sortKey]
	);

	useEffect(() => {
		if (!wpData.isConnected) { setLoadingUser(false); return; }
		fetchUserData()
			.then(setUser)
			.catch((e) => setError(e.message))
			.finally(() => setLoadingUser(false));
	}, []);

	useEffect(() => {
		if (!wpData.isConnected) { setLoadingLib(false); return; }
		setLoadingLib(true);
		fetchContentLibrary(page)
			.then(({ items, total: t }) => { setLibrary(items); setTotal(t); })
			.catch((e) => setError(e.message))
			.finally(() => setLoadingLib(false));
	}, [page]);

	const displayName = user?.name || wpData.userInfo?.username || '';
	const initials = displayName.split(' ').slice(0, 2).map((n) => n[0] ?? '').join('').toUpperCase() || '?';

	return (
		<div className="space-y-8">

			{ error && (
				<div className="rounded-lg border border-destructive/40 bg-destructive/5 px-4 py-3 text-destructive">
					{ error }
				</div>
			) }

			{/* ── User profile ─────────────────────────────────────────────── */}
			<Card>
				<CardContent className="flex flex-row items-center gap-4">
					<div className="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-primary text-primary-foreground font-semibold">
						{ initials }
					</div>
					{ loadingUser ? (
						<div className="space-y-2">
							<div className="h-4 w-32 animate-pulse rounded bg-muted" />
							<div className="h-3 w-48 animate-pulse rounded bg-muted" />
						</div>
					) : (
						<div>
							<p className="font-semibold">{ user?.name || displayName }</p>
							<p className="text-muted-foreground">{ user?.email || '—' }</p>
						</div>
					) }
					{ wpData.isConnected && (
						<span className="ml-auto inline-flex items-center gap-1.5 text-emerald-600 text-xs font-bold">
							<span className="h-2 w-2 rounded-full bg-emerald-500" />
							Connection Active
						</span>
					) }
				</CardContent>
			</Card>

			{/* ── Content library ──────────────────────────────────────────── */}
			<section className="space-y-4">

				{/* Header row */}
				<div className="flex items-center justify-between">
					<h2 className="font-semibold">Content Library</h2>
					<span className="text-muted-foreground">
						{ total > 0 ? `${visibleItems.length} of ${total} articles` : '' }
					</span>
				</div>

				{/* Filter + sort controls */}
				<div className="flex flex-wrap items-center justify-between gap-3">
					<div className="flex flex-wrap gap-1.5">
						{ STATUSES.map((s) => (
							<Button
								key={ s }
								size="sm"
								variant={ filterStatus === s ? 'default' : 'outline' }
								className="capitalize"
								onClick={ () => setFilterStatus(s) }
							>
								{ s }
							</Button>
						)) }
					</div>

					<select
						value={ sortKey }
						onChange={ (e) => setSortKey(e.target.value) }
						className="rounded-md border bg-background px-3 py-1.5 text-sm text-foreground"
					>
						{ SORT_OPTIONS.map((o) => (
							<option key={ o.value } value={ o.value }>{ o.label }</option>
						)) }
					</select>
				</div>

				{ loadingLib ? (
					<div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
						{ Array.from({ length: PAGE_SIZE }).map((_, i) => (
							<Card key={ i } className="overflow-hidden gap-0 py-0">
								<div className="h-40 animate-pulse bg-muted" />
								<CardHeader className="pt-5 pb-3 space-y-2">
									<div className="h-4 w-3/4 animate-pulse rounded bg-muted" />
									<div className="h-3 w-full animate-pulse rounded bg-muted" />
									<div className="h-3 w-2/3 animate-pulse rounded bg-muted" />
								</CardHeader>
							</Card>
						)) }
					</div>
				) : visibleItems.length === 0 ? (
					<Card>
						<CardContent className="py-12 text-center text-muted-foreground">
							No content found.
						</CardContent>
					</Card>
				) : (
					<>
						<div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
							{ visibleItems.map((entry) => (
								<LibraryCard key={ entry.content_id } entry={ entry } />
							)) }
						</div>
						{ totalPages > 1 && (
							<Pagination
								page={ page }
								totalPages={ totalPages }
								onPrev={ () => setPage((p) => p - 1) }
								onNext={ () => setPage((p) => p + 1) }
							/>
						) }
					</>
				) }
			</section>

		</div>
	);
}
