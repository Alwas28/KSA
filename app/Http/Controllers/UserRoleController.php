<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::where('is_active', true)->withCount('permissions')->get();
        return view('role-access.user-roles', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'role_ids' => $user->roles->pluck('id')->toArray(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        } else {
            $user->roles()->detach();
        }

        return redirect()->route('user-roles.index')->with('success', 'Role user berhasil diperbarui!');
    }
}
