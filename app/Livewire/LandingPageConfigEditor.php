<?php

namespace App\Livewire;

use App\Models\LandingPage;
use App\Helpers\LandingPageConfig;
use Livewire\Component;
use Livewire\Attributes\Computed;

class LandingPageConfigEditor extends Component
{
    public LandingPage $landingPage;
    public array $config = [];
    public string $activeTab = 'common';
    public array $errors = [];

    public function mount(LandingPage $landingPage)
    {
        $this->landingPage = $landingPage;
        $this->config = $landingPage->config ?? LandingPageConfig::getDefaultConfig();

        // Listen for updates from child components
        $this->dispatch('register-listeners');
    }

    #[\Livewire\Attributes\On('update-section-data')]
    public function handleSectionDataUpdate($sectionId, $field, $value)
    {
        $this->updateSectionData($sectionId, $field, $value);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updateCommon($field, $value)
    {
        $this->config['common'][$field] = $value;
    }

    public function updateSectionData($sectionId, $field, $value)
    {
        foreach ($this->config['sections'] as &$section) {
            if ($section['id'] === $sectionId) {
                $this->setNestedArray($section['data'], $field, $value);
                break;
            }
        }
    }

    public function toggleSection($sectionId)
    {
        foreach ($this->config['sections'] as &$section) {
            if ($section['id'] === $sectionId) {
                $section['enabled'] = !($section['enabled'] ?? false);
                break;
            }
        }
    }

    public function saveConfig()
    {
        try {
            $this->landingPage->update(['config' => $this->config]);
            session()->flash('flash_message', [
                'type' => 'success',
                'message' => '✅ Config saved successfully!'
            ]);
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('flash_message', [
                'type' => 'error',
                'message' => '❌ Error saving config: ' . $e->getMessage()
            ]);
            $this->dispatch('$refresh');
        }
    }

    public function resetToDefault()
    {
        $this->config = LandingPageConfig::getDefaultConfig();
        session()->flash('flash_message', [
            'type' => 'info',
            'message' => '🔄 Reset to default config'
        ]);
        $this->dispatch('$refresh');
    }

    private function setNestedArray(&$array, $path, $value)
    {
        $keys = explode('.', $path);
        $current = &$array;

        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current = $value;
    }

    public function render()
    {
        return view('livewire.landing-page-config-editor', [
            'sections' => $this->config['sections'] ?? [],
            'common' => $this->config['common'] ?? [],
        ]);
    }
}
