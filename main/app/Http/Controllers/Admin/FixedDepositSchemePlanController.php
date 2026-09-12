<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FixedDepositSchemePlanRequest;
use App\Models\FixedDepositSchemePlan;

class FixedDepositSchemePlanController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', FixedDepositSchemePlan::class);

        $pageTitle     = "Fixed Deposit Scheme Plans";
        $fractionDigit = bs('fraction_digit');
        $fdsPlans      = FixedDepositSchemePlan::searchable(['name'])->latest()->paginate(getPaginate());

        return view('admin.fds.plans', compact('pageTitle', 'fractionDigit', 'fdsPlans'));
    }

    public function store(FixedDepositSchemePlanRequest $request)
    {
        FixedDepositSchemePlan::create($request->validated());

        $toast[] = ['success', 'New FDS plan has been successfully created'];

        return back()->with('toasts', $toast);
    }

    public function update(FixedDepositSchemePlanRequest $request, FixedDepositSchemePlan $fixedDepositSchemePlan)
    {
        $fixedDepositSchemePlan->update($request->validated());

        $toast[] = ['success', 'FDS plan has been successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeStatus', FixedDepositSchemePlan::class);

        return FixedDepositSchemePlan::changeStatus($id);
    }
}
