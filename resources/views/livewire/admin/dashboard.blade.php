<div class="p-8 space-y-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Platform Overview</h1>
            <p class="text-xs text-slate-500 mt-1">Real-time status of question pools, blueprint test generations, and student attempts.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.test-builder') }}" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Launch Test Builder</span>
            </a>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Question Lake</p>
                <span class="p-2 rounded-xl bg-brand-50 text-brand-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $totalQuestions }}</h3>
            <p class="text-[11px] text-slate-500">Categorized across topics & passages</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">RC & Sets</p>
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $totalPassages }}</h3>
            <p class="text-[11px] text-slate-500">Decoupled Reading Comprehension sets</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Mocks</p>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $totalTests }}</h3>
            <p class="text-[11px] text-slate-500">CAT & CMAT standard snapshots</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Exam Attempts</p>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <h3 class="text-3xl font-black text-slate-900">{{ $totalAttempts }}</h3>
            <p class="text-[11px] text-slate-500">Student sessions taken</p>
        </div>
    </div>
</div>
