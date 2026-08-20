<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Actions\CreateImpersonationTokenAction;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class UserIndex extends Component
{
    use WithPagination;

    public string $roleFilter = '';
    public string $search = '';

    public bool $showLinkModal = false;
    public string $linkUrl = '';
    public string $linkUserName = '';

    // Edit modal state
    public bool $showEditModal = false;
    public ?int $editingUserId = null;
    public string $editName = '';
    public string $editEmail = '';
    public string $editRole = 'student';
    public string $editPassword = '';
    public string $editPasswordConfirmation = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function setRoleFilter(string $role): void
    {
        $this->roleFilter = $role;
        $this->resetPage();
    }

    public function createLoginLink(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return;
        }

        $this->linkUrl = app(CreateImpersonationTokenAction::class)->execute($user);
        $this->linkUserName = $user->name;
        $this->showLinkModal = true;

        $this->dispatch('login-link-ready', url: $this->linkUrl);
    }

    public function openEditModal(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editRole = $user->role->value;
        $this->editPassword = '';
        $this->editPasswordConfirmation = '';
        $this->showEditModal = true;
    }

    public function updateUser(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'editRole' => 'required|in:admin,student',
            'editPassword' => 'nullable|string|min:8',
            'editPasswordConfirmation' => 'nullable|same:editPassword',
        ]);

        $user = User::findOrFail($this->editingUserId);

        $data = [
            'name' => $this->editName,
            'email' => $this->editEmail,
            'role' => $user->id === auth()->id() ? $user->role->value : $this->editRole,
        ];

        if ($this->editPassword !== '') {
            $data['password'] = $this->editPassword;
        }

        $user->update($data);

        session()->flash('message', "User {$user->name} updated successfully.");
        $this->showEditModal = false;
    }

    public function render()
    {
        $query = User::query()
            ->when($this->roleFilter !== '', fn ($q) => $q->where('role', $this->roleFilter))
            ->when($this->search !== '', function ($q) {
                $q->where(fn ($q) => $q
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%'));
            });

        return view('livewire.admin.user-index', [
            'users' => $query->orderByDesc('id')->paginate(10),
            'roleCounts' => User::selectRaw('role, count(*) as total')
                ->groupBy('role')
                ->pluck('total', 'role'),
        ]);
    }
}
