<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AffiliateUserController extends Controller
{
    public function index(Request $request)
    {
        if (!userCan('view customers')) {
            abort(403);
        }
        $affiliateUsers = User::with('address')
            ->where('is_affiliate', true)
            ->latest()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->keyword . '%');
            })
            ->paginate(20);
        return view('admin.pages.customer.affiliate-users', compact('affiliateUsers'));
    }
}
