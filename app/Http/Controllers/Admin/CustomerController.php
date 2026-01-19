<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if (!userCan('view customers')) {
            abort(403);
        }
        $customers = User::with('address')
            ->where('is_affiliate', false)
            ->latest()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->keyword . '%');
            })
            ->paginate(20);
        return view('admin.pages.customer.index', compact('customers'));
    }

    public function create()
    {
        if (!userCan('view customers')) {
            abort(403);
        }

        return view('admin.pages.customer.create');
    }

    public function store(Request $request)
    {
        if (!userCan('view customers')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'avatar' => $avatarPath ? 'storage/' . $avatarPath : null,
            ]);

            UserAddress::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'],
                'address' => $validated['address'],
            ]);

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            Log::error('Customer creation failed: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create customer. Please try again.');
        }
    }

    public function edit($id)
    {
        if (!userCan('view customers')) {
            abort(403);
        }

        $customer = User::with('address')->findOrFail($id);

        return view('admin.pages.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        if (!userCan('view customers')) {
            abort(403);
        }

        $customer = User::with('address')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($customer->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $customer->avatar))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $customer->avatar));
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = 'storage/' . $avatarPath;
            }

            $customer->update($updateData);

            // Update or create address
            $customer->address()->updateOrCreate(
                ['user_id' => $customer->id],
                [
                    'name' => $validated['name'],
                    'phone_number' => $validated['phone_number'],
                    'address' => $validated['address'],
                ]
            );

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            Log::error('Customer update failed: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update customer. Please try again.');
        }
    }

    public function destroy($id)
    {
        if (!userCan('view customers')) {
            abort(403);
        }

        $customer = User::findOrFail($id);

        // Delete avatar if exists
        if ($customer->avatar && Storage::disk('public')->exists(str_replace('storage/', '', $customer->avatar))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $customer->avatar));
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
