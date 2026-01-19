<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteToggle extends Component
{
    public int $productId;
    public bool $isFavorite = false;

    public function mount(int $productId, bool $isFavorite = false): void
    {
        $this->productId = $productId;
        $this->isFavorite = $isFavorite || ($this->userHasFavorited());
    }

    private function userHasFavorited(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $user = Auth::user();
        if (!$user || !($user instanceof User) || !method_exists($user, 'favorites')) {
            return false;
        }
        return $user->favorites()->where('product_id', $this->productId)->exists();
    }

    public function toggle(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $user = Auth::user();
        if (!$user || !($user instanceof User) || !method_exists($user, 'favorites')) {
            return;
        }

        // Ensure product exists
        $product = Product::find($this->productId);
        if (!$product) {
            return;
        }

        $exists = $user->favorites()->where('product_id', $this->productId)->exists();
        if ($exists) {
            $user->favorites()->detach($this->productId);
            $this->isFavorite = false;
        } else {
            $user->favorites()->attach($this->productId);
            $this->isFavorite = true;
        }
    }

    public function render()
    {
        return view('livewire.favorite-toggle');
    }
}


