<?php

namespace App\Lib;

use App\Models\Form;
use Carbon\Carbon;
use Exception;

class FormProcessor
{
    /**
     * Generate validation rules & messages for the custom fields of the form.
     *
     * @return array
     */
    public function generatorValidation(): array
    {
        $validation['rules'] = [
            'form_generator.is_required.*' => 'required|in:required,optional',
            'form_generator.extensions.*'  => 'nullable',
            'form_generator.options.*'     => 'nullable',
            'form_generator.form_label.*'  => 'required|regex:/^[\pL\s]+$/u',
            'form_generator.form_type.*'   => 'required|in:text,email,url,number,datetime,date,time,select,radio,textarea,checkbox,file',
        ];

        $validation['messages'] = [
            'form_generator.is_required.*.required' => 'The "Is Required" field is required for all entries.',
            'form_generator.is_required.*.in'       => 'The "Is Required" field must be either "required" or "optional".',
            'form_generator.form_label.*.required'  => 'The form label is required for all entries.',
            'form_generator.form_label.*.regex'     => 'Form labels can only contain letters and spaces.',
            'form_generator.form_type.*.required'   => 'The form type is required for all entries.',
            'form_generator.form_type.*.in'         => 'Some selected form types are invalid.',
        ];

        return $validation;
    }

    /**
     * Generate form_data for the form.
     *
     * @param string $act
     * @param bool $isUpdate
     * @param string $identifierField
     * @param null $identifier
     * @return Form
     * @throws Exception
     */
    public function generate(string $act, bool $isUpdate = false, string $identifierField = 'act', $identifier = null): Form
    {
        $forms    = request()->form_generator;
        $formData = [];

        if ($forms) {
            for ($i = 0; $i < count($forms['form_label']); $i++) {
                $extensions = $forms['extensions'][$i];

                if ($extensions != 'null' && $extensions != null) {
                    $extensionsArr = explode(',', $extensions);
                    $notMatchedExt = count(array_diff($extensionsArr, $this->supportedExt()));

                    if ($notMatchedExt > 0) throw new Exception("Your selected extensions are invalid");
                }

                $label = titleToKey($forms['form_label'][$i]);

                $formData[$label] = [
                    'name'        => $forms['form_label'][$i],
                    'label'       => $label,
                    'is_required' => $forms['is_required'][$i],
                    'extensions'  => $forms['extensions'][$i] == 'null' ? "" : $forms['extensions'][$i],
                    'options'     => $forms['options'][$i] ? explode(",", $forms['options'][$i]) : [],
                    'type'        => $forms['form_type'][$i],
                ];
            }
        }

        if ($isUpdate) {
            if ($identifierField == 'act') $identifier = $act;

            $form = Form::where($identifierField, $identifier)->first();

            if (!$form) $form = new Form();
        } else {
            $form = new Form();
        }

        $form->act       = $act;
        $form->form_data = $formData;
        $form->save();

        return $form;
    }

    /**
     * Validate the input fields based on custom form field settings.
     *
     * @param $formData
     * @return array
     */
    public function valueValidation($formData): array
    {
        $validationRule = [];
        $rule           = [];

        foreach ($formData as $data) {
            if ($data->is_required == 'required') $rule = array_merge($rule, ['required']);
            else $rule = array_merge($rule, ['nullable']);

            if ($data->type == 'select' || $data->type == 'checkbox' || $data->type == 'radio') {
                $rule = array_merge($rule, ['in:' . implode(',', $data->options)]);
            }

            if ($data->type == 'file') $rule = array_merge($rule, ['mimes:' . $data->extensions]);

            if ($data->type == 'email') $rule = array_merge($rule, ['email']);

            if ($data->type == 'url') $rule = array_merge($rule, ['url']);

            if ($data->type == 'number') $rule = array_merge($rule, ['numeric']);

            if ($data->type == 'datetime') $rule = array_merge($rule, ['date_format:Y-m-d\TH:i']);

            if ($data->type == 'date') $rule = array_merge($rule, ['date_format:Y-m-d']);

            if ($data->type == 'time') $rule = array_merge($rule, ['date_format:H:i']);

            if ($data->type == 'checkbox') $validationRule[$data->label . '.*'] = $rule;
            else $validationRule[$data->label] = $rule;

            $rule = [];
        }

        return $validationRule;
    }

    /**
     * Process the input values based on the form data. This includes tasks such as
     * formatting date values, storing file uploads in the storage, and handling
     * other field types as specified in the form data.
     *
     * @param $request
     * @param $formData
     * @return array
     */
    public function processFormData($request, $formData): array
    {
        $requestForm = [];

        foreach ($formData as $data) {
            $name  = $data->label;
            $value = $request->$name;

            if ($data->type == 'datetime') $value = Carbon::parse($value)->format('Y-m-d H:i');

            if ($data->type == 'file') {
                if ($request->hasFile($name)) {
                    $directory = date("Y") . "/" . date("m") . "/" . date("d");
                    $path      = getFilePath('verify') . '/' . $directory;
                    $value     = $directory . '/' . fileUploader($value, $path);
                } else {
                    $value = null;
                }
            }

            $requestForm[] = [
                'name'  => $data->name,
                'type'  => $data->type,
                'value' => $value,
            ];
        }

        return $requestForm;
    }

    /**
     * Get the list of supported file extensions.
     *
     * @return array
     */
    public function supportedExt(): array
    {
        return [
            'jpg',
            'jpeg',
            'png',
            'pdf',
            'doc',
            'docx',
            'txt',
            'xlx',
            'xlsx',
            'csv',
        ];
    }
}
