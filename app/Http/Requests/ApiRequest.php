<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\ApiRequestException;

class ApiRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
        switch ($this->instance()->url()) {
            case route('token'):
                $rules = [
                    'email' => 'required|email|max:255',
                    'password' => 'required|string|max:255'
                ];
                break;
            case route('posts.post'):
                $rules = [
                    'user_id' => 'integer',
                ];
                break;
            case route('comments'):
                $rules = [
                    'user_id' => 'integer',
                    'post_id' => 'integer'
                ];
                break;
            default:
                $rules = [];
                break;
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ApiRequestException($validator->messages());
    }
}
