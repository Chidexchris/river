<?php

namespace App\Http\Requests;

use App\Models\Branch;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('branch')) {
            return Gate::allows('store', Branch::class);
        } else {
            return Gate::allows('update', Branch::class);
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'map_location' => strip_tags($this->input('map_location'), '<iframe>'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $branchId = $this->route('branch')?->id ?? null;

        return [
            'name'           => "required|string|max:40|unique:branches,name,$branchId",
            'code'           => "required|string|max:40|unique:branches,code,$branchId",
            'routing_number' => "required|integer|unique:branches,routing_number,$branchId",
            'swift_code'     => "required|string|max:40|unique:branches,swift_code,$branchId",
            'contact_number' => 'nullable|string|max:40',
            'email'          => 'nullable|email:rfc,dns|max:40',
            'address'        => 'required|string|min:10|max:500',
            'map_location'   => 'nullable|string'
        ];
    }
}
