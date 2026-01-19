<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if (!userCan('view roles')) {
            abort(403);
        }
        $roles = Role::where('guard_name', 'admin')->latest()->paginate(20);
        return view('admin.pages.role.index', compact('roles'));
    }

    public function create()
    {
        if (!userCan('create role')) {
            abort(403);
        }
        $permissions = Permission::where('guard_name', 'admin')
            ->orderBy('group')
            ->get()
            ->groupBy('group');
        return view('admin.pages.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!userCan('create role')) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'admin'
        ]);

        // Get the actual permission instances
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()
            ->route('admin.role.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        // if (!userCan('edit role')) {
        //     abort(403);
        // }
        // if ($role->name === 'Super Admin') {
        //     return redirect()
        //         ->route('admin.role.index')
        //         ->with('error', 'Super Admin role cannot be edited.');
        // }

        $permissions = Permission::where('guard_name', 'admin')
            ->orderBy('group')
            ->get()
            ->groupBy('group');

        return view('admin.pages.role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // if (!userCan('edit role')) {
        //     abort(403);
        // }
        // if ($role->name === 'Super Admin') {
        //     return redirect()
        //         ->route('admin.role.index')
        //         ->with('error', 'Super Admin role cannot be updated.');
        // }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        // Get the actual permission instances
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()
            ->route('admin.role.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (!userCan('delete role')) {
            abort(403);
        }
        if ($role->name === 'Super Admin') {
            return redirect()
                ->route('admin.role.index')
                ->with('error', 'Super Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()
            ->route('admin.role.index')
            ->with('success', 'Role deleted successfully.');
    }
}
