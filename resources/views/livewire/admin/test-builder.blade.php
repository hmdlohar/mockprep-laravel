<div class="p-8 space-y-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="border-b border-slate-200 pb-6">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Test Blueprint Generator</h1>
        <p class="text-xs text-slate-500 mt-1">Specify test criteria and let the engine build and lock a snapshot mock paper from the question bank.</p>
    </div>

    @if (session()->has('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <a href="{{ route('admin.tests') }}" class="underline font-bold">View in Tests List &rarr;</a>
        </div>
    @endif

    <form wire:submit="generateTest" class="space-y-6 text-xs">
        <!-- Test Meta -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-xs">
            <h2 class="text-sm font-bold text-slate-900">General Information</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Test Title</label>
                    <input type="text" wire:model="title" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-slate-900 focus:outline-none focus:border-brand-500 focus:bg-white">
                    @error('title') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Exam Category</label>
                    <select wire:model="category" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-slate-900 focus:outline-none focus:border-brand-500 focus:bg-white">
                        <option value="cat">CAT (Common Admission Test)</option>
                        <option value="cmat">CMAT</option>
                        <option value="xat">XAT</option>
                        <option value="snap">SNAP</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer text-slate-700">
                    <input type="checkbox" wire:model="has_calculator" class="rounded bg-white border-slate-300 text-brand-600 focus:ring-0">
                    <span>Enable On-Screen Scientific Calculator in CBT runner</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end pt-2">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-md shadow-brand-600/20 transition text-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                <span>Build & Publish Test Snapshot &rarr;</span>
            </button>
        </div>
    </form>
</div>
