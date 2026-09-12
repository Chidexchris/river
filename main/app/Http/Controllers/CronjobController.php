<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Constants\ManageStatus;
use App\Models\DepositPensionScheme;
use App\Models\FixedDepositScheme;
use App\Models\Installment;
use App\Models\Loan;
use App\Services\DepositPensionSchemeService;
use App\Services\FixedDepositSchemeService;
use App\Services\LoanService;
use Illuminate\Database\Eloquent\Builder;

class CronjobController extends Controller
{
    function cleanTemporaryUploads() {
        $setting = bs();
        $setting->clean_cron = now();
        $setting->save();

        $files = Storage::files('temp_uploads');

        $expiryTime   = now()->subMinutes(30)->timestamp;
        $expiredFiles = array_filter($files, fn($file) => Storage::lastModified($file) < $expiryTime);

        if (!empty($expiredFiles)) Storage::delete($expiredFiles);

        echo "EXECUTED";
    }

    function processDpsInstallments() {
        $setting = bs();
        $setting->dps_cron = now();
        $setting->save();

        Installment::where('installmentable_type', '=', DepositPensionScheme::class)
                    ->whereHas('installmentable', function (Builder $query) {
                        $query->where('status', '=', ManageStatus::DPS_RUNNING);
                    })
                    ->with('installmentable.user')
                    ->whereDate('installment_date', '<=', today())
                    ->whereNull('given_at')
                    ->chunk(100, function ($installments) {
                        foreach ($installments as $installment) {
                            DepositPensionSchemeService::make()->processInstallment($installment);
                        }
                    });

        echo "EXECUTED";
    }

    function processFdsInstallments() {
        $setting = bs();
        $setting->fds_cron = now();
        $setting->save();

        FixedDepositScheme::whereDate('next_installment_date', '<=', today())
                            ->running()
                            ->chunk(100, function ($fdsList) {
                                foreach ($fdsList as $fds) {
                                    FixedDepositSchemeService::make()->processInstallment($fds);
                                }
                            });
                            
        echo "EXECUTED";
    }

    function processLoanInstallments() {
        $setting = bs();
        $setting->loan_cron = now();
        $setting->save();

        Installment::where('installmentable_type', '=', Loan::class)
                    ->whereHas('installmentable', function (Builder $query) {
                        $query->where('status', '=', ManageStatus::LOAN_RUNNING);
                    })
                    ->with('installmentable.user')
                    ->whereDate('installment_date', '<=', today())
                    ->whereNull('given_at')
                    ->chunk(100, function ($installments) {
                        foreach ($installments as $installment) {
                            LoanService::make()->processInstallment($installment);
                        }
                    });

        echo "EXECUTED";
    }
}
