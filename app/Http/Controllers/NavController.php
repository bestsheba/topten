<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NavController extends Controller
{
    public function getNavigation()
    {
        $categories = Category::with('activeSubCategories')
            ->where('is_active', true)
            ->ordered()
            ->get();
        return response()->json($categories);
    }
}
