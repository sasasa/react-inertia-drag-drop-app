<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersMultiUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $emails = collect($this->users)->map(fn ($user) => $user['email'])->toArray();
        $v_email_duplicate = function($attribute, $value, $fail) use($emails) {
            $emailArray = [...$emails];
            preg_match('/users\.(\d+)\.email/', $attribute, $match);
            array_splice($emailArray, intval($match[1]), 1);
            if (in_array($value, $emailArray)) {
                $fail(':attribute は重複しています。');
            }
        };

        return [
            'users' => ['required', 'array'],
            'users.*.id' => ['required', 'regex:/^[0-9]+$/i'],
            'users.*.name' => ['required', 'min:2', 'max:55'],
            'users.*.username' => ['required', 'min:2', 'max:55'],
            'users.*.email' => ['required', 'min:2', 'max:55', 'email', 'regex:/^[a-zA-Z0-9_+-]+(\.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/', $v_email_duplicate],
            'users.*.is_email_verified' => ['required', 'boolean',],
        ];
    }
}
