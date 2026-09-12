<?php

namespace App\Http\Controllers\Staff;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Transaction;
use App\Rules\StrongPassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class StaffController extends Controller
{
    public function dashboard()
    {
        return isManager() ? $this->managerDashboard() : $this->accountOfficerDashboard();
    }

    private function managerDashboard()
    {
        $pageTitle = 'Manager Dashboard';
        $staff     = $this->getStaff();

        $this->loadStaffRelations($staff);

        return view('staff.dashboard.manager', compact('pageTitle', 'staff'));
    }

    private function accountOfficerDashboard()
    {
        $pageTitle = 'Account Officer Dashboard';
        $staff     = $this->getStaff();

        $this->loadStaffRelations($staff);

        return view('staff.dashboard.accountOfficer', compact('pageTitle', 'staff'));
    }

    private function getStaff(): Staff
    {
        return auth('staff')->user();
    }

    private function loadStaffRelations(Staff $staff)
    {
        $staff->load([
            'branches' => fn($query) => $query->active()
                ->select('id', 'name', 'address')
                ->withSum([
                    'deposits'    => fn($query) => $query->done()->whereDate('created_at', today()),
                    'withdrawals' => fn($query) => $query->done()->whereDate('created_at', today())
                ], 'amount')
                ->withCount([
                    'users' => fn($query) => $query->active()->whereDate('created_at', today())
                ])
                ->when($staff->designation == ManageStatus::BRANCH_MANAGER, function ($query) {
                    $query->with([
                        'transactions' => fn($innerQuery) => $innerQuery->with(['user', 'staff'])->latest()->take(10)
                    ]);
                })
                ->when($staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER, function ($query) {
                    $query->with([
                        'deposits'    => fn($innerQuery) => $innerQuery->with('user:id,account_number')
                            ->select('id', 'user_id', 'branch_id', 'amount', 'trx', 'created_at')
                            ->done()
                            ->latest()
                            ->take(10),
                        'withdrawals' => fn($innerQuery) => $innerQuery->with('user:id,account_number')
                            ->select('id', 'user_id', 'branch_id', 'amount', 'trx', 'created_at')
                            ->done()
                            ->latest()
                            ->take(10)
                    ]);
                })
        ]);
    }

    public function switchBranch(Request $request)
    {
        session()->put('branchId', $request->input('branch'));

        return redirect()->back();
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $staff     = $this->getStaff();

        return view('staff.page.profile', compact('pageTitle', 'staff'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:40',
            'image'   => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'address' => 'required|string|max:255',
        ]);

        $staff = $this->getStaff();

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = fileUploader(
                    $request->file('image'),
                    getFilePath('staffProfile'),
                    getFileSize('staffProfile'),
                    $staff->image
                );
            } catch (Exception $exception) {
                $toast[] = ['error', "Image uploading process has failed. {$exception->getMessage()}"];

                return back()->with('toasts', $toast);
            }
        }

        $staff->update(array_filter($validated));

        $toast[] = ['success', 'Your profile has updated'];

        return back()->with('toasts', $toast);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', new StrongPassword],
        ]);

        $staff = $this->getStaff();

        if (!Hash::check($validated['current_password'], $staff->password)) {
            $toast[] = ['error', 'Current password mismatched!'];

            return back()->with('toasts', $toast);
        }

        $staff->update([
            'password' => Hash::make($validated['password'])
        ]);

        $toast[] = ['success', 'Your password has been changed'];

        return back()->with('toasts', $toast);
    }

    public function branches()
    {
        $pageTitle = 'Assigned Branches';
        $staff     = $this->getStaff();

        if (!isManager()) abort(Response::HTTP_FORBIDDEN);

        $staff->load('branches');
        $branches = $staff->branches;

        return view('staff.page.branches', compact('pageTitle', 'staff', 'branches'));
    }

    public function transactions()
    {
        $staff = $this->getStaff();

        $staff->load('branches');

        $branches  = $staff->branches;
        $branch    = session()->has('branchId') ? $branches->find(session('branchId')) : $branches->first();
        $pageTitle = "Transactions Conducted at the $branch->name";

        $transactions = Transaction::with('user')
            ->where('branch_id', $branch->id)
            ->searchable(['user:account_number', 'staff:name', 'trx'])
            ->dateFilter()
            ->latest()
            ->paginate(getPaginate());

        if (isManager()) $transactions->load('staff');

        return view('staff.page.transactions', compact('staff', 'pageTitle', 'transactions'));
    }
}
