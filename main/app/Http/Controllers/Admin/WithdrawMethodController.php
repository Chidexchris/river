<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\WithdrawMethod;

class WithdrawMethodController extends Controller
{
    function index()
    {
        $this->authorize('viewAny', WithdrawMethod::class);

        $pageTitle = 'Withdrawal Methods';
        $methods   = WithdrawMethod::orderBy('name')->get();

        return view('admin.gateways.withdraw.index', compact('pageTitle', 'methods'));
    }

    function new()
    {
        $this->authorize('create', WithdrawMethod::class);

        $pageTitle   = 'Add Withdrawal Method';
        $method      = '';
        $form        = '';
        $formHeading = 'User Information';
        $actionRoute = route('admin.withdraw.method.store');
        $backRoute   = route('admin.withdraw.method.index');

        return view('admin.gateways.basicMethod', compact('pageTitle', 'method', 'form', 'formHeading', 'actionRoute', 'backRoute'));
    }

    function store($id = 0)
    {
        $this->authorize('storeOrUpdate', WithdrawMethod::class);

        $formProcessor = new FormProcessor();

        $this->validation(request(), $formProcessor);

        $minAmount      = request('min_amount');
        $fixedCharge    = request('fixed_charge');
        $percentCharge  = request('percent_charge');
        $minAmountCheck = $fixedCharge + ($minAmount * $percentCharge / 100);

        if ($minAmountCheck > $minAmount) {
            $toast[] = ['error', 'The total charge exceeds the minimum amount'];

            return back()->with('toasts', $toast);
        }

        if ($id) {
            $method   = WithdrawMethod::where('id', $id)->firstOrFail();
            $generate = $formProcessor->generate('withdraw_method', true, 'id', $method->form_id);
            $message  = ' withdrawal method successfully updated';
        } else {
            $method   = new WithdrawMethod();
            $generate = $formProcessor->generate('withdraw_method');
            $message  = ' withdrawal method successfully added';
        }

        $method->form_id        = $generate?->id ?? 0;
        $method->name           = request('name');
        $method->currency       = request('currency');
        $method->min_amount     = $minAmount;
        $method->max_amount     = request('max_amount');
        $method->fixed_charge   = $fixedCharge;
        $method->percent_charge = $percentCharge;
        $method->rate           = request('rate');
        $method->guideline      = request('guideline');
        $method->save();

        $toast[] = ['success', $method->name . $message];

        return back()->with('toasts', $toast);
    }

    function edit($id)
    {
        $method = WithdrawMethod::with('form')->findOrFail($id);

        $this->authorize('edit', $method);

        $pageTitle      = $method->name . ' Update';
        $methodRelation = $method;
        $form           = $method->form;
        $formHeading    = 'User Information';
        $actionRoute    = route('admin.withdraw.method.store', $method->id);
        $backRoute      = route('admin.withdraw.method.index');

        return view('admin.gateways.basicMethod', compact('pageTitle', 'method', 'methodRelation', 'form', 'formHeading', 'actionRoute', 'backRoute'));
    }

    function status($id)
    {
        $this->authorize('changeStatus', WithdrawMethod::class);

        return WithdrawMethod::changeStatus($id);
    }

    private function validation($request, $formProcessor)
    {
        $validation = [
            'name'           => 'required',
            'rate'           => 'required|numeric|gt:0',
            'currency'       => 'required',
            'min_amount'     => 'required|numeric|gt:0',
            'max_amount'     => 'required|numeric|gt:min_amount',
            'fixed_charge'   => 'required|numeric|gte:0',
            'percent_charge' => 'required|numeric|gte:0|regex:/^\d+(\.\d{1,2})?$/',
            'guideline'      => 'required',
        ];

        $generatorValidation = $formProcessor->generatorValidation();
        $validation          = array_merge($validation, $generatorValidation['rules']);

        $request->validate($validation, $generatorValidation['messages']);
    }
}
