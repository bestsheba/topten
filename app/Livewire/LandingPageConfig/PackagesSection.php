<?php

namespace App\Livewire\LandingPageConfig;

use App\Models\Product;
use Livewire\Component;

class PackagesSection extends Component
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

    public function updatePackage($index, $field, $value)
    {
        if (!isset($this->data['packages'])) {
            $this->data['packages'] = [];
        }
        $this->data['packages'][$index][$field] = $value;
        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'packages', value: $this->data['packages']);
    }

    public function updatePackageProduct($index, $productId)
    {
        if (!isset($this->data['packages'])) {
            $this->data['packages'] = [];
        }

        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->data['packages'][$index] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->picture_url,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'badge' => '⭐ পণ্য নির্বাচিত',
                ];
            }
        }

        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'packages', value: $this->data['packages']);
    }

    public function addPackage()
    {
        if (!isset($this->data['packages'])) {
            $this->data['packages'] = [];
        }

        $this->data['packages'][] = [
            'product_id' => null,
            'name' => '',
            'image' => '',
            'badge' => '',
        ];

        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'packages', value: $this->data['packages']);
    }

    public function removePackage($index)
    {
        if (isset($this->data['packages'][$index])) {
            unset($this->data['packages'][$index]);
            // Reindex array
            $this->data['packages'] = array_values($this->data['packages']);
            $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'packages', value: $this->data['packages']);
        }
    }

    public function render()
    {
        return view('livewire.landing-page-config.packages-section', [
            'products' => Product::where('is_active', true)->get(['id', 'name', 'picture', 'price']),
        ]);
    }
}
