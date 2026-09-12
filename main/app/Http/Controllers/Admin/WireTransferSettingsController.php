<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WireTransferSettingsRequest;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\WireTransferSettings;
use Exception;

class WireTransferSettingsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', WireTransferSettings::class);

        $pageTitle            = 'Wire Transfer Settings';
        $wireTransferSettings = WireTransferSettings::with('form')->firstOrNew();
        $formHeading          = 'Form Configuration';
        $form                 = $wireTransferSettings->form ?? new Form();

        if (!$wireTransferSettings->form) {
            $form->mergeDefaultFields([
                'account_name'   => 'Account Name',
                'account_number' => 'Account Number',
            ]);
        }

        return view('admin.page.wireTransferSettings', compact('pageTitle', 'wireTransferSettings', 'formHeading', 'form'));
    }

    public function update(WireTransferSettingsRequest $request)
    {
        $validated = $request->validated();
        $data      = collect($validated)->except('form_generator')->toArray();

        try {
            $wireTransferSettings = WireTransferSettings::first();

            // Generate form and retrieve form ID
            $form = (new FormProcessor)->generate(
                'wire_transfer',
                (bool)$wireTransferSettings,
                'id',
                $wireTransferSettings?->form_id
            );

            // Update or create wire transfer settings
            WireTransferSettings::updateOrCreate(
                ['id' => optional($wireTransferSettings)->id],
                array_merge(
                    array_filter($data, fn($value) => !is_null($value)),
                    ['form_id' => $form->id]
                )
            );
        } catch (Exception $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }

        return back()->with('toasts', [
            ['success', 'Wire transfer settings has been updated successfully.']
        ]);
    }
}
