<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = true;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();
            if (!$user->is_onboarded && $user->isStudent()) {
                $this->redirectRoute('portal.onboarding');
                return;
            }

            if ($user->isAdmin()) {
                $this->redirectRoute('admin.dashboard');
                return;
            }

            $this->redirectRoute('portal.catalog');
            return;
        }

        $this->addError('email', 'Invalid credentials provided.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
