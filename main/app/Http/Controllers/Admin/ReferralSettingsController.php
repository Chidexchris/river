<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralSettingsRequest;
use App\Models\ReferralSettings;

class ReferralSettingsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ReferralSettings::class);

        $pageTitle = 'Referral Settings';
        $referrals = ReferralSettings::all();

        return view('admin.settings.referral', compact('pageTitle', 'referrals'));
    }

    public function store(ReferralSettingsRequest $request)
    {
        $validated = $request->validated();

        // store referral-commission-count
        $settings                            = bs();
        $settings->referral_commission_count = $validated['referral_commission_count'];
        $settings->save();

        if (isset($validated['percentage'])) {
            // delete previously stored commission percentages
            ReferralSettings::truncate();

            // store new referral commission percentages
            $commissionPercentages = [];

            for ($i = 0; $i < count($validated['percentage']); $i++) {
                $commissionPercentages[] = [
                    'level'      => $i + 1,
                    'percentage' => $validated['percentage'][$i],
                ];
            }

            ReferralSettings::insert($commissionPercentages);
        }

        $toast[] = ['success', 'Referral settings updated successfully'];

        return back()->with('toasts', $toast);
    }

    public function status()
    {
        $this->authorize('changeStatus', ReferralSettings::class);

        $settings = bs();

        if ($settings->referral_system == ManageStatus::ACTIVE) {
            $settings->referral_system = ManageStatus::INACTIVE;
            $message                   = 'Referral system has deactivated';
        } else {
            $settings->referral_system = ManageStatus::ACTIVE;
            $message                   = 'Referral system has activated';
        }

        $settings->save();

        $toast[] = ['success', $message];

        return back()->with('toasts', $toast);
    }
}
