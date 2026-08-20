<div class="min-h-[80vh] flex items-center justify-center p-6 bg-slate-50">
    <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-8 shadow-xl space-y-6">
        <!-- Header -->
        <div class="space-y-1">
            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-700 border border-brand-200">
                Step 1 of 1 &bull; Profile Setup
            </span>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight mt-2">Welcome to MockPrep!</h1>
            <p class="text-xs text-slate-500">Tell us a bit about your target entrance exam and background to personalize your mocks.</p>
        </div>

        <form wire:submit="saveProfile" class="space-y-4 text-xs">
            <!-- Mobile Number -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Mobile Number</label>
                <div class="flex items-center">
                    <span class="px-3 py-2.5 bg-slate-100 border border-r-0 border-slate-300 rounded-l-xl text-slate-600 font-mono text-xs">+91</span>
                    <input type="tel" wire:model="phone" placeholder="9876543210" class="flex-1 bg-slate-50 border border-slate-300 rounded-r-xl px-3.5 py-2.5 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition">
                </div>
                @error('phone') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Target Exam & Year -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Target Exam</label>
                    <select wire:model="target_exam" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-slate-900 text-xs focus:outline-none focus:border-brand-500 focus:bg-white transition">
                        <option value="CAT">CAT (IIM Entrance)</option>
                        <option value="CMAT">CMAT</option>
                        <option value="XAT">XAT (XLRI)</option>
                        <option value="SNAP">SNAP / NMAT</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Target Year</label>
                    <select wire:model="target_year" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-slate-900 text-xs focus:outline-none focus:border-brand-500 focus:bg-white transition">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                    </select>
                </div>
            </div>

            <!-- Academic Background -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Graduation Stream / Background</label>
                <select wire:model="college_stream" class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 text-slate-900 text-xs focus:outline-none focus:border-brand-500 focus:bg-white transition">
                    <option value="Engineering / B.Tech">Engineering (B.Tech / B.E)</option>
                    <option value="Commerce / B.Com">Commerce / Finance (B.Com / BAF)</option>
                    <option value="Management / BBA">Management (BBA / BMS)</option>
                    <option value="Arts & Humanities">Arts & Humanities (B.A)</option>
                    <option value="Science / B.Sc">Science (B.Sc / BCA)</option>
                    <option value="Other">Other Disciplines</option>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-md shadow-brand-600/20 transition text-xs">
                    Complete Profile & Go to Mocks &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
