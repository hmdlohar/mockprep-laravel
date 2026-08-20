<div class="max-w-4xl mx-auto px-6 py-12 space-y-8">
    <!-- Page Header -->
    <div class="space-y-1">
        <h1 class="text-3xl font-black tracking-tight text-slate-900">My Profile</h1>
        <p class="text-xs text-slate-500">Manage your personal information and password.</p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl">
            {{ session('message') }}
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-5">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" class="w-16 h-16 rounded-full border border-slate-200 object-cover" alt="{{ auth()->user()->name }}">
            @else
                <div class="w-16 h-16 rounded-full bg-purple-100 text-purple-700 font-black flex items-center justify-center text-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <p class="text-lg font-bold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-50 text-brand-700 border border-brand-200">
                    {{ auth()->user()->role->value }}
                </span>
            </div>
        </div>

        <!-- Update Profile Form -->
        <form wire:submit="updateProfile" class="space-y-4 text-xs border-t border-slate-100 pt-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Full Name</label>
                    <input type="text" wire:model="name" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition" placeholder="Your name">
                    @error('name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Email <span class="text-slate-400 normal-case font-medium">(cannot be changed)</span></label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-500 text-xs cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Mobile Number</label>
                    <div class="flex items-center">
                        <span class="px-3 py-2.5 bg-slate-100 border border-r-0 border-slate-300 rounded-l-xl text-slate-600 font-mono text-xs">+91</span>
                        <input type="tel" wire:model="phone" placeholder="9876543210" class="flex-1 bg-slate-50 border border-slate-300 rounded-r-xl px-3.5 py-2.5 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition">
                    </div>
                    @error('phone') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>

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
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" wire:loading.attr="disabled" wire:target="updateProfile" class="px-6 py-2.5 bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-md shadow-brand-600/20 transition text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                    <span wire:loading wire:target="updateProfile" class="hidden items-center gap-2"><svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>Saving...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password Card -->
    <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm space-y-6">
        <div class="space-y-1">
            <h2 class="text-lg font-bold text-slate-900">Change Password</h2>
            <p class="text-xs text-slate-500">Enter your current password to set a new one.</p>
        </div>

        <form wire:submit="updatePassword" class="space-y-4 text-xs">
            <div>
                <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Current Password</label>
                <input type="password" wire:model="current_password" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition" placeholder="••••••••">
                @error('current_password') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">New Password</label>
                    <input type="password" wire:model="new_password" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition" placeholder="Min 8 characters">
                    @error('new_password') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 uppercase mb-1">Confirm New Password</label>
                    <input type="password" wire:model="new_password_confirmation" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition" placeholder="Repeat new password">
                </div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword" class="px-6 py-2.5 bg-slate-950 hover:bg-slate-800 text-white font-bold rounded-xl transition text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                    <span wire:loading wire:target="updatePassword" class="hidden items-center gap-2"><svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>Updating...</span>
                </button>
            </div>
        </form>
    </div>
</div>
