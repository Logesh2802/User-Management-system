<?php

namespace App\Rules;
use Closure;
use Illuminate\Contracts\Validation\Rule;

class MathCaptcha implements Rule {
    public function passes($attribute, $value): bool {
        return $value == session('captcha_result');
    }
    public function message(): string {
        return 'Captcha is incorrect.';
    }
}