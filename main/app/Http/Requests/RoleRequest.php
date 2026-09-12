<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->route('role')) {
            return Gate::allows('store', Role::class);
        } else {
            return Gate::allows('update', Role::class);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role')?->id ?? null;

        return [
            'name'          => "required|string|max:40|unique:roles,name,$roleId",
            'permissions'   => 'nullable|array|min:1',
            'permissions.*' => 'required|integer|exists:permissions,id',
        ];
    }
}
