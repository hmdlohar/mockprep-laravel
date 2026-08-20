<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Test Series & Packages</h1>
            <p class="text-xs text-slate-500 mt-1">Manage bundled mock packages and series offerings.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($packages as $pkg)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                        {{ strtoupper($pkg->category->value) }}
                    </span>
                    <span class="font-bold text-slate-900 text-sm">&#8377;{{ number_format((float)$pkg->price, 0) }}</span>
                </div>
                <h3 class="text-base font-bold text-slate-900">{{ $pkg->title }}</h3>
                <p class="text-xs text-slate-500">{{ $pkg->tests_count }} Mock Tests Included &bull; {{ $pkg->validity_days }} Days Validity</p>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center bg-white border border-slate-200 rounded-2xl text-slate-500">
                No packages created yet.
            </div>
        @endforelse
    </div>
</div>
