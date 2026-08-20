<div class="p-8 max-w-4xl mx-auto space-y-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-xs space-y-6">
        <!-- Header -->
        <div class="border-b border-slate-200 pb-4">
            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">{{ strtoupper($test->category->value) }}</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ $test->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Please read the instructions carefully before launching the CBT exam runner.</p>
        </div>

        <!-- General Instructions -->
        <div class="space-y-4 text-sm text-slate-700 leading-relaxed">
            <h2 class="font-bold text-slate-900 text-base">General Instructions:</h2>
            <ol class="list-decimal pl-5 space-y-2 text-xs text-slate-600">
                <li>Total test duration is <strong>{{ $test->total_duration_minutes }} minutes</strong>.</li>
                <li>The test is divided into <strong>{{ $test->sections->count() }} sections</strong>. Each section has a dedicated countdown timer.</li>
                <li>When the timer for a section expires, your answers for that section are automatically submitted and the next section will load immediately.</li>
                <li>You <strong>cannot return</strong> to previous sections once a section has ended.</li>
                <li>On-screen Calculator: <strong>{{ $test->has_calculator ? 'Enabled (Accessible from top right bar)' : 'Disabled' }}</strong>.</li>
            </ol>

            <h2 class="font-bold text-slate-900 text-base pt-2">Navigating to a Question & Palette Symbols:</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-xs">
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                    <span class="w-7 h-7 rounded bg-slate-400 text-white font-bold flex items-center justify-center text-xs">1</span>
                    <span class="text-slate-600">You have not visited the question yet.</span>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                    <span class="w-7 h-7 rounded bg-rose-600 text-white font-bold flex items-center justify-center text-xs">2</span>
                    <span class="text-slate-600">You have not answered the question.</span>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                    <span class="w-7 h-7 rounded bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">3</span>
                    <span class="text-slate-600">You have answered the question.</span>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-3">
                    <span class="w-7 h-7 rounded bg-purple-600 text-white font-bold flex items-center justify-center text-xs">4</span>
                    <span class="text-slate-600">Marked for Review (not answered).</span>
                </div>
            </div>

            <!-- Section Details Table -->
            <div class="pt-4">
                <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">Sectional Breakdown & Marking Scheme:</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs bg-slate-50 rounded-xl border border-slate-200 overflow-hidden">
                        <thead>
                            <tr class="text-slate-600 bg-slate-100 border-b border-slate-200">
                                <th class="p-3">Section</th>
                                <th class="p-3">Questions</th>
                                <th class="p-3">Duration</th>
                                <th class="p-3">Correct</th>
                                <th class="p-3">Negative MCQ</th>
                                <th class="p-3">Negative TITA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($test->sections as $sec)
                                <tr>
                                    <td class="p-3 font-bold text-slate-800">{{ $sec->name }}</td>
                                    <td class="p-3 text-slate-600">{{ $sec->questions->count() }}</td>
                                    <td class="p-3 text-slate-600">{{ $sec->duration_minutes }}m</td>
                                    <td class="p-3 text-emerald-600 font-bold">+{{ $sec->correct_marks }}</td>
                                    <td class="p-3 text-rose-600 font-bold">-{{ $sec->negative_mcq_marks }}</td>
                                    <td class="p-3 text-slate-500">0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Declaration Checkbox -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
            <label class="flex items-start gap-3 text-xs text-slate-700 cursor-pointer">
                <input type="checkbox" wire:model="agreed" class="mt-0.5 rounded bg-white border-slate-300 text-brand-600 focus:ring-0">
                <span>I have read and understood all instructions. I confirm that I am ready to begin the test with full exam simulation conditions.</span>
            </label>
            @error('agreed') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('portal.dashboard') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">&larr; Back to Mock Catalog</a>
            <button wire:click="startExam" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-600/20 transition">
                I Am Ready To Begin &rarr;
            </button>
        </div>
    </div>
</div>
