<?php

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Decimal implements InvokableRule
{
    private int $length;
    private int $precision;
    private bool $percentiled;

    /**
     * @param int $length
     * @param int $precision
     * @param bool $percentiled パーセント表記している場合、表示上の値は100倍になるので、trueのときはメッセージ内の値の調整をする
     */
    public function __construct(int $length, int $precision, bool $percentiled = false)
    {
        $this->length = $length;
        $this->precision = $precision;
        $this->percentiled = $percentiled;
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
            $fail('validation.decimal')->translate();
        }

        $value = (string) $value;

        if (!strlen($value) > $this->length) {
            $fail('validation.decimal_length')->translate([
                'length' => $this->length,
            ]);
        }

        if (strpos($value, '.') === false) {
            return;
        }

        if (strlen(explode('.', $value)[1]) > $this->precision) {
            $precision = max($this->precision - ((int) $this->percentiled * 2), 0);
            $fail($precision > 0 ? 'validation.decimal_precision' : 'validation.decimal_precision_2')->translate([
                'precision' => max($this->precision - ((int) $this->percentiled * 2), 0),
            ]);
        }
    }
}
