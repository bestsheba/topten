<?php

namespace App\Livewire\LandingPageConfig;

use Livewire\Component;

class FeaturesSection extends Component
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

    public function updateFeature($index, $field, $value)
    {
        if (!isset($this->data['features'])) {
            $this->data['features'] = [];
        }
        $this->data['features'][$index][$field] = $value;
        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'features', value: $this->data['features']);
    }

    public function render()
    {
        return view('livewire.landing-page-config.features-section');
    }
}
