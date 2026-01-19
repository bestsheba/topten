<?php

namespace App\Services;

use App\Models\Product;

class ProductSkuGenerateService
{
    public function generateUniqueSku($productName)
    {
        // Get the first 3 letters of the product name, convert to uppercase
        $productInitials = strtoupper(substr($productName, 0, 3));

        // Generate a random number
        $randomNumber = mt_rand(1000, 9999);

        // Combine to form SKU
        $sku = $productInitials . '-' . $randomNumber;

        // Check the database for existing SKUs
        if (Product::where('sku', $sku)->exists()) {
            // Recursively call the method to generate another SKU
            return $this->generateUniqueSku($productName);
        }

        return $sku;
    }
}
