<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositPensionSchemePlanRequest;
use App\Models\DepositPensionSchemePlan;

class DepositPensionSchemePlanController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', DepositPensionSchemePlan::class);

        $pageTitle     = "Deposit Pension Scheme Plans";
        $fractionDigit = bs('fraction_digit');
        $dpsPlans      = DepositPensionSchemePlan::searchable(['name'])->latest()->paginate(getPaginate());

        return view('admin.dps.plans', compact('pageTitle', 'dpsPlans', 'fractionDigit'));
    }

    public function store(DepositPensionSchemePlanRequest $request)
    {
        $validated = $request->validated();
        $data      = $this->handleCalculation($validated);

        DepositPensionSchemePlan::create(
            array_filter($data, fn($value) => !is_null($value))
        );

        $toast[] = ['success', 'New DPS plan has been successfully created'];

        return back()->with('toasts', $toast);
    }

    public function update(DepositPensionSchemePlanRequest $request, DepositPensionSchemePlan $depositPensionSchemePlan)
    {
        $validated = $request->validated();
        $data      = $this->handleCalculation($validated);

        $depositPensionSchemePlan->update($data);

        $toast[] = ['success', 'DPS plan has been successfully updated'];

        return back()->with('toasts', $toast);
    }

    private function handleCalculation($requestData)
    {
        $totalDepositAmount = $requestData['per_installment'] * $requestData['total_installment'];
        $profitAmount       = ($requestData['interest_rate'] * $totalDepositAmount) / 100;
        $maturityAmount     = $totalDepositAmount + $profitAmount;

        return array_merge($requestData, [
            'total_deposit_amount' => $totalDepositAmount,
            'profit_amount'        => $profitAmount,
            'maturity_amount'      => $maturityAmount,
        ]);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeStatus', DepositPensionSchemePlan::class);

        return DepositPensionSchemePlan::changeStatus($id);
    }
}
