<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class ProductQuickViewButton extends Component
{
    public $product;
    public $uniqueId;
    public $spinner = false;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function openQuickView()
    {
        $this->toggleSpinner(true);
        $this->dispatch('open-quick-view', product: $this->product);
    }

    public function toggleSpinner($value)
    {
        $this->spinner = $value;
    }

    #[On('off-spinner')]
    public function offSpinner()
    {
        $this->spinner = false;
    }

    public function render()
    {
        return view('livewire.product-quick-view-button', [
            'uniqueId' => $this->uniqueId
        ]);
    }
}
