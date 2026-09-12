<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\DepositPensionScheme;
use App\Models\FixedDepositScheme;
use App\Models\Loan;
use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Rules\StrongPassword;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->authorize('accessDashboard', Admin::class);

        $pageTitle = 'Dashboard';
        $admin     = Auth::user();

        $admin->load('roles');

        $passwordAlert = false;

        if ($admin->username == 'admin' || Hash::check('admin', $admin->password)) $passwordAlert = true;

        $widget['allUsersBalance']  = User::sum('balance');
        $widget['runningDpsTotal']  = DepositPensionScheme::running()
            ->selectRaw('SUM(per_installment * given_installment) AS dps_total')
            ->value('dps_total');
        $widget['runningFdsTotal']  = FixedDepositScheme::running()->sum('deposit_amount');
        $widget['runningLoanTotal'] = Loan::running()->sum('amount_requested');

        $pendingWidget['kycCount']           = User::kycPending()->count();
        $pendingWidget['loanCount']          = Loan::pending()->count();
        $pendingWidget['depositCount']       = Deposit::pending()->count();
        $pendingWidget['withdrawCount']      = Withdrawal::pending()->count();
        $pendingWidget['moneyTransferCount'] = MoneyTransfer::pending()->count();

        $dueWidget['dpsCount'] = DepositPensionScheme::running()
            ->whereHas('installments', function (Builder $builder) {
                $builder->whereDate('installment_date', '<', today())
                    ->whereNull('given_at');
            })
            ->count();

        $dueWidget['loanCount'] = Loan::running()
            ->whereHas('installments', function (Builder $builder) {
                $builder->whereDate('installment_date', '<', today())
                    ->whereNull('given_at');
            })
            ->count();

        $widget['runningDpsCount']  = DepositPensionScheme::running()->count();
        $widget['maturedDpsCount']  = DepositPensionScheme::matured()->count();
        $widget['runningFdsCount']  = FixedDepositScheme::running()->count();
        $widget['runningLoanCount'] = Loan::running()->count();

        // User Info
        $widget['totalUsers']             = User::count();
        $widget['activeUsers']            = User::active()->count();
        $widget['emailUnconfirmedUsers']  = User::emailUnconfirmed()->count();
        $widget['mobileUnconfirmedUsers'] = User::mobileUnconfirmed()->count();

        // Deposit Info
        $widget['depositDone']      = Deposit::done()->sum('amount');
        $widget['depositPending']   = Deposit::pending()->count();
        $widget['depositCancelled'] = Deposit::cancelled()->count();
        $widget['depositCharge']    = Deposit::done()->sum('charge');

        // Withdraw Info
        $widget['withdrawDone']      = Withdrawal::done()->sum('amount');
        $widget['withdrawPending']   = Withdrawal::pending()->count();
        $widget['withdrawCancelled'] = Withdrawal::cancelled()->count();
        $widget['withdrawCharge']    = Withdrawal::done()->sum('charge');

        // Monthly Deposit & Withdraw Report Graph
        $report['depositAmount']  = collect();
        $report['withdrawAmount'] = collect();

        $monthWiseDeposit = Deposit::where('status', ManageStatus::PAYMENT_SUCCESS)
            ->whereYear('created_at', now()->format('Y'))
            ->selectRaw('date_format(created_at, "%M") as month')
            ->selectRaw('sum(amount) as deposit_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthWiseWithdraw = Withdrawal::where('status', ManageStatus::PAYMENT_SUCCESS)
            ->whereYear('created_at', now()->format('Y'))
            ->selectRaw('date_format(created_at, "%M") as month')
            ->selectRaw('sum(amount) as withdraw_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            $deposit   = $monthWiseDeposit->firstWhere('month', $monthName);

            if ($deposit) $report['depositAmount']->push(intval($deposit->deposit_amount));
            else $report['depositAmount']->push(0);

            $withdraw = $monthWiseWithdraw->firstWhere('month', $monthName);

            if ($withdraw) $report['withdrawAmount']->push(intval($withdraw->withdraw_amount));
            else $report['withdrawAmount']->push(0);
        }

        $deposits    = $report['depositAmount']->toArray();
        $withdrawals = $report['withdrawAmount']->toArray();
        $latestTrx   = Transaction::with('user')->latest()->limit(9)->get();

        return view('admin.page.dashboard', compact('pageTitle', 'widget', 'latestTrx', 'passwordAlert', 'pendingWidget', 'dueWidget', 'deposits', 'withdrawals'));
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $admin     = auth()->user();

        return view('admin.page.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate()
    {
        $this->validate(request(), [
            'name'     => 'required|max:40',
            'email'    => 'required|email|max:40',
            'username' => 'required|max:40',
            'contact'  => 'required|max:40',
            'address'  => 'required|max:255',
            'image'    => File::types(['png', 'jpg', 'jpeg']),
        ]);

        $admin = auth()->user();

        if (request()->hasFile('image')) {
            try {
                $old          = $admin->image;
                $admin->image = fileUploader(request()->file('image'), getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (Exception) {
                $toast[] = ['error', 'Image upload failed'];

                return back()->with('toasts', $toast);
            }
        }

        $admin->name     = request('name');
        $admin->email    = $admin->hasRole('Super Admin') ? request('email') : $admin->email;
        $admin->username = $admin->hasRole('Super Admin') ? request('username') : $admin->username;
        $admin->contact  = request('contact');
        $admin->address  = request('address');
        $admin->save();

        $toast[] = ['success', 'Profile successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function passwordChange()
    {
        $this->validate(request(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', new StrongPassword],
        ]);

        $admin = auth()->user();

        if (!Hash::check(request('current_password'), $admin->password)) {
            $toast[] = ['error', 'Current password mismatched!'];

            return back()->with('toasts', $toast);
        }

        $admin->password = Hash::make(request('password'));
        $admin->save();

        $toast[] = ['success', 'Password successfully changed'];

        return back()->with('toasts', $toast);
    }

    public function allNotification()
    {
        $this->authorize('viewNotifications', Admin::class);

        $pageTitle     = 'Notifications';
        $notifications = AdminNotification::with('user')->orderBy('is_read')->paginate(getPaginate());

        return view('admin.page.notification', compact('pageTitle', 'notifications'));
    }

    public function notificationRead(int $id)
    {
        $this->authorize('markAsRead', Admin::class);

        $notification          = AdminNotification::findOrFail($id);
        $notification->is_read = ManageStatus::YES;
        $notification->save();

        $url = $notification->click_url;

        if ($url == '#') $url = url()->previous();

        return redirect($url);
    }

    public function notificationReadAll()
    {
        $this->authorize('markAllAsRead', Admin::class);

        AdminNotification::where('is_read', ManageStatus::NO)->update([
            'is_read' => ManageStatus::YES,
        ]);

        $toast[] = ['success', 'All notifications marked as read'];

        return back()->with('toasts', $toast);
    }

    public function notificationRemove(int $id)
    {
        $this->authorize('removeNotification', Admin::class);

        $notification = AdminNotification::findOrFail($id);
        $notification->delete();

        $toast[] = ['success', 'Notification has removed'];

        return back()->with('toasts', $toast);
    }

    public function notificationRemoveAll()
    {
        $this->authorize('removeAllNotification', Admin::class);

        AdminNotification::truncate();

        $toast[] = ['success', 'All notification has removed'];

        return back()->with('toasts', $toast);
    }

    public function transaction()
    {
        $this->authorize('viewTransactions', Admin::class);

        $pageTitle    = 'Transactions';
        $remarks      = Transaction::select('remark')->distinct()->orderBy('remark')->get('remark');
        $transactions = Transaction::with('user')
            ->searchable(['trx', 'user:username'])
            ->filter(['remark', 'trx_type'])
            ->dateFilter()
            ->latest()
            ->paginate(getPaginate());

        return view('admin.page.transaction', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function transactionEdit(Transaction $transaction)
    {
        $this->authorize('viewTransactions', Admin::class);

        $pageTitle = 'Edit Transaction';
        $transaction->load('user');

        return view('admin.page.transaction-edit', compact('pageTitle', 'transaction'));
    }

    public function transactionUpdate(Transaction $transaction)
    {
        $this->authorize('viewTransactions', Admin::class);

        $validated = request()->validate([
            'account_number' => ['required', 'string', 'max:60'],
            'trx'            => ['required', 'string', 'max:255', 'unique:transactions,trx,' . $transaction->id],
            'created_at'     => ['required', 'date'],
        ]);

        $transaction->update([
            'account_number' => trim($validated['account_number']),
            'trx'            => trim($validated['trx']),
            'created_at'     => $validated['created_at'],
        ]);

        return redirect()->route('admin.transaction.index')->with('toasts', [['success', 'Transaction successfully updated']]);
    }

    public function fileDownload()
    {
        $this->authorize('downloadFile', Admin::class);

        $path = request('filePath');
        $file = fileManager()->$path()->path . '/' . request('fileName');

        return response()->download($file);
    }
}
