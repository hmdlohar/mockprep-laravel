<div class="p-8 space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">User Management</h1>
            <p class="text-xs text-slate-500 mt-1">Browse all students and administrators, generate one-time login links.</p>
        </div>

        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name or email..." class="bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 pr-9 text-slate-900 text-xs placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white w-full sm:w-72">
            <svg wire:loading wire:target="search" class="w-4 h-4 text-brand-600 animate-spin absolute right-3 top-2.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-2xl">
            {{ session('message') }}
        </div>
    @endif

    <!-- Role Filter: Grouped Buttons -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="inline-flex rounded-xl border border-slate-200 bg-white p-1 shadow-xs">
            @foreach(['' => 'All Users', 'student' => 'Students', 'admin' => 'Admins'] as $value => $label)
                <button wire:click="setRoleFilter('{{ $value }}')" wire:loading.attr="disabled" wire:target="setRoleFilter('{{ $value }}')"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-2 {{ $roleFilter === $value ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    {{ $label }}
                    <span class="px-1.5 py-0.5 rounded text-[10px] {{ $roleFilter === $value ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                        {{ $value === '' ? $roleCounts->sum() : ($roleCounts[$value] ?? 0) }}
                    </span>
                </button>
            @endforeach
        </div>

        <div wire:loading wire:target="setRoleFilter, search, gotoPage, previousPage, nextPage" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-700 text-xs font-bold animate-pulse">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
            Updating list...
        </div>
    </div>

    <!-- Users Table -->
    <div wire:loading.class="opacity-50 pointer-events-none" wire:target="setRoleFilter, search, gotoPage, previousPage, nextPage" class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">User</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Email</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Role</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Joined</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-brand-50 border border-brand-200 flex items-center justify-center font-bold text-[10px] text-brand-700 shrink-0">
                                        {{ strtoupper(Str::substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                        @if($user->id === auth()->id())
                                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-600 font-medium">{{ $user->email }}</td>
                            <td class="px-6 py-3.5">
                                @if($user->isAdmin())
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">Admin</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">Student</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 text-right">
                                @if($user->id === auth()->id())
                                    <button wire:click="openEditModal({{ $user->id }})" wire:loading.attr="disabled" wire:target="openEditModal({{ $user->id }})"
                                        class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold uppercase rounded-lg transition inline-flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg wire:loading.remove wire:target="openEditModal({{ $user->id }})" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <svg wire:loading wire:target="openEditModal({{ $user->id }})" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                        <span>Edit</span>
                                    </button>
                                @else
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="openEditModal({{ $user->id }})" wire:loading.attr="disabled" wire:target="openEditModal({{ $user->id }})"
                                            class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold uppercase rounded-lg transition inline-flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg wire:loading.remove wire:target="openEditModal({{ $user->id }})" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <svg wire:loading wire:target="openEditModal({{ $user->id }})" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="createLoginLink({{ $user->id }})" wire:loading.attr="disabled" wire:target="createLoginLink({{ $user->id }})"
                                            class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 text-[10px] font-bold uppercase rounded-lg transition inline-flex items-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg wire:loading.remove wire:target="createLoginLink({{ $user->id }})" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                            <svg wire:loading wire:target="createLoginLink({{ $user->id }})" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                            <span>Login Link</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-xs font-bold">
                                {{ $search ? 'No users match your search.' : 'No users found.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-3 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Edit User Modal -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl w-full max-w-lg border border-slate-200 shadow-2xl p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-900">Edit User</h3>
                    <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>

                <form wire:submit="updateUser" class="space-y-4 text-xs">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Name</label>
                        <input type="text" wire:model="editName" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white focus:outline-none focus:border-brand-500">
                        @error('editName') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Email</label>
                        <input type="email" wire:model="editEmail" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white focus:outline-none focus:border-brand-500">
                        @error('editEmail') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Role</label>
                        @if($editingUserId === auth()->id())
                            <input type="text" value="{{ ucfirst($editRole) }}" disabled class="w-full bg-slate-100 border border-slate-200 rounded-xl px-3 py-2 text-slate-500 font-bold cursor-not-allowed">
                            <p class="text-[10px] text-amber-600 font-bold mt-1">You cannot change your own role.</p>
                        @else
                            <select wire:model="editRole" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white focus:outline-none focus:border-brand-500">
                                <option value="student">Student</option>
                                <option value="admin">Admin</option>
                            </select>
                        @endif
                        @error('editRole') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">New Password</label>
                            <input type="password" wire:model="editPassword" placeholder="Leave blank to keep" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white focus:outline-none focus:border-brand-500 placeholder-slate-400 placeholder-font-normal">
                            @error('editPassword') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Confirm Password</label>
                            <input type="password" wire:model="editPasswordConfirmation" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-900 font-bold focus:bg-white focus:outline-none focus:border-brand-500">
                            @error('editPasswordConfirmation') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="updateUser" class="px-5 py-2 gradient-btn-primary text-white font-bold rounded-xl shadow-xs inline-flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="updateUser" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Impersonation Link Modal -->
    @if($showLinkModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs" x-data="{ copied: false }">
            <div class="bg-white rounded-3xl w-full max-w-lg border border-slate-200 shadow-2xl p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-black text-slate-900">One-Time Login Link</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Login as <span class="font-bold text-slate-700">{{ $linkUserName }}</span></p>
                    </div>
                    <button wire:click="$set('showLinkModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-800 font-medium">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Open this link in a <b>private / incognito window</b>. Valid for {{ \App\Actions\CreateImpersonationTokenAction::TTL_MINUTES }} minutes, single use only.</span>
                    </div>

                    <input type="text" x-ref="link" value="{{ $linkUrl }}" readonly @click="$refs.link.select()"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-xs text-slate-700 font-mono focus:outline-none focus:border-brand-500">

                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold" :class="copied ? 'text-emerald-600' : 'text-slate-400'" x-text="copied ? 'Link copied to clipboard!' : 'The link was also auto-copied if allowed.'"></p>
                        <button x-on:click="navigator.clipboard.writeText($refs.link.value).then(() => copied = true).catch(() => { $refs.link.select(); document.execCommand('copy'); copied = true; })"
                            class="px-5 py-2 gradient-btn-primary text-white text-xs font-bold rounded-xl shadow-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('login-link-ready', (e) => {
            navigator.clipboard?.writeText(e.detail.url).catch(() => {});
        });
    </script>
</div>
