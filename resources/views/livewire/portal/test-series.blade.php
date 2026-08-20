<div class="space-y-12">
    <!-- Pricing Header -->
    <section class="max-w-7xl mx-auto px-6 pt-14 pb-4 text-center">
        <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Pricing</span>
        <h1 class="text-4xl sm:text-5xl font-black text-slate-950 tracking-tight mt-2">
            Test <span class="text-brand-600">Series</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto mt-4 leading-relaxed">
            Exam-like mock series with detailed analysis, mistake insights and revision guidance.
        </p>
    </section>

    <!-- Access Error Banner -->
    @if(session()->has('access_error'))
        <div class="max-w-7xl mx-auto px-6 pt-6">
            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                {{ session('access_error') }}
            </div>
        </div>
    @endif

    <!-- Payment Success Banner -->
    @if(session()->has('payment_success'))
        <div class="max-w-7xl mx-auto px-6 pt-6">
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl flex items-center gap-3">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('payment_success') }}
            </div>
        </div>
    @endif

    <!-- Package Cards -->
    <section class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($packages as $package)
                @php($owned = in_array($package->id, $ownedPackageIds))
                <div class="bg-white border border-slate-200 rounded-3xl p-8 flex flex-col justify-between shadow-xs hover:shadow-xl hover:border-purple-300 transition group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">Series</span>
                            @if($package->is_free)
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Free</span>
                            @elseif($owned)
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Owned</span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition">{{ $package->title }}</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $package->description }}</p>
                        </div>

                        <div class="space-y-1.5 pt-2 border-t border-slate-100 text-xs text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Mock tests included</span>
                                <strong class="text-slate-900">{{ $package->tests_count }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Access validity</span>
                                <strong class="text-slate-900">{{ $package->validityLabel() }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100 space-y-3">
                        <div class="text-3xl font-black text-slate-900">
                            @if($package->is_free)
                                Free
                            @else
                                &#8377;{{ number_format((float) $package->price, 0) }}
                            @endif
                        </div>

                        @if($owned)
                            <a href="{{ route('portal.dashboard') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-full shadow-md transition">
                                <span>Start Practicing</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @elseif($package->is_free)
                            <a href="{{ auth()->check() ? route('portal.dashboard') : route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 gradient-btn-primary hover:opacity-95 text-white text-xs font-bold rounded-full shadow-md shadow-purple-500/20 transition">
                                <span>Get Started</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <a href="{{ auth()->check() ? route('portal.checkout', ['package' => $package->slug]) : route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 gradient-btn-primary hover:opacity-95 text-white text-xs font-bold rounded-full shadow-md shadow-purple-500/20 transition">
                                <span>Buy Now</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-12 text-center bg-slate-50 border border-slate-200 rounded-3xl text-slate-500">
                    No test series available right now.
                </div>
            @endforelse
        </div>
    </section>
</div>
