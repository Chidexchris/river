<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStaffRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminStaffController extends Controller
{
    public function index()
    {
        $this->authorize('viewAdmins', Admin::class);

        $pageTitle = 'Admin Staffs';
        $admins    = Admin::with('roles')->searchable(['name'])->paginate(getPaginate());
        $roles     = Role::whereNot('name', '=', 'Super Admin')->orderBy('name')->get();

        return view('admin.page.adminStaffs', compact('pageTitle', 'admins', 'roles'));
    }

    public function store(AdminStaffRequest $request)
    {
        $validated             = $request->validated();
        $rawPassword           = $validated['password'];
        $validated['password'] = Hash::make($rawPassword);
        $dataWithoutRoles      = array_diff_key($validated, array_flip(['roles']));

        $admin = Admin::create($dataWithoutRoles);
        $roles = [];

        foreach ($validated['roles'] as $roleId) {
            $roles[] = Role::findById($roleId, 'admin')->name;
        }

        $admin->syncRoles($roles);

        // notify new admin
        notify($admin, 'NEW_ADMIN_STAFF', [
            'password' => $rawPassword,
            'url'      => route('admin.login.form')
        ], ['email']);

        $toast[] = ['success', 'New staff has been successfully created'];

        return back()->with('toasts', $toast);
    }

    public function update(AdminStaffRequest $request, Admin $admin)
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validated['password'] = $admin->password;
        }

        $dataWithoutRoles = array_diff_key($validated, array_flip(['roles']));

        $admin->update($dataWithoutRoles);

        $roles = [];

        foreach ($validated['roles'] as $roleId) {
            $roles[] = Role::findById($roleId, 'admin')->name;
        }

        $admin->syncRoles($roles);

        $toast[] = ['success', 'Staff has been successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeAdminStatus', Admin::class);

        return Admin::changeStatus($id);
    }

    public function staffLogin(Admin $admin)
    {
        $this->authorize('loginAsAdmin', $admin);

        Auth::guard('admin')->login($admin);

        return to_route('admin.dashboard');
    }
}
