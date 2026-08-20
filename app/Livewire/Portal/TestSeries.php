<?php

declare(strict_types=1);

namespace App\Livewire\Portal;

use App\Models\Package;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TestSeries extends Component
{
    public function render()
    {
        return view('livewire.portal.test-series', [
            'packages' => Package::where('is_published', true)->withCount('tests')->orderBy('is_free')->get(),
        ]);
    }
}
