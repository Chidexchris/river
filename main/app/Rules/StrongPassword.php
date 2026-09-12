<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = Password::min(6);

        if (bs('strong_pass')) $password->mixedCase()->numbers()->symbols()->uncompromised();

        $validator = validator(
            [$attribute => $value],
            [$attribute => $password],
            [
                'password.mixed'         => 'Your password must include both uppercase and lowercase letters.',
                'password.symbols'       => 'Your password must include at least one special character (e.g., @, #, $, etc.).',
                'password.numbers'       => 'Your password must include at least one number.',
                'password.uncompromised' => 'The password you provided has been compromised in a data breach. Please use a different password.',
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) $fail($error);
        }
    }
}
