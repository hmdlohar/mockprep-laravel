<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Profile extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $target_exam = '';
    public string $target_year = '';
    public string $college_stream = '';

    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->phone = $user->phone ?? '';
        $this->target_exam = $user->target_exam ?? '';
        $this->target_year = $user->target_year ?? '';
        $this->college_stream = $user->college_stream ?? '';
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{10}$/'],
            'target_exam' => ['nullable', 'string', 'max:50'],
            'target_year' => ['nullable', 'string', 'max:10'],
            'college_stream' => ['nullable', 'string', 'max:100'],
        ]);

        auth()->user()->update($validated);

        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $user = auth()->user();

        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed:new_password_confirmation'],
        ]);

        $user->update(['password' => $this->new_password]);

        $this->reset('current_password', 'new_password', 'new_password_confirmation');

        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.portal.profile');
    }
}
