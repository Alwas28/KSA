<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('roles')->latest()->get()->groupBy('module');
        return view('role-access.permissions', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan!');
    }

    public function show(Permission $permission)
    {
        $permission->load('roles');
        return response()->json($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $permission->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'module' => $validated['module'] ?? null,
            'is_active' => $validated['is_active'] ?? $permission->is_active,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui!');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')->with('error', 'Permission tidak dapat dihapus karena masih digunakan oleh role!');
        }

        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus!');
    }
}
