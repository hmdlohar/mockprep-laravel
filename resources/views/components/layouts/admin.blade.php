<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} - OnYourMocks</title>
    @include('components.theme-head')
    @livewireStyles
</head>
<body class="h-full flex antialiased bg-slate-100 text-slate-900 selection:bg-brand-600 selection:text-white">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 shadow-xs">
        <!-- Brand Header -->
        <div class="p-6 border-b border-slate-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center shadow-md shadow-brand-500/20">
                <span class="font-black text-xl text-white">M</span>
            </div>
            <div>
                <h1 class="font-bold text-base text-slate-900 leading-tight">On Your Mocks</h1>
                <p class="text-[11px] text-brand-600 font-bold uppercase tracking-wider">Control Center</p>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.users*') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.users*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Users
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Question Bank</p>
            </div>

            <a href="{{ route('admin.questions') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.questions*') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.questions*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Questions Pool
            </a>

            <a href="{{ route('admin.import') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.import*') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.import*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Bulk Import
            </a>

            <a href="{{ route('admin.passages') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.passages*') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.passages*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                RC & DILR Passages
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Test Engine</p>
            </div>

            <a href="{{ route('admin.tests') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.tests') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.tests') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                All Tests
            </a>

            <a href="{{ route('admin.test-builder') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.test-builder*') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.test-builder*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Test Builder Engine
            </a>

            <a href="{{ route('admin.packages') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.packages*') ? 'bg-brand-50 text-brand-700 font-bold border border-brand-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.packages*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Products & Series
            </a>
        </nav>

        <!-- User Footer -->
        <div class="p-4 border-t border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2.5 truncate">
                <div class="w-8 h-8 rounded-full bg-brand-50 border border-brand-200 flex items-center justify-center font-bold text-xs text-brand-700 shrink-0">
                    ADM
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">Administrator</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="text-slate-500 hover:text-slate-900 text-xs px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded font-medium">Exit</a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto bg-slate-50">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
