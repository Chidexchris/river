<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtherBankRequest;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\OtherBank;
use Exception;

class OtherBankController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', OtherBank::class);

        $pageTitle  = 'Other Banks';
        $otherBanks = OtherBank::searchable(['name'])->latest()->paginate(getPaginate());

        return view('admin.otherBanks.index', compact('pageTitle', 'otherBanks'));
    }

    public function create()
    {
        $this->authorize('create', OtherBank::class);

        $pageTitle   = 'Add Other Bank';
        $formHeading = 'Beneficiary Configuration';
        $form        = new Form();

        $form->mergeDefaultFields([
            'account_name'   => 'Account Name',
            'account_number' => 'Account Number',
            'short_name'     => 'Short Name',
        ]);

        return view('admin.otherBanks.create', compact('pageTitle', 'formHeading', 'form'));
    }

    public function store(OtherBankRequest $request)
    {
        $validated = $request->validated();
        $data      = collect($validated)->except('form_generator')->toArray();

        try {
            $form = (new FormProcessor)->generate('other_bank');
        } catch (Exception $e) {
            $toast[] = ['error', $e->getMessage()];

            return back()->with('toasts', $toast);
        }

        $otherBank = OtherBank::create(
            array_merge(
                array_filter($data, fn($value) => !is_null($value)),
                ['form_id' => $form->id]
            )
        );

        $toast[] = ['success', "$otherBank->name has been added successfully."];

        return to_route('admin.other.banks.index')->with('toasts', $toast);
    }

    public function edit(OtherBank $otherBank)
    {
        $this->authorize('edit', $otherBank);

        $otherBank->load('form');

        $pageTitle   = 'Edit Other Bank';
        $formHeading = 'Beneficiary Configuration';
        $form        = $otherBank->form;

        return view('admin.otherBanks.edit', compact('pageTitle', 'formHeading', 'form', 'otherBank'));
    }

    public function update(OtherBankRequest $request, OtherBank $otherBank)
    {
        $validated = $request->validated();
        $data      = collect($validated)->except('form_generator')->toArray();

        try {
            (new FormProcessor)->generate('other_bank', true, 'id', $otherBank->form_id);
        } catch (Exception $e) {
            $toast[] = ['error', $e->getMessage()];

            return back()->with('toasts', $toast);
        }

        $otherBank->update($data);

        $toast[] = ['success', "$otherBank->name has been updated successfully."];

        return back()->with('toasts', $toast);
    }

    public function updateStatus(int $id)
    {
        $this->authorize('changeStatus', OtherBank::class);

        return OtherBank::changeStatus($id);
    }
}
