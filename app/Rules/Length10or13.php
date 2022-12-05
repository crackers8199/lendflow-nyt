<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Length10or13 implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        if(strlen($value) != 10 && strlen($value) != 13) {
            $fail(sprintf('Invalid :attribute: %s, length must be 10 or 13.', $value));
        }
    }
}
