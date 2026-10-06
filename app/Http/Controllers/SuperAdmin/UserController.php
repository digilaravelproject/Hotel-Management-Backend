<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'permissions'])->latest();

        // Search by name, email, or phone
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $role = $request->input('role');
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::where('guard_name', 'web')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        }

        return view('super_admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::where('guard_name', 'web')->get();
        $permissions = Permission::where('guard_name', 'web')->get()->groupBy(function ($item) {
            return explode('.', $item->name)[0] ?? 'general';
        });

        return view('super_admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'status' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => $request->has('status') ? $request->boolean('status') : true,
        ]);

        // Assign Spatie Roles
        $user->syncRoles($validated['roles']);

        // Assign optional direct permissions if provided
        if (!empty($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => $user->load('roles', 'permissions'),
            ], 201);
        }

        return redirect()->route('super-admin.users.index')
                         ->with('success', "User '{$user->name}' created successfully with assigned roles.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($id);
        $roles = Role::where('guard_name', 'web')->get();
        $permissions = Permission::where('guard_name', 'web')->get()->groupBy(function ($item) {
            return explode('.', $item->name)[0] ?? 'general';
        });

        $userRoles = $user->roles->pluck('name')->toArray();
        $userDirectPermissions = $user->getDirectPermissions()->pluck('name')->toArray();

        return view('super_admin.users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userDirectPermissions'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'status' => 'nullable|boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $request->has('status') ? $request->boolean('status') : $user->status,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Sync Roles
        $user->syncRoles($validated['roles']);

        // Sync Direct Permissions
        $user->syncPermissions($validated['permissions'] ?? []);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => $user->fresh()->load('roles', 'permissions'),
            ]);
        }

        return redirect()->route('super-admin.users.index')
                         ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $user->status,
                'message' => 'User status toggled.',
            ]);
        }

        $statusText = $user->status ? 'activated' : 'deactivated';
        return back()->with('success', "User '{$user->name}' has been {$statusText}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self deletion if authenticated as this user
        if (auth()->id() === $user->id) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
            }
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "User '{$userName}' deleted successfully.",
            ]);
        }

        return redirect()->route('super-admin.users.index')
                         ->with('success', "User '{$userName}' deleted successfully.");
    }
}
