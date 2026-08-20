<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Passages & Data Caselets</h1>
            <p class="text-xs text-slate-500 mt-1">Reading Comprehension passages and DILR datasets linked to multiple questions.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($passages as $passage)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                        {{ $passage->category->value }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500">{{ $passage->questions_count }} Questions Linked</span>
                </div>

                <div class="text-xs text-slate-700 leading-relaxed line-clamp-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    {!! $passage->content !!}
                </div>
            </div>
        @empty
            <div class="col-span-2 p-12 text-center bg-white border border-slate-200 rounded-2xl text-slate-500">
                No passages found.
            </div>
        @endforelse
    </div>
</div>
