<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Onboarding extends Component
{
    public string $phone = '';
    public string $target_exam = 'CAT';
    public string $target_year = '2025';
    public string $college_stream = 'Engineering / B.Tech';

    protected function rules(): array
    {
        return [
            'phone' => 'required|string|min:10|max:15',
            'target_exam' => 'required|string',
            'target_year' => 'required|string',
            'college_stream' => 'required|string',
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->is_onboarded) {
            $this->redirectRoute('portal.catalog');
        }
    }

    public function saveProfile(): void
    {
        $this->validate();

        $user = Auth::user();
        if ($user) {
            $user->update([
                'phone' => $this->phone,
                'target_exam' => $this->target_exam,
                'target_year' => $this->target_year,
                'college_stream' => $this->college_stream,
                'is_onboarded' => true,
            ]);

            $this->redirectRoute('portal.catalog');
        }
    }

    public function render()
    {
        return view('livewire.portal.onboarding');
    }
}
