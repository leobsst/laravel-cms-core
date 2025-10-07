<?php

declare(strict_types=1);

namespace Leobsst\LaravelCmsCore\Concerns\Request;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiRequestFaillableTrait
{
    /**
     * Handle a failed validation attempt.
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response: response()->json(data: [
            'status' => 'error',
            'error' => $validator->errors()->first(),
            'data' => [$validator->errors()->first()],
        ], status: 400));
    }
}
