<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        $permissions = Permission::where('is_active', true)->get()->groupBy('module');
        return view('role-access.role-permissions', compact('roles', 'permissions'));
    }

    public function show(Role $role)
    {
        return response()->json([
            'role_id' => $role->id,
            'role_name' => $role->display_name,
            'permission_ids' => $role->permissions->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('role-permissions.index')->with('success', 'Permission role berhasil diperbarui!');
    }
}
