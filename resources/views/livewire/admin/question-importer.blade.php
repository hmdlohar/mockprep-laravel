<div class="p-8 space-y-8 max-w-7xl mx-auto"
     x-data="{ 
         handleKey(e) {
             if (!@js($viewingQuestionIndex !== null)) return;
             if (e.key === 'ArrowLeft') $wire.prevQuestion();
             if (e.key === 'ArrowRight') $wire.nextQuestion();
             if (e.key === 'Escape') $wire.closeQuestionModal();
         }
     }"
     @keydown.window="handleKey($event)">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200">
                Extensible Parser Engine
            </span>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">Bulk Question Importer</h1>
            <p class="text-xs text-slate-500">Stage, inspect section/topic distributions, review questions in modal, and commit cleanly without duplicates.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Global subtle async indicator -->
            <div wire:loading class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-700 text-xs font-bold animate-pulse">
                <svg class="animate-spin w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Processing...</span>
            </div>

            @if($batchData)
                <button wire:click="resetImporter" wire:loading.attr="disabled" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition disabled:opacity-50">
                    &larr; Upload Another File
                </button>
            @endif
        </div>
    </div>

    <!-- Success Import Alert -->
    @if($importSummary)
        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-6 sm:p-8 space-y-4 shadow-xs">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-base shadow-md shadow-emerald-600/20">✓</div>
                    <div>
                        <h3 class="text-lg font-black text-emerald-950">Bulk Import Completed Successfully!</h3>
                        <p class="text-xs text-emerald-700">All selected questions, passages, and topic taxonomies have been persisted to the question bank.</p>
                    </div>
                </div>

                @if(!empty($importSummary['created_test_slug']))
                    <a href="{{ route('portal.test.instructions', $importSummary['created_test_slug']) }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Launch & Test CBT Exam Runner &rarr;</span>
                    </a>
                @endif
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-xs">
                <div class="p-3.5 bg-white rounded-xl border border-emerald-100"><span class="font-black text-emerald-800 text-lg block">{{ $importSummary['imported_questions'] }}</span> Questions Added</div>
                <div class="p-3.5 bg-white rounded-xl border border-emerald-100"><span class="font-black text-emerald-800 text-lg block">{{ $importSummary['imported_passages'] }}</span> Passages Created</div>
                <div class="p-3.5 bg-white rounded-xl border border-emerald-100"><span class="font-black text-emerald-800 text-lg block">{{ $importSummary['imported_topics'] }}</span> Topics Synced</div>
                @if(!empty($importSummary['created_test_title']))
                    <div class="p-3.5 bg-brand-50 rounded-xl border border-brand-200"><span class="font-black text-brand-800 text-lg block">{{ $importSummary['test_questions_count'] }}</span> In Generated Mock Test</div>
                @else
                    <div class="p-3.5 bg-white rounded-xl border border-emerald-100"><span class="font-bold text-slate-500 text-xs block">Mock Test:</span> None Created</div>
                @endif
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('admin.questions') }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl inline-block transition">
                    View in Question Bank &rarr;
                </a>
                @if(!empty($importSummary['created_test_id']))
                    <a href="{{ route('admin.tests') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl inline-block transition">
                        View in Tests Directory &rarr;
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- STEP 1: Upload Dropzone (When no batch is staged) -->
    @if(!$batchData)
        <div class="max-w-2xl mx-auto">
            <!-- Web File Upload Card -->
            <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-xs space-y-6">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Upload Test File</h2>
                    <p class="text-xs text-slate-500 mt-1">Upload `.json` or `.jsonp` test file to stage questions with duplicate detection & topic mapping.</p>
                </div>

                <form wire:submit="dryRunUpload" class="space-y-4">
                    <div class="border-2 border-dashed border-slate-200 hover:border-brand-500 rounded-2xl p-10 text-center space-y-3 transition bg-slate-50/50">
                        <div>
                            <label class="cursor-pointer font-bold text-sm text-brand-600 hover:underline">
                                <span>Choose a file</span>
                                <input type="file" wire:model="uploadedFile" class="hidden" accept=".json,.jsonp,.txt">
                            </label>
                            <span class="text-xs text-slate-500"> or drag & drop here</span>
                        </div>
                        <p class="text-[11px] text-slate-400">JSON, JSONP formats supported (up to 20MB)</p>
                    </div>

                    @if($uploadedFile)
                        <div class="p-3.5 bg-brand-50 border border-brand-200 rounded-xl text-xs text-brand-800 font-bold flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ $uploadedFile->getClientOriginalName() }}
                            </span>
                            <span class="text-[11px] font-normal text-slate-500">{{ round($uploadedFile->getSize() / 1024, 1) }} KB</span>
                        </div>
                    @endif

                    @error('uploadedFile') <p class="text-xs text-rose-600 font-semibold text-center">{{ $message }}</p> @enderror

                    <button type="submit" wire:loading.attr="disabled" wire:target="dryRunUpload, uploadedFile" class="w-full py-3.5 gradient-btn-primary text-white text-xs font-bold rounded-xl shadow-md shadow-purple-500/20 hover:opacity-95 transition flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="dryRunUpload, uploadedFile">Parse & Analyze File &rarr;</span>
                        <span wire:loading wire:target="uploadedFile" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Uploading test payload...</span>
                        </span>
                        <span wire:loading wire:target="dryRunUpload" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Analyzing schema & detecting duplicates...</span>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- STEP 2: Staged Batch Analysis & Summary Dashboards -->
    @if($batchData)
        <div class="space-y-6">
            <!-- Metric Overview Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Questions</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $batchData['totalQuestions'] }}</h3>
                    <p class="text-xs text-slate-500 mt-1 truncate" title="{{ $batchData['sourceFileName'] }}">File: {{ $batchData['sourceFileName'] }}</p>
                </div>

                <div class="bg-white border border-emerald-200 rounded-2xl p-5 shadow-xs bg-emerald-50/20">
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">New Questions</p>
                    <h3 class="text-3xl font-black text-emerald-700 mt-1">{{ $batchData['newQuestionsCount'] }}</h3>
                    <p class="text-xs text-emerald-600 mt-1">Ready for insertion</p>
                </div>

                <div class="bg-white border border-amber-200 rounded-2xl p-5 shadow-xs bg-amber-50/20">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Duplicates</p>
                    <h3 class="text-3xl font-black text-amber-600 mt-1">{{ $batchData['duplicateQuestionsCount'] }}</h3>
                    <p class="text-xs text-amber-700 mt-1">Matched against DB (Unchecked)</p>
                </div>

                <div class="bg-white border border-purple-200 rounded-2xl p-5 shadow-xs bg-purple-50/20">
                    <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Passage Sets</p>
                    <h3 class="text-3xl font-black text-purple-700 mt-1">{{ $batchData['passagesCount'] }}</h3>
                    <p class="text-xs text-purple-600 mt-1">Deduplicated RC / Sets</p>
                </div>
            </div>

            <!-- Section Breakdown & Topic Breakdown Matrix -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Section Distribution Card -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Section Distribution</h3>
                        @if($selectedSectionFilter !== '')
                            <button wire:click="$set('selectedSectionFilter', '')" class="text-[11px] text-brand-600 hover:underline font-bold">Clear Filter</button>
                        @endif
                    </div>

                    <div class="space-y-2 text-xs">
                        <div wire:click="$set('selectedSectionFilter', 'va')" class="p-3 rounded-xl border cursor-pointer flex items-center justify-between transition {{ $selectedSectionFilter === 'va' ? 'bg-brand-50 border-brand-500 font-bold' : 'bg-slate-50 border-slate-200 hover:border-slate-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                                <span>Verbal Ability (VA)</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 font-bold text-[11px]">{{ $batchData['sectionCounts']['va'] ?? 0 }} Qs</span>
                        </div>

                        <div wire:click="$set('selectedSectionFilter', 'dilr')" class="p-3 rounded-xl border cursor-pointer flex items-center justify-between transition {{ $selectedSectionFilter === 'dilr' ? 'bg-brand-50 border-brand-500 font-bold' : 'bg-slate-50 border-slate-200 hover:border-slate-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                <span>Data Interpretation & Reasoning (DILR)</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-[11px]">{{ $batchData['sectionCounts']['dilr'] ?? 0 }} Qs</span>
                        </div>

                        <div wire:click="$set('selectedSectionFilter', 'qa')" class="p-3 rounded-xl border cursor-pointer flex items-center justify-between transition {{ $selectedSectionFilter === 'qa' ? 'bg-brand-50 border-brand-500 font-bold' : 'bg-slate-50 border-slate-200 hover:border-slate-300' }}">
                            <span class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span>Quantitative Aptitude (QA)</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[11px]">{{ $batchData['sectionCounts']['qa'] ?? 0 }} Qs</span>
                        </div>
                    </div>
                </div>

                <!-- Topic Distribution Card -->
                <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Topic-wise Breakdown & Filters</h3>
                        @if($selectedTopicFilter !== '')
                            <button wire:click="$set('selectedTopicFilter', '')" class="text-[11px] text-brand-600 hover:underline font-bold">Clear Topic Filter</button>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2 max-h-44 overflow-y-auto pt-1">
                        @forelse($batchData['topicCounts'] as $topicName => $count)
                            <button wire:click="$set('selectedTopicFilter', '{{ $topicName }}')" class="px-3 py-1.5 rounded-xl text-xs font-medium border flex items-center gap-2 transition {{ $selectedTopicFilter === $topicName ? 'bg-brand-600 text-white font-bold border-brand-600 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                                <span>{{ $topicName }}</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $selectedTopicFilter === $topicName ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $count }}</span>
                            </button>
                        @empty
                            <p class="text-xs text-slate-400">No topic tags mapped.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Direct Mock Test Generation Config Card -->
            <div class="bg-white border {{ $createTestDirectly ? 'border-brand-300 ring-2 ring-brand-500/10' : 'border-slate-200' }} rounded-2xl p-6 shadow-xs transition space-y-5">
                <div class="flex items-start justify-between gap-4">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" wire:model.live="createTestDirectly" class="w-5 h-5 rounded border-slate-300 text-brand-600 focus:ring-0">
                        <div>
                            <span class="text-sm font-black text-slate-900 flex items-center gap-2">
                                <span>Create CBT Mock Test directly from this import</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">Optional</span>
                            </span>
                            <p class="text-xs text-slate-500 mt-0.5">Builds an exam with sections, timers, and automatically selects unbroken RC/DILR question sets.</p>
                        </div>
                    </label>

                    @if($createTestDirectly)
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                            ✓ Test Creation Active
                        </span>
                    @endif
                </div>

                @if($createTestDirectly)
                    <div class="pt-4 border-t border-slate-100 grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in slide-in-from-top-2 duration-150 text-xs">
                        <!-- Test Meta -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Test Title</label>
                                <input type="text" wire:model="testTitle" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold text-xs focus:outline-none focus:border-brand-500 focus:bg-white transition">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Exam Type</label>
                                    <select wire:model="testExamCategory" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 text-xs font-semibold focus:outline-none focus:border-brand-500 focus:bg-white">
                                        <option value="cat">CAT</option>
                                        <option value="xat">XAT</option>
                                        <option value="snap">SNAP</option>
                                        <option value="nmat">NMAT</option>
                                        <option value="cmat">CMAT</option>
                                        <option value="iift">IIFT</option>
                                        <option value="mhcet">MHCET</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Total Timer (min)</label>
                                    <input type="number" wire:model="testTotalDuration" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold text-xs focus:outline-none focus:border-brand-500 focus:bg-white">
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="testHasCalculator" class="rounded border-slate-300 text-brand-600 focus:ring-0">
                                    <span class="text-slate-700 font-medium">On-screen Calculator</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="testSectionLocked" class="rounded border-slate-300 text-brand-600 focus:ring-0">
                                    <span class="text-slate-700 font-medium">Enforce Section Timer</span>
                                </label>
                            </div>
                        </div>

                        <!-- Section Quota Configurator with Unbroken Set Guarantee -->
                        <div class="lg:col-span-2 space-y-3 bg-slate-50/70 p-4 rounded-2xl border border-slate-200">
                            <div class="flex items-center justify-between">
                                <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Section Question Quota & Timers</h4>
                                <span class="text-[10px] text-brand-700 font-bold bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
                                    🛡️ Unbroken Question Sets Guaranteed
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500">Specify how many questions to pick from each section. Multi-question RC/DILR passage sets will remain 100% complete without breaking.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                                <!-- Verbal Ability Quota -->
                                <div class="p-3.5 bg-white rounded-xl border border-slate-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-purple-700 text-xs">VA Section</span>
                                        <span class="text-[10px] text-slate-400 font-bold">{{ $batchData['sectionCounts']['va'] ?? 0 }} Avail</span>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Target Questions:</label>
                                        <input type="number" wire:model="sectionQuotas.va" min="0" max="{{ $batchData['sectionCounts']['va'] ?? 0 }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-xs text-slate-900 focus:bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Section Min:</label>
                                        <input type="number" wire:model="sectionDurations.va" min="1" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-xs text-slate-900 focus:bg-white">
                                    </div>
                                </div>

                                <!-- DILR Quota -->
                                <div class="p-3.5 bg-white rounded-xl border border-slate-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-blue-700 text-xs">DILR Section</span>
                                        <span class="text-[10px] text-slate-400 font-bold">{{ $batchData['sectionCounts']['dilr'] ?? 0 }} Avail</span>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Target Questions:</label>
                                        <input type="number" wire:model="sectionQuotas.dilr" min="0" max="{{ $batchData['sectionCounts']['dilr'] ?? 0 }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-xs text-slate-900 focus:bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Section Min:</label>
                                        <input type="number" wire:model="sectionDurations.dilr" min="1" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-xs text-slate-900 focus:bg-white">
                                    </div>
                                </div>

                                <!-- QA Quota -->
                                <div class="p-3.5 bg-white rounded-xl border border-slate-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-emerald-700 text-xs">QA Section</span>
                                        <span class="text-[10px] text-slate-400 font-bold">{{ $batchData['sectionCounts']['qa'] ?? 0 }} Avail</span>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Target Questions:</label>
                                        <input type="number" wire:model="sectionQuotas.qa" min="0" max="{{ $batchData['sectionCounts']['qa'] ?? 0 }}" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-xs text-slate-900 focus:bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-slate-500 font-semibold mb-0.5">Section Min:</label>
                                        <input type="number" wire:model="sectionDurations.qa" min="1" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-bold text-xs text-slate-900 focus:bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Strip & Question Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <button wire:click="toggleSelectAll" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Select / Deselect Visible
                    </button>

                    <!-- Filter pills -->
                    <div class="flex items-center gap-1 border-l border-slate-200 pl-3">
                        <button wire:click="$set('previewFilter', 'all')" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $previewFilter === 'all' ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">All ({{ $batchData['totalQuestions'] }})</button>
                        <button wire:click="$set('previewFilter', 'new')" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $previewFilter === 'new' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">New Only ({{ $batchData['newQuestionsCount'] }})</button>
                        <button wire:click="$set('previewFilter', 'duplicates')" class="px-3 py-1 rounded-lg text-xs font-bold transition {{ $previewFilter === 'duplicates' ? 'bg-amber-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Duplicates ({{ $batchData['duplicateQuestionsCount'] }})</button>
                    </div>
                </div>

                <button wire:click="commitImport" 
                        wire:confirm="Are you sure you want to commit these questions{{ $createTestDirectly ? ' and build the CBT Mock Exam' : '' }}?" 
                        wire:loading.attr="disabled" 
                        wire:target="commitImport" 
                        class="px-6 py-2.5 gradient-btn-primary hover:opacity-95 text-white text-xs font-bold rounded-xl shadow-md shadow-purple-500/20 transition flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="commitImport" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $createTestDirectly ? 'Import & Build Mock Test (' . count($selectedIndexes) . ' Qs) &rarr;' : 'Import ' . count($selectedIndexes) . ' Selected Questions &rarr;' }}</span>
                    </span>
                    <span wire:loading wire:target="commitImport" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>{{ $createTestDirectly ? 'Persisting Questions & Creating CBT Mock Exam...' : 'Persisting Questions to Lake...' }}</span>
                    </span>
                </button>
            </div>

            <!-- Lean Question Summary Table -->
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-600 bg-slate-50 border-b border-slate-200 uppercase font-semibold">
                            <th class="p-3.5 w-12 text-center">Include</th>
                            <th class="p-3.5 w-16">#</th>
                            <th class="p-3.5">Question Summary / Heading</th>
                            <th class="p-3.5">Section</th>
                            <th class="p-3.5">Topic Tags</th>
                            <th class="p-3.5">Type & LOD</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right w-24">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($filteredQuestions as $q)
                            <tr class="hover:bg-slate-50/80 transition {{ $q['isDuplicate'] ? 'bg-amber-50/20' : '' }}">
                                <td class="p-3.5 text-center">
                                    <input type="checkbox" wire:model="selectedIndexes.{{ $q['index'] }}" class="rounded bg-white border-slate-300 text-brand-600 focus:ring-0">
                                </td>
                                <td class="p-3.5 font-mono text-slate-500 font-bold">#{{ $q['index'] + 1 }}</td>
                                <td class="p-3.5 max-w-md">
                                    <p class="font-medium text-slate-900 line-clamp-1">{{ $q['snippet'] }}</p>
                                    @if($q['passageContent'])
                                        <span class="text-[10px] text-brand-600 font-bold flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Linked to Passage Set #{{ $q['passageExternalId'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded uppercase font-bold text-[10px] bg-brand-50 text-brand-700 border border-brand-200">
                                        {{ $q['sectionCategory'] }}
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <div class="flex items-center gap-1 flex-wrap max-w-xs">
                                        @foreach(array_slice($q['topics'], 0, 2) as $t)
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px]">{{ $t }}</span>
                                        @endforeach
                                        @if(count($q['topics']) > 2)
                                            <span class="text-[10px] text-slate-400 font-bold">+{{ count($q['topics']) - 2 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <div class="space-y-0.5">
                                        <span class="uppercase text-[10px] font-semibold text-slate-600 block">{{ $q['type'] }}</span>
                                        <span class="text-amber-600 font-bold text-[10px]">Lvl {{ $q['difficulty'] }}/5</span>
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    @if($q['isDuplicate'])
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            Duplicate (DB #{{ $q['duplicateOfId'] }})
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            New
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right">
                                    <button wire:click="openQuestionModal({{ $q['index'] }})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-600 hover:text-brand-600 transition" title="Inspect Full Question">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-500">No questions found matching current section / topic filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- STEP 3: Interactive Question Inspection Modal with Prev/Next Navigation -->
    @if($viewingQuestionIndex !== null && isset($batchData['questions'][$viewingQuestionIndex]))
        @php $vq = $batchData['questions'][$viewingQuestionIndex]; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/70 backdrop-blur-xs">
            <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-6xl max-h-[92vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                <!-- Modal Top Header Strip -->
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-xs">
                            #{{ $vq['index'] + 1 }}
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Question Preview (ID: {{ $vq['externalId'] }})</h3>
                            <p class="text-[11px] text-slate-500">Viewing {{ $vq['index'] + 1 }} of {{ count($batchData['questions']) }} questions</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 cursor-pointer">
                            <input type="checkbox" wire:model="selectedIndexes.{{ $vq['index'] }}" class="rounded bg-white border-slate-300 text-brand-600 focus:ring-0">
                            <span>Include in Import</span>
                        </label>
                        <button wire:click="closeQuestionModal" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-200 transition text-base font-bold">&times;</button>
                    </div>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="flex-1 p-6 sm:p-8 overflow-y-auto space-y-6 text-xs text-slate-700">
                    <!-- Meta Badges -->
                    <div class="flex items-center gap-2 flex-wrap pb-2 border-b border-slate-100">
                        <span class="px-2.5 py-0.5 rounded uppercase font-bold text-[10px] bg-brand-50 text-brand-700 border border-brand-200">{{ $vq['sectionCategory'] }}</span>
                        <span class="px-2 py-0.5 rounded uppercase text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $vq['type'] }}</span>
                        <span class="text-amber-600 font-bold text-[11px]">Difficulty Lvl {{ $vq['difficulty'] }}/5</span>

                        @if($vq['isDuplicate'])
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Duplicate Detected (Matches DB #{{ $vq['duplicateOfId'] }})
                            </span>
                        @endif
                    </div>

                    <!-- If Linked Passage exists, show side-by-side or dedicated block -->
                    @if($vq['passageContent'])
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 leading-relaxed text-xs text-slate-800">
                            <div class="flex items-center gap-2 font-bold text-brand-700 uppercase tracking-wider text-[11px]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Passage Set Reference (#{{ $vq['passageExternalId'] }}):</span>
                            </div>
                            <div class="prose prose-sm max-w-none text-slate-800 max-h-60 overflow-y-auto leading-relaxed border-t border-slate-200 pt-3">
                                {!! $vq['passageContent'] !!}
                            </div>
                        </div>
                    @endif

                    <!-- Question Content -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Question Text:</label>
                        <div class="p-5 bg-slate-50/70 border border-slate-200 rounded-2xl text-slate-900 text-sm font-medium leading-relaxed">
                            {!! $vq['content'] !!}
                        </div>
                    </div>

                    <!-- Options (if MCQ) -->
                    @if($vq['type'] === 'mcq' && !empty($vq['options']))
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Multiple Choice Options:</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($vq['options'] as $opt)
                                    @php $isCorrect = ($vq['correctAnswer'] === $opt['id']); @endphp
                                    <div class="p-4 rounded-2xl border flex items-start justify-between gap-3 {{ $isCorrect ? 'bg-emerald-50/80 border-emerald-400 text-emerald-950 font-medium' : 'bg-white border-slate-200 text-slate-800' }}">
                                        <div class="flex items-start gap-3 flex-1">
                                            <span class="w-6 h-6 rounded-lg bg-slate-100 shrink-0 flex items-center justify-center font-bold text-xs {{ $isCorrect ? 'bg-emerald-600 text-white' : 'text-slate-700' }}">{{ $opt['id'] }}</span>
                                            <div class="leading-relaxed flex-1">{!! $opt['text'] !!}</div>
                                        </div>
                                        @if($isCorrect)
                                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-emerald-600 text-white shrink-0">Correct Answer</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs flex items-center gap-2">
                            <span class="text-slate-500 font-bold">TITA Keyed Answer: </span>
                            <span class="font-mono text-emerald-700 font-bold text-base">{{ $vq['correctAnswer'] }}</span>
                        </div>
                    @endif

                    <!-- Explanation -->
                    @if($vq['explanation'])
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Step-by-Step Explanation:</label>
                            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-700 leading-relaxed">
                                {!! $vq['explanation'] !!}
                            </div>
                        </div>
                    @endif

                    <!-- Topic Tags -->
                    @if(!empty($vq['topics']))
                        <div class="flex items-center gap-1.5 flex-wrap pt-2 border-t border-slate-100">
                            <span class="text-slate-400 font-bold uppercase text-[10px]">Topic Tags:</span>
                            @foreach($vq['topics'] as $t)
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-medium">{{ $t }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Modal Bottom Navigation Footer -->
                <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
                    <button wire:click="prevQuestion" 
                            wire:loading.attr="disabled"
                            :disabled="@js($vq['index'] === 0)"
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
                        <span>&larr; Previous</span>
                        <kbd class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded border text-slate-400">Left</kbd>
                    </button>

                    <span class="text-xs text-slate-500 font-medium">
                        {{ $vq['index'] + 1 }} / {{ count($batchData['questions']) }}
                    </span>

                    <button wire:click="nextQuestion" 
                            wire:loading.attr="disabled"
                            :disabled="@js($vq['index'] >= count($batchData['questions']) - 1)"
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
                        <span>Next &rarr;</span>
                        <kbd class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded border text-slate-400">Right</kbd>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
