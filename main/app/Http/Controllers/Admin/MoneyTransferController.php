<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\MoneyTransfer;
use App\Services\MoneyTransferService;
use Exception;
use Illuminate\Http\Request;

class MoneyTransferController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MoneyTransfer::class);

        $pageTitle      = 'All Money Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType();

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function pending()
    {
        $this->authorize('viewPendingMoneyTransfers', MoneyTransfer::class);

        $pageTitle      = 'Pending Money Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType('pending');

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function completed()
    {
        $this->authorize('viewCompletedMoneyTransfers', MoneyTransfer::class);

        $pageTitle      = 'Completed Money Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType('completed');

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function failed()
    {
        $this->authorize('viewFailedMoneyTransfers', MoneyTransfer::class);

        $pageTitle      = 'Failed Money Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType('failed');

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function internal()
    {
        $this->authorize('viewInternalMoneyTransfers', MoneyTransfer::class);

        $pageTitle      = 'Internal Money Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType('internal');

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function external()
    {
        $this->authorize('viewExternalMoneyTransfers', MoneyTransfer::class);

        $pageTitle      = 'External Money Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType('external');

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function wire()
    {
        $this->authorize('viewWireTransfers', MoneyTransfer::class);

        $pageTitle      = 'Wire Transfers';
        $moneyTransfers = MoneyTransferService::make()->fetchMoneyTransfersByType('wire');

        return view('admin.page.moneyTransfers', compact('pageTitle', 'moneyTransfers'));
    }

    public function download(Request $request, MoneyTransfer $moneyTransfer)
    {
        $this->authorize('downloadFile', $moneyTransfer);

        $file = decrypt($request->query('data'));

        try {
            if (!is_null($moneyTransfer->beneficiary_id)) {
                $moneyTransfer->load('beneficiary');

                return MoneyTransferService::make()->downloadFileFromPayload($moneyTransfer->beneficiary->details, $file);
            } else {
                return MoneyTransferService::make()->downloadFileFromPayload($moneyTransfer->wire_transfer_payload, $file);
            }
        } catch (Exception $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function complete(MoneyTransfer $moneyTransfer)
    {
        $this->authorize('markAsComplete', $moneyTransfer);

        if ($moneyTransfer->status != ManageStatus::MONEY_TRANSFER_PENDING) {
            return back()->with('toasts', [
                ['error', 'This money transfer is not in a pending state.']
            ]);
        }

        $moneyTransfer->update([
            'status' => ManageStatus::MONEY_TRANSFER_COMPLETED
        ]);

        MoneyTransferService::make()->sendMoneyTransferNotification($moneyTransfer, 'COMPLETE');

        return back()->with('toasts', [
            ['success', 'The transfer has been marked as completed.']
        ]);
    }

    public function fail(Request $request, MoneyTransfer $moneyTransfer)
    {
        $this->authorize('markAsFail', $moneyTransfer);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:20',
        ]);

        if ($moneyTransfer->status != ManageStatus::MONEY_TRANSFER_PENDING) {
            return back()->with('toasts', [
                ['error', 'This money transfer is not in a pending state.']
            ]);
        }

        $moneyTransfer->update([
            'status'           => ManageStatus::MONEY_TRANSFER_FAILED,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        MoneyTransferService::make()->refundUser($moneyTransfer);

        MoneyTransferService::make()->sendMoneyTransferNotification($moneyTransfer, 'FAIL');

        return back()->with('toasts', [
            ['success', 'The transfer has been marked as failed.']
        ]);
    }
}
