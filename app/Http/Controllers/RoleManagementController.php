<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);
        $role = Role::create(['name' => $validated['name']]);
        if ($validated['permissions']) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray(); // Get permission names from IDs
            $role->syncPermissions($permissions);
        }
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);
        $role->update(['name' => $validated['name']]);
        if ($validated['permissions']) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
    public function assignRoleToUser(Request $request, User $user)
    {
        $user->syncRoles($request->input('roles'));
        return redirect()->back()->with('success', 'Roles assigned successfully.');
    }
}
