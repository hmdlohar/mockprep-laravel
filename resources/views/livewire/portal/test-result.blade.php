<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                Scorecard & Comprehensive Analysis
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-2">{{ $attempt->test->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Submitted on {{ $attempt->submitted_at?->format('d M Y, h:i A') ?? 'Just now' }}</p>
        </div>

        <a href="{{ route('portal.catalog') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl shadow-xs transition">
            &larr; Back to Test Catalog
        </a>
    </div>

    <!-- Overview Score Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Score -->
        <div class="p-6 rounded-2xl bg-gradient-to-br from-indigo-50 to-white border border-brand-200 shadow-xs relative overflow-hidden">
            <p class="text-xs font-bold text-brand-700 uppercase tracking-wider">Overall Score</p>
            <h3 class="text-4xl font-black text-slate-900 mt-2">{{ number_format((float)$attempt->total_score, 2) }}</h3>
            <p class="text-xs text-slate-500 mt-2">Net Marks obtained</p>
        </div>

        <!-- Accuracy -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Accuracy</p>
            <h3 class="text-4xl font-black text-indigo-600 mt-2">{{ $accuracy }}%</h3>
            <p class="text-xs text-slate-500 mt-2">{{ $correct }} correct out of {{ $correct + $incorrect }} attempted</p>
        </div>

        <!-- Correct vs Incorrect -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Correct Answers</p>
            <h3 class="text-4xl font-black text-emerald-600 mt-2">{{ $correct }}</h3>
            <p class="text-xs text-rose-600 mt-2">{{ $incorrect }} Incorrect (-ve applied)</p>
        </div>

        <!-- Unattempted -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Unattempted</p>
            <h3 class="text-4xl font-black text-slate-700 mt-2">{{ $unattempted }}</h3>
            <p class="text-xs text-slate-400 mt-2">out of {{ $totalQuestions }} total questions</p>
        </div>
    </div>

    <!-- Section-wise Performance Breakdown -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900">Sectional Performance Matrix</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-600 uppercase bg-slate-50 border-b border-slate-200">
                        <th class="p-3.5">Section</th>
                        <th class="p-3.5">Total Qs</th>
                        <th class="p-3.5">Correct</th>
                        <th class="p-3.5">Incorrect</th>
                        <th class="p-3.5">Unattempted</th>
                        <th class="p-3.5">Accuracy</th>
                        <th class="p-3.5 text-right">Section Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($sectionStats as $stat)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 font-bold text-slate-900">{{ $stat['name'] }}</td>
                            <td class="p-3.5 text-slate-600">{{ $stat['total'] }}</td>
                            <td class="p-3.5 text-emerald-600 font-bold">{{ $stat['correct'] }}</td>
                            <td class="p-3.5 text-rose-600 font-bold">{{ $stat['incorrect'] }}</td>
                            <td class="p-3.5 text-slate-400">{{ $stat['unattempted'] }}</td>
                            <td class="p-3.5 text-indigo-600 font-semibold">{{ $stat['accuracy'] }}%</td>
                            <td class="p-3.5 text-right font-mono font-bold text-sm text-slate-900">
                                {{ number_format((float)$stat['score'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Question & Solution Review -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Question by Question Solution Review</h2>
                <p class="text-xs text-slate-500 mt-0.5">Inspect full step-by-step explanations, passage context, and answer keys.</p>
            </div>

            <!-- Review Filter Pills -->
            <div class="flex items-center gap-2 text-xs font-semibold">
                <button wire:click="$set('filter', 'all')" class="px-3 py-1.5 rounded-lg transition {{ $filter === 'all' ? 'bg-brand-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">All ({{ $totalQuestions }})</button>
                <button wire:click="$set('filter', 'correct')" class="px-3 py-1.5 rounded-lg transition {{ $filter === 'correct' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-emerald-600' }}">Correct ({{ $correct }})</button>
                <button wire:click="$set('filter', 'incorrect')" class="px-3 py-1.5 rounded-lg transition {{ $filter === 'incorrect' ? 'bg-rose-600 text-white' : 'bg-white border border-slate-200 text-rose-600' }}">Incorrect ({{ $incorrect }})</button>
                <button wire:click="$set('filter', 'unattempted')" class="px-3 py-1.5 rounded-lg transition {{ $filter === 'unattempted' ? 'bg-slate-700 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Unattempted ({{ $unattempted }})</button>
            </div>
        </div>

        <div class="space-y-6">
            @foreach($filteredAnswers as $index => $ans)
                @php $q = $ans->question; @endphp
                <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-xs">
                    <!-- Question Header Strip -->
                    <div class="flex items-center justify-between border-b border-slate-200 pb-3 text-xs">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-slate-900 text-sm">Question #{{ $q->id }}</span>
                            <span class="px-2 py-0.5 rounded uppercase font-semibold bg-slate-100 text-slate-700">{{ $ans->testSection->name }}</span>
                            <span class="px-2 py-0.5 rounded uppercase font-semibold bg-brand-50 text-brand-700">{{ $q->type->value }}</span>
                        </div>

                        <!-- Result Badge -->
                        <div>
                            @if($ans->is_correct === true)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">+{{ $ans->marks_awarded }} Correct</span>
                            @elseif($ans->user_answer !== null)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $ans->marks_awarded }} Incorrect</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">Unattempted (0.00)</span>
                            @endif
                        </div>
                    </div>

                    <!-- Passage context if present -->
                    @if($q->passage)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 leading-relaxed max-h-48 overflow-y-auto">
                            <p class="font-bold text-brand-700 mb-1 uppercase tracking-wider">Passage Context:</p>
                            {!! $q->passage->content !!}
                        </div>
                    @endif

                    <!-- Question Content -->
                    <div class="text-slate-900 text-sm font-medium leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                        {!! $q->content !!}
                    </div>

                    <!-- MCQ Options with comparison -->
                    @if($q->type->value === 'mcq' && $q->options)
                        <div class="space-y-2 pt-1 text-xs">
                            @foreach($q->options as $opt)
                                @php
                                    $isCorrectKey = ($q->correct_answer === $opt['id']);
                                    $isUserChoice = ($ans->user_answer === $opt['id']);
                                    
                                    $borderStyle = 'bg-white border-slate-200 text-slate-700';
                                    if ($isCorrectKey) {
                                        $borderStyle = 'bg-emerald-50 border-emerald-400 text-emerald-950 font-bold';
                                    } elseif ($isUserChoice && !$isCorrectKey) {
                                        $borderStyle = 'bg-rose-50 border-rose-400 text-rose-950 line-through';
                                    }
                                @endphp
                                <div class="p-3 rounded-xl border {{ $borderStyle }} flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold">{{ $opt['id'] }}.</span>
                                        <span>{{ $opt['text'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($isUserChoice)
                                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $isCorrectKey ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">Your Answer</span>
                                        @endif
                                        @if($isCorrectKey)
                                            <span class="text-[10px] text-emerald-700 font-bold uppercase">Correct Option</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- TITA comparison -->
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs flex items-center justify-between">
                            <div>
                                <span class="text-slate-500">Your Keyed Input: </span>
                                <strong class="{{ $ans->is_correct ? 'text-emerald-700' : 'text-rose-600' }}">{{ $ans->user_answer ?? 'None' }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-500">Correct Key: </span>
                                <strong class="text-emerald-700">{{ $q->correct_answer }}</strong>
                            </div>
                        </div>
                    @endif

                    <!-- Solution / Explanation -->
                    @if($q->explanation)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 leading-relaxed">
                            <p class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Step-by-Step Solution & Explanation:
                            </p>
                            <div class="prose prose-xs max-w-none text-slate-600 pt-1">
                                {!! $q->explanation !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
