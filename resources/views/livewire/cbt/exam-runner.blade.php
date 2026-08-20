<div class="h-full flex flex-col bg-slate-100 text-slate-900 select-none overflow-hidden" 
     x-data="cbtEngine({{ json_encode($initialPayload) }})"
     x-init="initEngine()">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200 px-6 py-2.5 flex items-center justify-between shrink-0 shadow-xs">
        <!-- Exam Info -->
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center font-black text-white text-sm shadow">
                M
            </div>
            <div>
                <h1 class="text-sm font-bold text-slate-900 leading-tight" x-text="payload.test_title"></h1>
                <p class="text-[11px] text-slate-500">Computer Based Test (CBT) Real-Time Simulation</p>
            </div>
        </div>

        <!-- Section Navigation Tabs -->
        <div class="hidden md:flex items-center gap-1 bg-slate-100 border border-slate-200 rounded-xl p-1">
            <template x-for="(sec, idx) in payload.sections" :key="sec.id">
                <button @click="switchSection(idx)" 
                        :disabled="sec.is_section_locked && !sec.allow_return && idx !== activeSecIndex"
                        :class="{
                            'bg-brand-600 text-white shadow-xs': idx === activeSecIndex,
                            'text-slate-400 cursor-not-allowed': sec.is_section_locked && !sec.allow_return && idx !== activeSecIndex,
                            'text-slate-600 hover:text-slate-900': (!sec.is_section_locked || sec.allow_return) && idx !== activeSecIndex
                        }"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-2 transition">
                    <span x-text="sec.name"></span>
                    <template x-if="sec.is_section_locked && !sec.allow_return && idx !== activeSecIndex">
                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </template>
                </button>
            </template>
        </div>

        <!-- Right Bar: Calculator & Countdown Timer -->
        <div class="flex items-center gap-4">
            <template x-if="payload.has_calculator">
                <button @click="calcOpen = !calcOpen" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-300 text-xs font-bold text-brand-700 flex items-center gap-1.5 shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Calculator</span>
                </button>
            </template>

            <!-- Live Timer -->
            <div class="flex items-center gap-2 px-3.5 py-1.5 bg-slate-50 border border-slate-300 rounded-xl font-mono text-sm font-bold text-amber-600 shadow-xs">
                <svg class="w-4 h-4 text-amber-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="formatTime(timerSeconds)">00:00</span>
            </div>

            <!-- Candidate Profile -->
            <div class="hidden lg:flex items-center gap-2.5 border-l border-slate-200 pl-4">
                <div class="w-7 h-7 rounded-full bg-brand-100 border border-brand-200 text-brand-700 flex items-center justify-center font-bold text-xs">
                    AS
                </div>
                <div class="text-[11px] leading-tight">
                    <p class="font-bold text-slate-800" x-text="payload.candidate_name"></p>
                    <p class="text-slate-500 font-mono">ID: MP-2025-081</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Exam Split Workspace -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Center Panel: Question + Passage -->
        <div class="flex-1 flex flex-col bg-slate-50 overflow-hidden">
            <!-- Question Top Sub-header -->
            <div class="bg-white border-b border-slate-200 px-6 py-2 flex items-center justify-between text-xs text-slate-600">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-slate-900 text-sm" x-text="'Question No. ' + (activeQIndex + 1)"></span>
                    <span>&bull;</span>
                    <span class="font-medium text-slate-600">Type: <strong class="uppercase text-brand-600" x-text="currentQuestion() ? currentQuestion().type : ''"></strong></span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-emerald-700 font-bold" x-text="'Marks: +' + (currentSection() ? currentSection().correct_marks : '3.00')"></span>
                    <span class="text-rose-600 font-bold" x-text="'Negative: -' + (currentQuestion() && currentQuestion().type === 'mcq' ? currentSection().negative_mcq_marks : '0.00')"></span>
                </div>
            </div>

            <!-- Question Body (Split if passage present) -->
            <div class="flex-1 flex overflow-hidden bg-white">
                <!-- Left Pane: Passage (if linked) -->
                <template x-if="currentQuestion() && currentQuestion().passage">
                    <div class="w-1/2 p-6 overflow-y-auto border-r border-slate-200 bg-slate-50 space-y-4 text-xs text-slate-700 leading-relaxed">
                        <div class="flex items-center gap-2 text-xs font-bold text-brand-700 uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Reading Comprehension / Dataset Reference</span>
                        </div>
                        <div class="prose prose-xs max-w-none text-slate-700" x-html="currentQuestion().passage.content"></div>
                    </div>
                </template>

                <!-- Right Pane: Question & Options -->
                <div class="flex-1 p-6 overflow-y-auto space-y-6 text-sm bg-white">
                    <!-- Question Text -->
                    <div class="text-slate-900 font-medium text-sm sm:text-base leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200" 
                         x-html="currentQuestion() ? currentQuestion().content : ''">
                    </div>

                    <!-- MCQ Options -->
                    <template x-if="currentQuestion() && currentQuestion().type === 'mcq' && currentQuestion().options">
                        <div class="space-y-3 pt-2">
                            <template x-for="opt in currentQuestion().options" :key="opt.id">
                                <label @click="setAnswer(opt.id)"
                                       :class="getAnswerValue() === opt.id ? 'bg-brand-50 border-brand-500 text-brand-950 font-bold shadow-xs' : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300'"
                                       class="p-3.5 rounded-xl border cursor-pointer flex items-center gap-4 transition">
                                    <input type="radio" 
                                           :name="'q_radio_' + currentQuestion().id" 
                                           :value="opt.id" 
                                           :checked="getAnswerValue() === opt.id"
                                           class="w-4 h-4 text-brand-600 bg-white border-slate-300 focus:ring-0">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs shrink-0" x-text="opt.id"></span>
                                    <span class="text-sm font-medium" x-text="opt.text"></span>
                                </label>
                            </template>
                        </div>
                    </template>

                    <!-- TITA Numeric / Text Input -->
                    <template x-if="currentQuestion() && currentQuestion().type === 'tita'">
                        <div class="space-y-3 pt-2 max-w-md">
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Type In The Answer (TITA):</label>
                            <input type="text" 
                                   :value="getAnswerValue()" 
                                   @input="setAnswer($event.target.value)"
                                   placeholder="Enter exact numerical or text value..." 
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-slate-900 font-mono text-base font-bold tracking-wider focus:outline-none focus:border-brand-500 shadow-inner">
                            <p class="text-[11px] text-slate-500">Key in your final answer using keyboard. No negative marking for this question.</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer Action Strip -->
            <footer class="bg-white border-t border-slate-200 px-6 py-3 flex flex-wrap items-center justify-between gap-3 shrink-0 shadow-xs">
                <div class="flex items-center gap-2">
                    <button @click="markForReviewAndNext()" class="px-4 py-2 bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100 text-xs font-bold rounded-xl transition">
                        Mark for Review & Next
                    </button>
                    <button @click="clearResponse()" class="px-4 py-2 bg-slate-100 border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-200 text-xs font-bold rounded-xl transition">
                        Clear Response
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <button @click="saveAndNext()" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                        <span x-text="getNextButtonLabel()"></span>
                    </button>
                    <button @click="showSubmitModal = true" class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition">
                        Submit Test
                    </button>
                </div>
            </footer>
        </div>

        <!-- Right Side Panel: Question Status Palette -->
        <aside class="w-80 bg-slate-50 border-l border-slate-200 flex flex-col shrink-0 overflow-hidden">
            <!-- Palette Header -->
            <div class="p-4 border-b border-slate-200 bg-white">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Question Palette</h2>
                <p class="text-[11px] text-slate-500 mt-0.5" x-text="currentSection() ? currentSection().name : ''"></p>
            </div>

            <!-- Legend Summary Counts -->
            <div class="p-4 grid grid-cols-2 gap-2 border-b border-slate-200 text-[11px] bg-slate-50">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-600 text-white font-bold flex items-center justify-center text-xs shrink-0" x-text="getCounts().answered"></span>
                    <span class="text-slate-600">Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-rose-600 text-white font-bold flex items-center justify-center text-xs shrink-0" x-text="getCounts().not_answered"></span>
                    <span class="text-slate-600">Not Answered</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-slate-400 text-white font-bold flex items-center justify-center text-xs shrink-0" x-text="getCounts().not_visited"></span>
                    <span class="text-slate-600">Not Visited</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-600 text-white font-bold flex items-center justify-center text-xs shrink-0" x-text="getCounts().marked"></span>
                    <span class="text-slate-600">Marked</span>
                </div>
            </div>

            <!-- Question Number Grid -->
            <div class="flex-1 p-4 overflow-y-auto">
                <div class="grid grid-cols-4 gap-2.5">
                    <template x-if="currentSection()">
                        <template x-for="(q, idx) in currentSection().questions" :key="q.id">
                            <button @click="selectQuestion(idx)" 
                                    :class="getPaletteClass(q.id, idx)"
                                    class="h-10 rounded-xl font-bold text-xs border flex items-center justify-center transition"
                                    x-text="idx + 1">
                            </button>
                        </template>
                    </template>
                </div>
            </div>
        </aside>
    </div>

    <!-- On-Screen Floating Calculator Modal -->
    <div x-show="calcOpen" 
         x-cloak 
         class="fixed top-16 right-6 z-50 w-72 bg-white border border-slate-300 rounded-2xl shadow-2xl p-4 space-y-3">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <span class="text-xs font-bold text-brand-700 uppercase tracking-wider">Scientific Calculator</span>
            <button @click="calcOpen = false" class="text-slate-400 hover:text-slate-700">&times;</button>
        </div>

        <!-- Display -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-right font-mono text-xl font-bold text-emerald-600 overflow-x-auto" x-text="calcDisplay">
            0
        </div>

        <!-- Keypad -->
        <div class="grid grid-cols-4 gap-2 text-xs font-bold">
            <button @click="calcClear()" class="p-2.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100">C</button>
            <button @click="calcSqrt()" class="p-2.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">&radic;</button>
            <button @click="calcAppend('%')" class="p-2.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">%</button>
            <button @click="calcAppend('÷')" class="p-2.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100">&divide;</button>

            <button @click="calcAppend('7')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">7</button>
            <button @click="calcAppend('8')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">8</button>
            <button @click="calcAppend('9')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">9</button>
            <button @click="calcAppend('×')" class="p-2.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100">&times;</button>

            <button @click="calcAppend('4')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">4</button>
            <button @click="calcAppend('5')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">5</button>
            <button @click="calcAppend('6')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">6</button>
            <button @click="calcAppend('-')" class="p-2.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100">-</button>

            <button @click="calcAppend('1')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">1</button>
            <button @click="calcAppend('2')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">2</button>
            <button @click="calcAppend('3')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">3</button>
            <button @click="calcAppend('+')" class="p-2.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100">+</button>

            <button @click="calcAppend('0')" class="col-span-2 p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">0</button>
            <button @click="calcAppend('.')" class="p-2.5 rounded-lg bg-slate-100 text-slate-800 hover:bg-slate-200">.</button>
            <button @click="calcEval()" class="p-2.5 rounded-lg bg-brand-600 text-white hover:bg-brand-500">=</button>
        </div>
    </div>

    <!-- Final Submission Confirmation Modal -->
    <div x-show="showSubmitModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
        <div class="bg-white border border-slate-200 rounded-2xl w-full max-w-md p-6 space-y-5 shadow-2xl">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Final Exam Submission</h3>
                <p class="text-xs text-slate-500">Are you sure you want to end and submit your exam? All your answers will be evaluated immediately.</p>
            </div>

            <!-- Summary Breakdown -->
            <div class="grid grid-cols-3 gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200 text-center text-xs">
                <div>
                    <span class="text-emerald-700 font-bold text-sm" x-text="getOverallCounts().answered"></span>
                    <p class="text-[10px] text-slate-500">Answered</p>
                </div>
                <div>
                    <span class="text-rose-600 font-bold text-sm" x-text="getOverallCounts().not_answered"></span>
                    <p class="text-[10px] text-slate-500">Unanswered</p>
                </div>
                <div>
                    <span class="text-purple-700 font-bold text-sm" x-text="getOverallCounts().marked"></span>
                    <p class="text-[10px] text-slate-500">Marked</p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3 pt-2">
                <button @click="showSubmitModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl">
                    Return to Test
                </button>
                <button @click="submitTest()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/20 flex items-center gap-2">
                    <span>Yes, Submit Exam</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cbtEngine(payload) {
    return {
        payload: payload,
        activeSecIndex: 0,
        activeQIndex: 0,
        answers: {},
        timerSeconds: 2400,
        calcOpen: false,
        calcDisplay: '0',
        showSubmitModal: false,

        initEngine() {
            this.payload.sections.forEach(sec => {
                sec.questions.forEach(q => {
                    this.answers[q.id] = {
                        answer: q.initial_answer || null,
                        status: q.initial_status || 'not_visited',
                        time_spent: 0
                    };
                });
            });

            if (this.currentSection()) {
                this.timerSeconds = this.currentSection().duration_minutes * 60;
            }

            if (this.currentQuestion()) {
                const qId = this.currentQuestion().id;
                if (this.answers[qId].status === 'not_visited') {
                    this.answers[qId].status = 'not_answered';
                }
            }

            setInterval(() => {
                if (this.timerSeconds > 0) {
                    this.timerSeconds--;
                    if (this.currentQuestion()) {
                        this.answers[this.currentQuestion().id].time_spent++;
                    }
                } else {
                    this.handleTimeout();
                }
            }, 1000);
        },

        currentSection() {
            return this.payload.sections[this.activeSecIndex] || null;
        },

        currentQuestion() {
            const sec = this.currentSection();
            return sec ? (sec.questions[this.activeQIndex] || null) : null;
        },

        getAnswerValue() {
            const q = this.currentQuestion();
            return q && this.answers[q.id] ? this.answers[q.id].answer : null;
        },

        setAnswer(val) {
            const q = this.currentQuestion();
            if (!q) return;
            this.answers[q.id].answer = val;
        },

        selectQuestion(idx) {
            this.activeQIndex = idx;
            const q = this.currentQuestion();
            if (q && this.answers[q.id].status === 'not_visited') {
                this.answers[q.id].status = 'not_answered';
                this.backgroundSync(q.id);
            }
        },

        switchSection(secIdx) {
            const currentSec = this.currentSection();
            if (currentSec && currentSec.is_section_locked && !currentSec.allow_return && secIdx !== this.activeSecIndex) {
                return;
            }
            this.activeSecIndex = secIdx;
            this.activeQIndex = 0;
            this.timerSeconds = this.currentSection().duration_minutes * 60;
            const q = this.currentQuestion();
            if (q && this.answers[q.id].status === 'not_visited') {
                this.answers[q.id].status = 'not_answered';
            }
        },

        saveAndNext() {
            const q = this.currentQuestion();
            if (!q) return;

            const hasAns = !!this.answers[q.id].answer;
            this.answers[q.id].status = hasAns ? 'answered' : 'not_answered';
            this.backgroundSync(q.id);

            const sec = this.currentSection();
            const isLastQuestionOfSection = (this.activeQIndex >= sec.questions.length - 1);
            const isLastSection = (this.activeSecIndex >= this.payload.sections.length - 1);

            if (isLastQuestionOfSection) {
                if (!isLastSection) {
                    this.activeSecIndex++;
                    this.activeQIndex = 0;
                    this.timerSeconds = this.currentSection().duration_minutes * 60;
                } else {
                    this.showSubmitModal = true;
                }
            } else {
                this.activeQIndex++;
            }

            const nextQ = this.currentQuestion();
            if (nextQ && this.answers[nextQ.id].status === 'not_visited') {
                this.answers[nextQ.id].status = 'not_answered';
            }
        },

        clearResponse() {
            const q = this.currentQuestion();
            if (!q) return;
            this.answers[q.id].answer = null;
            this.answers[q.id].status = 'not_answered';
            this.backgroundSync(q.id);
        },

        markForReviewAndNext() {
            const q = this.currentQuestion();
            if (!q) return;

            const hasAns = !!this.answers[q.id].answer;
            this.answers[q.id].status = hasAns ? 'answered_marked' : 'marked_for_review';
            this.backgroundSync(q.id);

            const sec = this.currentSection();
            const isLastQuestionOfSection = (this.activeQIndex >= sec.questions.length - 1);
            const isLastSection = (this.activeSecIndex >= this.payload.sections.length - 1);

            if (isLastQuestionOfSection) {
                if (!isLastSection) {
                    this.activeSecIndex++;
                    this.activeQIndex = 0;
                    this.timerSeconds = this.currentSection().duration_minutes * 60;
                } else {
                    this.showSubmitModal = true;
                }
            } else {
                this.activeQIndex++;
            }

            const nextQ = this.currentQuestion();
            if (nextQ && this.answers[nextQ.id].status === 'not_visited') {
                this.answers[nextQ.id].status = 'not_answered';
            }
        },

        getNextButtonLabel() {
            const sec = this.currentSection();
            if (!sec) return 'Save & Next →';
            const isLastQ = (this.activeQIndex >= sec.questions.length - 1);
            const isLastSec = (this.activeSecIndex >= this.payload.sections.length - 1);

            if (isLastQ && isLastSec) return 'Save & Review / Submit Exam →';
            if (isLastQ && !isLastSec) return 'Save & Proceed to Next Section →';
            return 'Save & Next →';
        },

        getPaletteClass(qId, idx) {
            const status = this.answers[qId] ? this.answers[qId].status : 'not_visited';
            const isActive = (idx === this.activeQIndex);
            const activeRing = isActive ? 'ring-2 ring-brand-500 ring-offset-2 ring-offset-white scale-105 ' : 'hover:opacity-90 ';

            if (status === 'answered') return activeRing + 'bg-emerald-600 text-white border-emerald-500';
            if (status === 'not_answered') return activeRing + 'bg-rose-600 text-white border-rose-500';
            if (status === 'marked_for_review') return activeRing + 'bg-purple-600 text-white border-purple-500';
            if (status === 'answered_marked') return activeRing + 'bg-purple-600 text-white border-emerald-400 border-2';
            return activeRing + 'bg-slate-200 text-slate-700 border-slate-300';
        },

        getCounts() {
            const sec = this.currentSection();
            if (!sec) return { answered: 0, not_answered: 0, not_visited: 0, marked: 0 };

            let answered = 0, not_answered = 0, not_visited = 0, marked = 0;
            sec.questions.forEach(q => {
                const s = this.answers[q.id] ? this.answers[q.id].status : 'not_visited';
                if (s === 'answered') answered++;
                else if (s === 'not_answered') not_answered++;
                else if (s === 'not_visited') not_visited++;
                else if (s === 'marked_for_review' || s === 'answered_marked') marked++;
            });

            return { answered, not_answered, not_visited, marked };
        },

        getOverallCounts() {
            let answered = 0, not_answered = 0, marked = 0;
            Object.values(this.answers).forEach(item => {
                if (item.status === 'answered') answered++;
                else if (item.status === 'not_answered' || item.status === 'not_visited') not_answered++;
                else if (item.status === 'marked_for_review' || item.status === 'answered_marked') marked++;
            });
            return { answered, not_answered, marked };
        },

        formatTime(s) {
            const m = Math.floor(s / 60);
            const sec = s % 60;
            return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
        },

        backgroundSync(qId) {
            const item = this.answers[qId];
            if (!item) return;
            this.$wire.syncAnswer(qId, item.answer, item.status, item.time_spent);
        },

        handleTimeout() {
            if (this.activeSecIndex < this.payload.sections.length - 1) {
                this.activeSecIndex++;
                this.activeQIndex = 0;
                this.timerSeconds = this.currentSection().duration_minutes * 60;
            } else {
                this.submitTest();
            }
        },

        submitTest() {
            this.$wire.submitExam(this.answers);
        },

        calcAppend(val) {
            if (this.calcDisplay === '0' && val !== '.') {
                this.calcDisplay = val;
            } else {
                this.calcDisplay += val;
            }
        },
        calcClear() {
            this.calcDisplay = '0';
        },
        calcEval() {
            try {
                this.calcDisplay = String(eval(this.calcDisplay.replace(/×/g, '*').replace(/÷/g, '/')));
            } catch(e) {
                this.calcDisplay = 'Error';
            }
        },
        calcSqrt() {
            try {
                this.calcDisplay = String(Math.sqrt(parseFloat(this.calcDisplay)));
            } catch(e) {
                this.calcDisplay = 'Error';
            }
        }
    };
}
</script>
