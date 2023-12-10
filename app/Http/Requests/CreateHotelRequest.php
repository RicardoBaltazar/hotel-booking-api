<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class CreateHotelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'amenities' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo :attribute é obrigatório.',
            'name.string' => 'O campo :attribute deve ser uma string.',
            'name.max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'location.required' => 'O campo :attribute é obrigatório.',
            'location.string' => 'O campo :attribute deve ser uma string.',
            'location.max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'amenities.string' => 'O campo :attribute deve ser uma string.',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
        }
    }
}
