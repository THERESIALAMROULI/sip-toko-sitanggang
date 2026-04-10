<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
class PerbaruiProfilRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];
        if (Schema::hasColumn('users', 'username')) {
            $rules['username'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ];
        }
        return $rules;
    }
}
