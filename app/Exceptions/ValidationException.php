<?php

namespace App\Exceptions;

use App\Utils\Arr;
use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends BaseValidationException
{
    private static string $globalMessage = 'validation.global_message';

    /**
     * Create an error message summary from the validation errors.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return string
     */
    protected static function summarize($validator)
    {
        $messages = $validator->errors()->messages();

        if (empty($messages)) {
            return __(static::$globalMessage);
        }

        if (array_keys($messages)[0] === 0) {
            return Arr::unwrap($messages[0]);
        }

        return __(static::$globalMessage);
    }
}
