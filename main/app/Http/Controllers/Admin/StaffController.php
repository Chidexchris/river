<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;
use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Staff::class);

        $pageTitle = 'Branch Staffs';
        $staffs    = Staff::with('branches')
            ->searchable(['name', 'username', 'email_address', 'contact_number'])
            ->latest()
            ->paginate(getPaginate());

        return view('admin.branchStaff.index', compact('pageTitle', 'staffs'));
    }

    public function create()
    {
        $this->authorize('create', Staff::class);

        $pageTitle = 'Create Branch Staff';
        $branches  = Branch::active()->orderBy('name')->get();

        return view('admin.branchStaff.create', compact('pageTitle', 'branches'));
    }

    public function store(StaffRequest $request)
    {
        $validated             = $request->validated();
        $rawPassword           = $validated['password'];
        $validated['password'] = Hash::make($rawPassword);
        $dataWithoutBranchIds  = array_diff_key($validated, array_flip(['branch_ids']));

        $staff = Staff::create($dataWithoutBranchIds);

        $staff->branches()->attach($validated['branch_ids']);

        // notify new branch staff
        notify($staff, 'NEW_BRANCH_STAFF', [
            'password' => $rawPassword,
            'url'      => route('staff.login.form')
        ], ['email']);

        $toast[] = ['success', 'New branch staff has been successfully created'];

        return to_route('admin.branch.staffs.index')->with('toasts', $toast);
    }

    public function edit(Staff $staff)
    {
        $this->authorize('edit', $staff);

        $pageTitle = 'Edit Branch Staff';
        $staff->load('branches');

        $branches = Branch::active()->orderBy('name')->get();

        return view('admin.branchStaff.edit', compact('pageTitle', 'branches', 'staff'));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validated['password'] = $staff->password;
        }

        $dataWithoutBranchIds = array_diff_key($validated, array_flip(['branch_ids']));

        $staff->update($dataWithoutBranchIds);

        $staff->branches()->sync($validated['branch_ids']);

        $toast[] = ['success', 'Branch staff has been successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeStaffStatus', Staff::class);

        return Staff::changeStatus($id);
    }

    public function staffLogin(Staff $staff)
    {
        $this->authorize('loginAsStaff', $staff);

        Auth::guard('staff')->login($staff);

        return to_route('staff.dashboard');
    }
}
