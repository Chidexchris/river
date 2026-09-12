<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $pageTitle = 'Roles';
        $roles     = Role::whereNot('name', '=', 'Super Admin')->latest()->paginate(getPaginate());

        return view('admin.role.index', compact('pageTitle', 'roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);

        $pageTitle   = 'Create Role';
        $permissions = Permission::all()->groupBy('module');

        return view('admin.role.create', compact('pageTitle', 'permissions'));
    }

    public function store(RoleRequest $request)
    {
        $validated = $request->validated();

        $role = Role::create([
            'name' => $validated['name'],
        ]);

        if ($request->filled('permissions')) {
            $permissions = [];

            foreach ($validated['permissions'] as $permissionId) {
                $permissions[] = Permission::findById($permissionId, 'admin')->name;
            }

            $role->syncPermissions($permissions);
        }

        $toast[] = ['success', 'New role has been successfully created'];

        return to_route('admin.roles.index')->with('toasts', $toast);
    }

    public function edit(Role $role)
    {
        $this->authorize('edit', $role);

        $pageTitle = 'Edit Role';

        $role->load('permissions');

        $hasPermissions = $role->permissions->pluck('id')->toArray();
        $permissions    = Permission::all()->groupBy('module');

        return view('admin.role.edit', compact('pageTitle', 'role', 'permissions', 'hasPermissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $validated = $request->validated();

        $role->update(['name' => $validated['name']]);

        if ($request->filled('permissions')) {
            $permissions = [];

            foreach ($validated['permissions'] as $permissionId) {
                $permissions[] = Permission::findById($permissionId, 'admin')->name;
            }

            $role->syncPermissions($permissions);
        }

        $toast[] = ['success', 'Role has been successfully updated'];

        return back()->with('toasts', $toast);
    }
}
