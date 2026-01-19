<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if (!userCan('view admins')) {
            abort(403);
        }

        $admins = Admin::latest()->paginate(20);
        return view('admin.pages.admin.index', compact('admins'));
    }

    public function create()
    {
        if (!userCan('create admin')) {
            abort(403);
        }

        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.pages.admin.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!userCan('create admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $roles = Role::whereIn('id', $request->roles)->get();
        $admin->syncRoles($roles);

        return redirect()
            ->route('admin.admin.index')
            ->with('success', 'Admin created successfully.');
    }

    public function edit(Admin $admin)
    {
        if (!userCan('edit admin')) {
            abort(403);
        }

        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.pages.admin.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        if (!userCan('edit admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        $roles = Role::whereIn('id', $request->roles)->get();
        $admin->syncRoles($roles);

        return redirect()
            ->route('admin.admin.index')
            ->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        if (!userCan('delete admin')) {
            abort(403);
        }

        if ($admin->hasRole('Super Admin')) {
            return redirect()
                ->route('admin.admin.index')
                ->with('error', 'Super Admin cannot be deleted.');
        }

        $admin->delete();

        return redirect()
            ->route('admin.admin.index')
            ->with('success', 'Admin deleted successfully.');
    }
}
