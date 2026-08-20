<div class="space-y-24">
    <!-- Hero Section (Pixel-accurate to On Your Mocks design) -->
    <section class="max-w-7xl mx-auto px-6 pt-12 pb-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Copy -->
            <div class="lg:col-span-7 space-y-6">
                <span class="text-xs font-semibold text-slate-500 tracking-wide uppercase">
                    Built to improve your score, one mock at a time.
                </span>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-slate-950 tracking-tight leading-[1.05]">
                    Every <span class="text-accent-cyan">Mock</span><br>
                    Should Make<br>
                    You <span class="text-brand-600">Better</span><span class="text-slate-900">.</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 max-w-lg leading-relaxed font-normal">
                    Take exam-like mocks, understand mistakes, and revise what matters before your next attempt.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    @if($tests->isNotEmpty())
                        <a href="{{ route('portal.test.instructions', ['test' => $tests->first()->slug]) }}" class="px-7 py-3.5 rounded-full gradient-btn-primary text-white text-sm font-bold shadow-lg shadow-purple-500/25 hover:opacity-95 transition">
                            Take Free CAT Mock &rarr;
                        </a>
                    @else
                        <a href="#test-list" class="px-7 py-3.5 rounded-full gradient-btn-primary text-white text-sm font-bold shadow-lg shadow-purple-500/25 hover:opacity-95 transition">
                            Explore Mocks &rarr;
                        </a>
                    @endif

                    <a href="#how-it-works" class="px-7 py-3.5 rounded-full bg-white border border-slate-200 hover:border-slate-300 text-slate-800 text-sm font-bold shadow-xs transition">
                        See How It Works
                    </a>
                </div>

                <!-- Feature checklist -->
                <div class="flex flex-wrap items-center gap-6 pt-4 text-xs font-medium text-slate-500">
                    <span class="flex items-center gap-1.5">&bull; Real Exam UI</span>
                    <span class="flex items-center gap-1.5">&bull; Mistake Analysis</span>
                    <span class="flex items-center gap-1.5">&bull; Learning Journal</span>
                    <span class="flex items-center gap-1.5">&bull; Revision Plan</span>
                </div>
            </div>

            <!-- Right Hero Interactive CBT Widget Mockup -->
            <div class="lg:col-span-5 relative">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl p-6 space-y-6">
                    <!-- Top Widget Header -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider">CAT Full Test</span>
                            <h3 class="text-base font-extrabold text-slate-900">Exam Window</h3>
                        </div>
                        <div class="px-3.5 py-1.5 bg-dark-card text-white rounded-full font-mono text-xs font-bold flex items-center gap-1.5 shadow-xs">
                            <span class="text-[10px] text-slate-400">TIMER</span>
                            <span class="text-amber-400">01:59:42</span>
                        </div>
                    </div>

                    <!-- Section Tabs & Palette Bar Mockup -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full bg-brand-600 text-white text-xs font-bold shadow-xs">VARC</span>
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">DILR</span>
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">QA</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="h-2 flex-1 rounded-full bg-purple-500"></div>
                            <div class="h-2 flex-1 rounded-full bg-accent-cyan"></div>
                            <div class="h-2 flex-1 rounded-full bg-emerald-500"></div>
                            <div class="h-2 flex-1 rounded-full bg-rose-500"></div>
                            <div class="h-2 flex-1 rounded-full bg-slate-200"></div>
                            <div class="h-2 flex-1 rounded-full bg-slate-200"></div>
                        </div>
                    </div>

                    <!-- Down Arrow -->
                    <div class="flex justify-center text-purple-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>

                    <!-- Avoidable Mistakes Widget -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2.5">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-slate-800">Avoidable Mistakes</span>
                            <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-600 text-[10px]">12 detected</span>
                        </div>
                        <div class="space-y-1.5 text-xs text-slate-600">
                            <div class="flex justify-between"><span>Concept Errors</span><strong class="text-slate-900">4</strong></div>
                            <div class="flex justify-between"><span>Calculation Errors</span><strong class="text-slate-900">5</strong></div>
                            <div class="flex justify-between"><span>Reading Errors</span><strong class="text-slate-900">3</strong></div>
                        </div>
                    </div>

                    <!-- Down Arrow -->
                    <div class="flex justify-center text-purple-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>

                    <!-- Dark Revision Card -->
                    <div class="bg-dark-card text-white rounded-2xl p-5 flex items-center justify-between shadow-xl">
                        <div>
                            <p class="text-[10px] font-bold text-accent-cyan uppercase tracking-wider">Today's Revision</p>
                            <h4 class="text-base font-extrabold text-white">Arithmetic</h4>
                            <p class="text-xs text-slate-400 mt-0.5">20 min &bull; calculation accuracy</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-white text-slate-900 flex items-center justify-center font-black shadow-md">
                            &rarr;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section ("Mocks alone don't improve your score") -->
    <section id="how-it-works" class="max-w-7xl mx-auto px-6">
        <div class="text-center space-y-3 mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-950">
                <span class="text-accent-cyan">Mocks</span> alone <span class="text-brand-600">don't improve</span> your score.
            </h2>
            <p class="text-sm text-slate-500 max-w-lg mx-auto">
                Discover the difference between blind test taking and deliberate AI-guided improvement.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch relative">
            <!-- Old Way -->
            <div class="bg-gradient-to-br from-rose-50/50 via-white to-white border border-rose-100 rounded-3xl p-8 space-y-4 relative shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Old Way</h3>
                        <p class="text-xs text-slate-500">A loop that keeps repeating.</p>
                    </div>
                    <span class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs font-bold">&searr;</span>
                </div>

                <div class="space-y-2 pt-2 text-xs font-medium text-slate-700">
                    <div class="p-3 bg-white rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-[10px]">1</span> Take a mock</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-[10px]">2</span> Check the score</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-[10px]">3</span> Feel disappointed</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-[10px]">4</span> Move on</div>
                    <div class="p-3 bg-white rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-[10px]">5</span> Repeat the same mistakes</div>
                </div>

                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200/60 text-center text-xs font-bold text-rose-700">
                    Result: Same effort. Same mistakes. Same score.
                </div>
            </div>

            <!-- On Your Mocks Way -->
            <div class="bg-white border-2 border-brand-500/30 rounded-3xl p-8 space-y-4 relative shadow-xl shadow-purple-500/5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-brand-600">On Your Mocks Way</h3>
                        <p class="text-xs text-slate-500">A progression that moves you forward.</p>
                    </div>
                    <span class="w-7 h-7 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold">&nearr;</span>
                </div>

                <div class="space-y-2 pt-2 text-xs font-medium text-slate-800">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-[10px]">1</span> Take a mock</div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-[10px]">2</span> Get detailed diagnostic analysis</div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-[10px]">3</span> See mistake highlighters</div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-[10px]">4</span> Know focus areas</div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-5 h-5 rounded-full bg-brand-600 text-white font-bold flex items-center justify-center text-[10px]">5</span> Track continuous improvement</div>
                </div>

                <div class="p-3.5 rounded-xl bg-dark-card text-white text-center text-xs font-bold shadow-md">
                    Result: Every mock gives you something to fix.
                </div>
            </div>
        </div>

        <!-- 4 Feature Pills -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">✓</div>
                <span class="text-xs font-bold text-slate-800">Smarter Practice</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-50 text-accent-blue flex items-center justify-center font-bold text-xs">✓</div>
                <span class="text-xs font-bold text-slate-800">Data-backed Insights</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-xs">✓</div>
                <span class="text-xs font-bold text-slate-800">Personalized Plans</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs">✓</div>
                <span class="text-xs font-bold text-slate-800">Better Results</span>
            </div>
        </div>
    </section>

    <!-- Available Tests Section -->
    <section id="test-list" class="max-w-7xl mx-auto px-6 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Mock Papers</span>
                <h2 class="text-2xl font-black text-slate-900 mt-1">Available Mock Tests</h2>
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="$set('categoryFilter', '')" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $categoryFilter === '' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">All Exams</button>
                <button wire:click="$set('categoryFilter', 'cat')" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $categoryFilter === 'cat' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">CAT</button>
                <button wire:click="$set('categoryFilter', 'cmat')" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $categoryFilter === 'cmat' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100' }}">CMAT</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tests as $test)
                <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col justify-between shadow-xs hover:shadow-xl hover:border-purple-300 transition group">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-purple-50 text-purple-700 border border-purple-200">
                                {{ strtoupper($test->category->value) }}
                            </span>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                Free Access
                            </span>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition">{{ $test->title }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Sectional timed pattern &bull; {{ $test->has_calculator ? 'Calculator Enabled' : 'No Calculator' }}</p>
                        </div>

                        <!-- Section list -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-100">
                            @foreach($test->sections as $sec)
                                <div class="flex items-center justify-between text-xs text-slate-600">
                                    <span class="font-medium">{{ $sec->name }}</span>
                                    <span class="text-slate-800 font-bold">{{ $sec->questions->count() }} Qs &bull; {{ $sec->duration_minutes }}m</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 mt-6 flex items-center justify-between">
                        <div class="text-xs text-slate-500 font-medium">
                            <strong class="text-slate-900 font-bold">{{ $test->total_duration_minutes }} Mins</strong> total
                        </div>
                        <a href="{{ route('portal.test.instructions', ['test' => $test->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 gradient-btn-primary hover:opacity-95 text-white text-xs font-bold rounded-full shadow-md shadow-purple-500/20 transition">
                            <span>Attempt Test</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-12 text-center bg-slate-50 border border-slate-200 rounded-3xl text-slate-500">
                    No published tests available right now.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Take Your First Free Mock Dark Banner (Matching Screenshot) -->
    <section class="max-w-7xl mx-auto px-6">
        <div class="bg-dark-card rounded-3xl p-8 sm:p-12 text-white shadow-2xl border border-slate-800 flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="space-y-4 max-w-md text-center lg:text-left">
                <h3 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Take your first free mock.</h3>
                <p class="text-xs text-slate-400 leading-relaxed">See the full flow: mock, analysis, notes, and revision.</p>
                @if($tests->isNotEmpty())
                    <a href="{{ route('portal.test.instructions', ['test' => $tests->first()->slug]) }}" class="inline-block px-7 py-3 bg-white text-slate-900 hover:bg-slate-100 font-bold text-xs rounded-xl shadow-md transition mt-2">
                        Take Free CAT Mock
                    </a>
                @endif
            </div>

            <!-- FAQ Pills Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 w-full lg:max-w-xl text-xs font-medium">
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-300 text-center sm:text-left">
                    Is On Your Mocks only for CAT?
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-300 text-center sm:text-left">
                    Why analyse mocks?
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-300 text-center sm:text-left">
                    Can I try it free?
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-300 text-center sm:text-left">
                    Will I get solutions?
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-300 text-center sm:text-left">
                    What is the journal for?
                </div>
                <div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800 text-slate-300 text-center sm:text-left">
                    Is this beginner friendly?
                </div>
            </div>
        </div>
    </section>
</div>
