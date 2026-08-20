<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Passages & Data Caselets</h1>
            <p class="text-xs text-slate-500 mt-1">Reading Comprehension passages and DILR datasets linked to multiple questions.</p>
        </div>

        <button wire:click="openCreateModal" wire:loading.attr="disabled" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Passage</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="flex items-center gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <select wire:model.live="sectionFilter" class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 text-xs font-semibold focus:outline-none focus:border-brand-500 focus:bg-white">
            <option value="">All Sections</option>
            <option value="va">VA (Verbal Ability & RC)</option>
            <option value="dilr">DILR (Data Interpretation & LR)</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($passages as $passage)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-xs flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $passage->section_category?->value ?? 'RC' }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">{{ $passage->questions_count }} Questions Linked</span>
                    </div>

                    <div class="text-xs text-slate-700 leading-relaxed line-clamp-6 bg-slate-50 p-4 rounded-xl border border-slate-200 prose prose-sm max-w-none">
                        {!! $passage->content !!}
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[10px] font-mono text-slate-400">Passage #{{ $passage->id }}</span>
                    <div class="flex items-center gap-3">
                        <button wire:click="openEditModal({{ $passage->id }})" wire:loading.attr="disabled" class="text-xs font-bold text-brand-600 hover:text-brand-700">Edit</button>
                        <button wire:click="deletePassage({{ $passage->id }})" wire:confirm="Are you sure you want to delete this passage?" wire:loading.attr="disabled" class="text-xs font-bold text-rose-600 hover:text-rose-700">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 p-12 text-center bg-white border border-slate-200 rounded-2xl text-slate-500">
                No passages found.
            </div>
        @endforelse
    </div>

    <div class="p-4 bg-white border border-slate-200 rounded-2xl">
        {{ $passages->links() }}
    </div>

    <!-- Create / Edit Passage Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl w-full max-w-2xl border border-slate-200 shadow-2xl p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-900">{{ $editingPassageId ? 'Edit Passage' : 'Add Passage / Dataset' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>

                <form wire:submit="save" class="space-y-4 text-xs">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Section Category</label>
                        <select wire:model="section_category" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white">
                            <option value="va">VA (Verbal Ability & Reading Comprehension)</option>
                            <option value="dilr">DILR (Data Interpretation & Logical Reasoning)</option>
                        </select>
                        @error('section_category') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Passage / Caselet HTML Content</label>
                        <textarea wire:model="content" rows="10" placeholder="Paste passage text or HTML..." class="w-full bg-slate-50 border border-slate-300 rounded-xl p-3 text-slate-900 font-mono text-xs focus:bg-white"></textarea>
                        @error('content') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-5 py-2 gradient-btn-primary text-white font-bold rounded-xl shadow-xs">Save Passage</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
