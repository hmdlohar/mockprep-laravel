<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white text-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'On Your Mocks' }} - Every Mock Should Make You Better</title>
    @include('components.theme-head')
    @livewireStyles
</head>
<body class="min-h-full flex flex-col antialiased bg-white text-slate-900 selection:bg-purple-500 selection:text-white">
    <!-- Navbar (Exact On Your Mocks Header) -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-100 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ auth()->check() ? auth()->user()->homeUrl() : route('portal.series') }}" class="flex items-center gap-2">
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tight leading-none text-brand-600">
                        On Your
                    </span>
                    <span class="text-2xl font-black tracking-tight leading-none text-accent-cyan">
                        Mocks
                    </span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-700">
                @auth
                    <a href="{{ route('portal.dashboard') }}" class="hover:text-brand-600 transition">Dashboard</a>
                @endauth
                <a href="{{ route('portal.series') }}" class="hover:text-brand-600 transition">Test Series</a>
            </nav>

            <!-- Actions / Auth -->
            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-full bg-slate-950 hover:bg-slate-800 text-white text-xs font-bold transition shadow-xs">
                            Admin Panel
                        </a>
                    @endif

                    <div class="flex items-center gap-3 pl-2">
                        <a href="{{ route('portal.profile') }}" title="My Profile" class="hover:opacity-80 transition">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" class="w-8 h-8 rounded-full border border-slate-200 object-cover" alt="{{ auth()->user()->name }}">
                            @else
                                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-slate-200 hover:border-slate-300 text-slate-800 text-xs font-bold transition">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2 rounded-full gradient-btn-primary text-white text-xs font-bold shadow-md shadow-purple-500/20 hover:opacity-95 transition">
                        Take Free Mock &rarr;
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Body Slot -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer (Matching On Your Mocks Dark Footer) -->
    <footer class="bg-dark-footer text-white py-14 px-6 border-t border-slate-900 mt-20">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-1 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-1">
                    <span class="text-xl font-black text-white">On Your</span>
                    <span class="text-xl font-black text-accent-cyan">Mocks</span>
                </div>
                <p class="text-xs text-slate-400">Every mock should make you better.</p>
            </div>

            <div class="flex items-center gap-6 text-xs text-slate-400 font-medium">
                <a href="{{ route('portal.series') }}" class="hover:text-white transition">Test Series</a>
                <a href="#blogs" class="hover:text-white transition">Blogs</a>
                <a href="#about" class="hover:text-white transition">About</a>
                <a href="#contact" class="hover:text-white transition">Contact</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
    @livewireScripts
</body>
</html>
