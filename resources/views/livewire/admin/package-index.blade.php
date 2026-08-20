<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Test Series & Packages</h1>
            <p class="text-xs text-slate-500 mt-1">Manage bundled mock packages and series offerings.</p>
        </div>

        <button wire:click="openCreateModal" wire:loading.attr="disabled" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create Package</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($packages as $pkg)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-xs flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        @if($pkg->is_free)
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                FREE
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                                PRO SERIES
                            </span>
                        @endif
                        <span class="font-bold text-slate-900 text-sm">
                            {{ $pkg->is_free ? '₹0' : '₹' . number_format((float)$pkg->price, 0) }}
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">{{ $pkg->title }}</h3>
                    @if($pkg->description)
                        <p class="text-xs text-slate-600 line-clamp-2">{{ $pkg->description }}</p>
                    @endif
                    <div class="pt-2 text-xs text-slate-500 flex items-center gap-2">
                        <span class="font-bold text-slate-700">{{ $pkg->tests_count }}</span> Mock Tests Included &bull;
                        <span class="font-bold text-slate-700">{{ $pkg->users_count }}</span> Enrolled
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <button wire:click="openEditModal({{ $pkg->id }})" wire:loading.attr="disabled" class="text-xs font-bold text-brand-600 hover:text-brand-700">Edit</button>
                    <button wire:click="deletePackage({{ $pkg->id }})" wire:confirm="Are you sure you want to delete this package?" wire:loading.attr="disabled" class="text-xs font-bold text-rose-600 hover:text-rose-700">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-12 text-center bg-white border border-slate-200 rounded-2xl text-slate-500">
                No packages created yet.
            </div>
        @endforelse
    </div>

    <!-- Create / Edit Package Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl w-full max-w-lg border border-slate-200 shadow-2xl p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-900">{{ $editingPackageId ? 'Edit Package' : 'Create Test Series Package' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>

                <form wire:submit="save" class="space-y-4 text-xs">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Package Title</label>
                        <input type="text" wire:model="title" placeholder="e.g. CAT 2026 Ultimate Mock Series" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white">
                        @error('title') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Description</label>
                        <textarea wire:model="description" rows="2" placeholder="Brief description of the package..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 focus:bg-white"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Price (₹)</label>
                            <input type="number" step="0.01" wire:model="price" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white">
                        </div>
                        <div class="pt-4 space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.live="is_free" class="rounded border-slate-300 text-brand-600 focus:ring-0">
                                <span class="font-bold text-slate-700">Free Access</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_published" class="rounded border-slate-300 text-brand-600 focus:ring-0">
                                <span class="font-bold text-slate-700">Published</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Select Included Mock Tests</label>
                        <div class="max-h-40 overflow-y-auto space-y-1.5 p-2 bg-slate-50 rounded-xl border border-slate-200">
                            @forelse($availableTests as $test)
                                <label class="flex items-center gap-2 p-1.5 hover:bg-white rounded-lg cursor-pointer transition">
                                    <input type="checkbox" wire:model="selectedTests" value="{{ $test->id }}" class="rounded border-slate-300 text-brand-600 focus:ring-0">
                                    <span class="text-slate-800 font-medium truncate">{{ $test->title }}</span>
                                </label>
                            @empty
                                <p class="text-slate-400 text-center py-2">No tests created yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" class="px-5 py-2 gradient-btn-primary text-white font-bold rounded-xl shadow-xs">Save Package</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
