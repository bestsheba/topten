<?php

namespace App\Livewire\LandingPageConfig;

use Livewire\Component;
use Livewire\WithFileUploads;

class TestimonialsSection extends Component
{
    use WithFileUploads;

    public array $section = [];
    public array $data = [];
    public array $tempImages = [];

    public function mount()
    {
        $this->data = $this->section['data'] ?? [];
    }

    public function updateData($field, $value)
    {
        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: $field, value: $value);
        $this->data[$field] = $value;
    }

    public function updateTestimonial($index, $field, $value)
    {
        if (!isset($this->data['testimonials'])) {
            $this->data['testimonials'] = [];
        }
        $this->data['testimonials'][$index][$field] = $value;
        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'testimonials', value: $this->data['testimonials']);
    }

    public function updatedTempImages($value, $key)
    {
        // Extract index from key (e.g., "0" from "tempImages.0")
        $index = (int) $key;
        
        if (!isset($this->data['testimonials'])) {
            $this->data['testimonials'] = [];
        }

        if ($value && isset($this->tempImages[$index])) {
            // Validate file
            $this->validate([
                "tempImages.{$index}" => 'image|max:2048', // 2MB max
            ], [
                "tempImages.{$index}.image" => 'File must be an image',
                "tempImages.{$index}.max" => 'Image size must be less than 2MB',
            ]);

            // Delete old image if exists
            if (isset($this->data['testimonials'][$index]['image']) && $this->data['testimonials'][$index]['image']) {
                $oldImage = $this->data['testimonials'][$index]['image'];
                // Only delete if it's a local file (starts with storage/)
                if (strpos($oldImage, 'storage/') === 0) {
                    deleteFile($oldImage);
                }
            }

            // Upload new image
            $imagePath = uploadImage($this->tempImages[$index], 'landing/testimonials');
            
            // Update testimonial image
            $this->data['testimonials'][$index]['image'] = $imagePath;
            $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'testimonials', value: $this->data['testimonials']);
            
            // Clear temp image
            unset($this->tempImages[$index]);
        }
    }

    public function addTestimonial()
    {
        if (!isset($this->data['testimonials'])) {
            $this->data['testimonials'] = [];
        }

        $this->data['testimonials'][] = [
            'image' => '',
            'rating' => '⭐⭐⭐⭐⭐',
            'text' => 'নতুন রিভিউ',
        ];

        $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'testimonials', value: $this->data['testimonials']);
    }

    public function removeTestimonial($index)
    {
        if (isset($this->data['testimonials'][$index])) {
            unset($this->data['testimonials'][$index]);
            // Reindex array
            $this->data['testimonials'] = array_values($this->data['testimonials']);
            $this->dispatch('update-section-data', sectionId: $this->section['id'], field: 'testimonials', value: $this->data['testimonials']);
        }
    }

    public function render()
    {
        return view('livewire.landing-page-config.testimonials-section');
    }
}
