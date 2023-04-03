<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

/**
 * パーセント表記している値に対して、最大値のバリデーションをする。
 *
 * @todo 小数点以下未対応
 */
class MaxPercent implements InvokableRule
{
    private int $max;

    /**
     * @param int $max
     */
    public function __construct(int $max)
    {
        $this->max = $max;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param  \Closure
     *
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!is_numeric($value)) {
            return;
        }

        $percentiled = (int) bcmul($value, 100, 0);

        if ($percentiled > $this->max) {
            $fail('validation.max_percent')->translate([
                'max' => $this->max,
            ]);
        }
    }
}
