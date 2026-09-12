<?php

namespace App\Http\Requests;

use App\Constants\ManageStatus;
use App\Models\Staff;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('staff')) {
            return Gate::allows('store', Staff::class);
        } else {
            return Gate::allows('update', Staff::class);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $staffRoles = [ManageStatus::BRANCH_MANAGER, ManageStatus::BRANCH_ACCOUNT_OFFICER];
        $staffId    = $this->route('staff')?->id ?? null;

        return [
            'name'           => 'required|string|max:40',
            'username'       => "required|string|max:40|unique:staffs,username,$staffId",
            'email_address'  => "required|email:rfc,dns|max:40|unique:staffs,email_address,$staffId",
            'contact_number' => "required|string|max:40|unique:staffs,contact_number,$staffId",
            'address'        => 'required|string|max:255',
            'designation'    => 'required|integer|in:' . implode(',', $staffRoles),
            'branch_ids'     => 'required|array|min:1',
            'branch_ids.*'   => 'required|integer|exists:branches,id',
            'password'       => [$staffId ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}
