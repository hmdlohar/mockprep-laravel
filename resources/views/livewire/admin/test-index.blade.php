<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Published Tests</h1>
            <p class="text-xs text-slate-500 mt-1">Frozen snapshot tests available for candidates to attempt.</p>
        </div>

        <a href="{{ route('admin.test-builder') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
            + New Test Blueprint
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tests as $test)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col justify-between shadow-xs hover:border-brand-300 transition">
                <div class="space-y-3">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                        {{ strtoupper($test->category->value) }}
                    </span>
                    <h3 class="text-base font-bold text-slate-900">{{ $test->title }}</h3>
                    <p class="text-xs text-slate-500">{{ $test->sections->count() }} Sections &bull; {{ $test->total_duration_minutes }} Mins</p>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                    <a href="{{ route('portal.test.instructions', ['test' => $test->slug]) }}" target="_blank" class="text-xs font-bold text-brand-600 hover:underline">Preview CBT &rarr;</a>
                    <button wire:click="deleteTest({{ $test->id }})" wire:confirm="Are you sure you want to delete this test?" class="text-xs text-rose-600 hover:text-rose-700 font-semibold">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center bg-white border border-slate-200 rounded-2xl text-slate-500">
                No tests published yet.
            </div>
        @endforelse
    </div>
</div>
