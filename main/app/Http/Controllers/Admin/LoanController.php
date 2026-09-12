<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class LoanController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Loan::class);

        $pageTitle = 'All Loans';
        $loanList  = LoanService::make()->fetchLoansByType();

        return view('admin.loan.list', compact('pageTitle', 'loanList'));
    }

    public function pending()
    {
        $this->authorize('viewPendingLoans', Loan::class);

        $pageTitle = 'Pending Loans';
        $loanList  = LoanService::make()->fetchLoansByType('pending');

        return view('admin.loan.list', compact('pageTitle', 'loanList'));
    }

    public function running()
    {
        $this->authorize('viewRunningLoans', Loan::class);

        $pageTitle = 'Running Loans';
        $loanList  = LoanService::make()->fetchLoansByType('running');

        return view('admin.loan.list', compact('pageTitle', 'loanList'));
    }

    public function lateInstallment()
    {
        $this->authorize('viewLateInstallmentLoans', Loan::class);

        $pageTitle = 'Late Installment Loans';
        $loanList  = LoanService::make()->fetchLoansByType('late_installment');

        return view('admin.loan.list', compact('pageTitle', 'loanList'));
    }

    public function paid()
    {
        $this->authorize('viewPaidLoans', Loan::class);

        $pageTitle = 'Paid Loans';
        $loanList  = LoanService::make()->fetchLoansByType('paid');

        return view('admin.loan.list', compact('pageTitle', 'loanList'));
    }

    public function rejected()
    {
        $this->authorize('viewRejectedLoans', Loan::class);

        $pageTitle = 'Rejected Loans';
        $loanList  = LoanService::make()->fetchLoansByType('rejected');

        return view('admin.loan.list', compact('pageTitle', 'loanList'));
    }

    public function download(Request $request, Loan $loan)
    {
        $this->authorize('downloadFile', $loan);

        $file = decrypt($request->query('data'));

        try {
            return LoanService::make()->downloadFileFromPayload($loan->form_data, $file);
        } catch (Exception $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function approveLoan(Loan $loan)
    {
        $this->authorize('markAsApprove', $loan);

        try {
            DB::transaction(function () use ($loan) {
                $user = User::lockForUpdate()->findOrFail($loan->user_id);

                LoanService::make()->processLoanApproval($loan, $user);
            });

            return back()->with('toasts', [
                ['success', 'The loan has been successfully approved.']
            ]);
        } catch (Throwable $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function rejectLoan(Request $request, Loan $loan)
    {
        $this->authorize('markAsReject', $loan);

        $validated = $request->validate([
            'admin_feedback' => 'required|string|min:10|max:255',
        ]);

        $loan->load('user');

        LoanService::make()->processLoanRejection($loan, $validated);

        return back()->with('toasts', [
            ['success', 'The loan has been successfully rejected.']
        ]);
    }

    public function installments(Loan $loan)
    {
        $this->authorize('viewLoanInstallments', $loan);

        $pageTitle    = 'Loan Installments';
        $installments = LoanService::make()->transformInstallment(
            $loan->installments()->paginate(getPaginate())
        );

        $loan->load('user:id,account_number');

        return view('admin.loan.installments', compact('pageTitle', 'loan', 'installments'));
    }
}
