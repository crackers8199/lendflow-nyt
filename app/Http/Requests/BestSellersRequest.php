<?php

namespace App\Http\Requests;

use App\Rules\Length10or13;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BestSellersRequest extends FormRequest
{
    /**
     * Validation rules for a Best Sellers request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'author' => 'string',
            'isbn' => 'filled|array',
            'isbn.*' => ['bail', 'string', new Length10or13],
            'title' => 'string',
            'offset' => ['bail', 'integer', 'multiple_of:20'],
        ];
    }

    /**
     * Force 422 error rather than redirect on any failed validation.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
