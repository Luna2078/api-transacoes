<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CreateUserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'name' => 'required|string|min:1|max:255',
			'email' => 'required|string|email|max:255|unique:users,email',
			'cpf_cnpj' => 'required|string|min:11|max:14|unique:users,cpf_cnpj',
			'balance' => 'required|numeric',
			'type' => 'required|integer|in:1,2',
			'password' => 'required|string|min:8|:max:255',
		];
	}
	
	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
	}
}
