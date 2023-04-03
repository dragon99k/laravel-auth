<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Number implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     *
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!preg_match('/^[\d]*$/', $value)) {
            $fail(__('validation.num_dash'));
        }
    }
}
