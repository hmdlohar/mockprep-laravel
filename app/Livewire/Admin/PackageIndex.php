<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Package;
use App\Models\Test;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class PackageIndex extends Component
{
    public bool $showModal = false;
    public ?int $editingPackageId = null;

    public string $title = '';
    public string $description = '';
    public float $price = 999.0;
    public ?int $validity_days = null;
    public bool $is_free = false;
    public bool $is_published = true;
    public array $selectedTests = [];

    public function openCreateModal(): void
    {
        $this->editingPackageId = null;
        $this->title = '';
        $this->description = '';
        $this->price = 999.0;
        $this->validity_days = null;
        $this->is_free = false;
        $this->is_published = true;
        $this->selectedTests = [];
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $package = Package::with('tests')->findOrFail($id);
        $this->editingPackageId = $package->id;
        $this->title = $package->title;
        $this->description = $package->description ?? '';
        $this->price = (float) $package->price;
        $this->validity_days = $package->validity_days;
        $this->is_free = $package->is_free;
        $this->is_published = $package->is_published;
        $this->selectedTests = $package->tests->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'price' => $this->is_free ? 0.0 : $this->price,
            'validity_days' => $this->validity_days,
            'is_free' => $this->is_free,
            'is_published' => $this->is_published,
        ];

        if ($this->editingPackageId) {
            $package = Package::findOrFail($this->editingPackageId);
            $package->update($data);
            $package->tests()->sync($this->selectedTests);
            session()->flash('message', 'Test Series Package updated.');
        } else {
            $package = Package::create($data);
            $package->tests()->sync($this->selectedTests);
            session()->flash('message', 'New Test Series Package published.');
        }

        $this->showModal = false;
    }

    public function deletePackage(int $id): void
    {
        Package::findOrFail($id)->delete();
        session()->flash('message', 'Package deleted.');
    }

    public function render()
    {
        return view('livewire.admin.package-index', [
            'packages' => Package::withCount(['tests', 'users'])->latest()->get(),
            'availableTests' => Test::where('is_published', true)->get(),
        ]);
    }
}
