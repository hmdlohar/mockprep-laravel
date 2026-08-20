<div class="space-y-12">
    <!-- Welcome Header -->
    <section class="max-w-7xl mx-auto px-6 pt-10">
        <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Dashboard</span>
                <h1 class="text-3xl font-black text-slate-950 mt-1">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}.</h1>
                <p class="text-sm text-slate-500 mt-1.5">Pick up where you left off or start a new mock.</p>
            </div>

            <!-- Quick Stats -->
            <div class="flex items-center gap-4">
                <div class="px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 text-center min-w-[90px]">
                    <div class="text-2xl font-black text-slate-900">{{ $stats['mocks_taken'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">Mocks Taken</div>
                </div>
                <div class="px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 text-center min-w-[90px]">
                    <div class="text-2xl font-black text-slate-900">{{ $stats['series_owned'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">Series Owned</div>
                </div>
                <div class="px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 text-center min-w-[90px]">
                    <div class="text-2xl font-black text-slate-900">{{ $stats['tests_available'] }}</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">Tests Live</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Tests -->
    <section id="test-list" class="max-w-7xl mx-auto px-6 space-y-8 scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Mock Papers</span>
                <h2 class="text-2xl font-black text-slate-900 mt-1">Available Mock Tests</h2>
            </div>

            <div class="flex items-center gap-2" wire:loading.class="opacity-50" wire:target="categoryFilter">
                <button wire:click="$set('categoryFilter', '')" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $categoryFilter === '' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">All Exams</button>
                <button wire:click="$set('categoryFilter', 'cat')" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $categoryFilter === 'cat' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">CAT</button>
                <button wire:click="$set('categoryFilter', 'cmat')" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $categoryFilter === 'cmat' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">CMAT</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tests as $test)
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-xs hover:shadow-xl hover:border-purple-300 transition group">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-purple-50 text-purple-700 border border-purple-200">
                                {{ strtoupper($test->category->value) }}
                            </span>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                Free Access
                            </span>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition">{{ $test->title }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Sectional timed pattern &bull; {{ $test->has_calculator ? 'Calculator Enabled' : 'No Calculator' }}</p>
                        </div>

                        <!-- Section list -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                            @foreach($test->sections as $sec)
                                <div class="flex items-center justify-between text-xs text-slate-600">
                                    <span class="font-medium">{{ $sec->name }}</span>
                                    <span class="text-slate-800 font-bold">{{ $sec->questions->count() }} Qs &bull; {{ $sec->duration_minutes }}m</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 mt-6 flex items-center justify-between">
                        <div class="text-xs text-slate-500 font-medium">
                            <strong class="text-slate-900 font-bold">{{ $test->total_duration_minutes }} Mins</strong> total
                        </div>
                        <a href="{{ route('portal.test.instructions', ['test' => $test->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 gradient-btn-primary hover:opacity-95 text-white text-xs font-bold rounded-full shadow-md shadow-purple-500/20 transition">
                            <span>Attempt Test</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-12 text-center bg-slate-50 border border-slate-200 rounded-3xl text-slate-500">
                    No published tests available right now.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Recent Attempts -->
    @if($recentAttempts->isNotEmpty())
        <section class="max-w-7xl mx-auto px-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">History</span>
                <h2 class="text-2xl font-black text-slate-900 mt-1">Recent Attempts</h2>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl shadow-xs divide-y divide-slate-100">
                @foreach($recentAttempts as $attempt)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $attempt->test?->title ?? 'Deleted Test' }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $attempt->submitted_at?->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-sm font-black text-slate-900">{{ $attempt->total_score ?? '0' }} <span class="text-xs font-medium text-slate-500">marks</span></div>
                            <a href="{{ route('portal.test.result', ['attempt' => $attempt->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-full transition">
                                View Result
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
