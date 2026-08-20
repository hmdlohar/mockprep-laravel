<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Question Lake</h1>
            <p class="text-xs text-slate-500 mt-1">Pool of questions categorized by section, topic tags, difficulty, and passage sets.</p>
        </div>

        <button wire:click="openCreateModal" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Question</span>
        </button>
    </div>

    <!-- Filters Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search questions..." class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white">

        <select wire:model.live="sectionFilter" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 text-xs focus:outline-none focus:border-brand-500 focus:bg-white">
            <option value="">All Section Categories</option>
            <option value="va">VA (Verbal)</option>
            <option value="dilr">DILR (Data & Reasoning)</option>
            <option value="qa">QA (Quant)</option>
        </select>

        <select wire:model.live="typeFilter" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 text-xs focus:outline-none focus:border-brand-500 focus:bg-white">
            <option value="">All Question Types</option>
            <option value="mcq">MCQ (Multiple Choice)</option>
            <option value="tita">TITA (Type In The Answer)</option>
        </select>

        <select wire:model.live="sourceFilter" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 text-xs focus:outline-none focus:border-brand-500 focus:bg-white">
            <option value="">All Import Sources</option>
            @foreach($sources as $src)
                <option value="{{ $src }}">{{ $src }}</option>
            @endforeach
        </select>
    </div>

    <!-- Questions Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-600 bg-slate-50 border-b border-slate-200 uppercase font-semibold">
                    <th class="p-4">ID</th>
                    <th class="p-4">Content</th>
                    <th class="p-4">Section / Category</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Difficulty</th>
                    <th class="p-4">Source / Ext ID</th>
                    <th class="p-4">Passage</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($questions as $q)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-mono font-bold text-slate-500">#{{ $q->id }}</td>
                        <td class="p-4 max-w-md">
                            <div class="line-clamp-2 text-slate-800 font-medium">
                                {{ strip_tags($q->content) }}
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded uppercase text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $q->section_category->value }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="uppercase text-[10px] font-semibold text-slate-600">{{ $q->type->value }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-amber-600 font-bold">Lvl {{ $q->difficulty }}/5</span>
                        </td>
                        <td class="p-4">
                            @if($q->source)
                                <div class="space-y-0.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700 block truncate max-w-xs" title="{{ $q->source }}">{{ $q->source }}</span>
                                    @if($q->external_id)
                                        <span class="text-[10px] text-slate-400 font-mono">ID: {{ $q->external_id }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 text-[10px]">Manual</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-500">
                            {{ $q->passage ? 'Linked #' . $q->passage->id : 'None' }}
                        </td>
                        <td class="p-4 text-right">
                            <button wire:click="deleteQuestion({{ $q->id }})" wire:confirm="Are you sure you want to delete this question?" class="text-rose-600 hover:text-rose-700 font-semibold text-xs">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-500">No questions found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $questions->links() }}
        </div>
    </div>
</div>
