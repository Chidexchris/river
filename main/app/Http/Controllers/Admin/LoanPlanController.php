<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanPlanRequest;
use App\Lib\FormProcessor;
use App\Models\LoanPlan;
use Exception;

class LoanPlanController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', LoanPlan::class);

        $pageTitle = "Loan Plans";
        $loanPlans = LoanPlan::searchable(['name'])->latest()->paginate(getPaginate());

        return view('admin.loan.plans.index', compact('pageTitle', 'loanPlans'));
    }

    public function create()
    {
        $this->authorize('create', LoanPlan::class);

        $pageTitle   = 'Create New Plan';
        $formHeading = 'Loan Application Form Fields';
        $form        = '';

        return view('admin.loan.plans.create', compact('pageTitle', 'form', 'formHeading'));
    }

    public function store(LoanPlanRequest $request)
    {
        $validated = $request->validated();
        $data      = collect($validated)->except('form_generator')->toArray();

        try {
            $form = (new FormProcessor)->generate('loan_plan');
        } catch (Exception $e) {
            $toast[] = ['error', $e->getMessage()];

            return back()->with('toasts', $toast);
        }

        LoanPlan::create(
            array_merge(
                array_filter($data, fn($value) => !is_null($value)),
                ['form_id' => $form->id]
            )
        );

        $toast[] = ['success', 'New Loan plan has been successfully created'];

        return to_route('admin.loan.plans')->with('toasts', $toast);
    }

    public function edit(LoanPlan $plan)
    {
        $this->authorize('edit', $plan);

        $plan->load('form');

        $pageTitle   = 'Edit Plan';
        $formHeading = 'Loan Application Form Fields';
        $form        = $plan->form;

        return view('admin.loan.plans.edit', compact('pageTitle', 'form', 'formHeading', 'plan'));
    }

    public function update(LoanPlanRequest $request, LoanPlan $plan)
    {
        $validated = $request->validated();
        $data      = collect($validated)->except('form_generator')->toArray();

        try {
            (new FormProcessor)->generate('loan_plan', true, 'id', $plan->form_id);
        } catch (Exception $e) {
            $toast[] = ['error', $e->getMessage()];

            return back()->with('toasts', $toast);
        }

        $plan->update($data);

        $toast[] = ['success', 'Loan plan has been successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeStatus', LoanPlan::class);

        return LoanPlan::changeStatus($id);
    }
}
