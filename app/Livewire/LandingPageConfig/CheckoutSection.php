<?php

namespace App\Livewire\LandingPageConfig;

use Livewire\Component;

class CheckoutSection extends Component
{
    public array $section = [];
    public array $data = [];

    public function mount()
    {
        $this->data = $this->section['data'] ?? [];
    }

    public function updateData($field, $value)
    {
        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: $field, value: $value);
        $this->data[$field] = $value;
    }

    public function render()
    {
        return view('livewire.landing-page-config.checkout-section');
    }
}
