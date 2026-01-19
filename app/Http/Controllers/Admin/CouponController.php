<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        if (!userCan('manage coupons')) {
            abort(403);
        }
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.pages.coupon.index', compact('coupons'));
    }

    public function create()
    {
        if (!userCan('manage coupons')) {
            abort(403);
        }
        return view('admin.pages.coupon.create');
    }

    public function store(Request $request)
    {
        if (!userCan('manage coupons')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|unique:coupons,code',
            'start_date' => 'required',
            'expired_date' => 'required',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:amount,percentage',
        ]);

        Coupon::create([
            'title' => $request->title,
            'code' =>  $request->code,
            'start_date' => Carbon::parse($request->start_date),
            'expired_date' =>  Carbon::parse($request->expired_date),
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        if (!userCan('manage coupons')) {
            abort(403);
        }
        return view('admin.pages.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        if (!userCan('manage coupons')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|unique:coupons,code,' . $coupon->id,
            'start_date' => 'required',
            'expired_date' => 'required',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:amount,percentage',
        ]);

        $coupon->update([
            'title' => $request->title,
            'code' =>  $request->code,
            'start_date' => Carbon::parse($request->start_date),
            'expired_date' =>  Carbon::parse($request->expired_date),
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        if (!userCan('manage coupons')) {
            abort(403);
        }
        $coupon->delete();
        return redirect()->route('admin.coupon.index')->with('success', 'Coupon deleted successfully.');
    }
}
