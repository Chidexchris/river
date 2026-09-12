<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AdminStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('admin')) {
            return Gate::allows('createAdmin', Admin::class);
        } else {
            return Gate::allows('editAdmin', Admin::class);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $adminId = $this->route('admin')?->id ?? null;

        return [
            'name'     => 'required|string|max:40',
            'username' => "required|string|max:40|unique:admins,username,$adminId",
            'email'    => "required|email:rfc,dns|max:40|unique:admins,email,$adminId",
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'required|integer|exists:roles,id',
            'password' => [$adminId ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }
}
