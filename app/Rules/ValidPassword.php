<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class ValidPassword implements Rule
{
        /**
     * Determine if the Length Validation Rule passes.
     *
     * @var bool
     */
    public const lengthPasses = true;

    /**
     * Determine if the Uppercase Validation Rule passes.
     *
     * @var bool
     */
    public const upperCasePasses = true;

    /**
     * Determine if the Numeric Validation Rule passes.
     *
     * @var bool
     */
    public const numericPasses = true;

    /**
     * Determine if the Special Character Validation Rule passes.
     *
     * @var bool
     */
    public const specialCharacterPasses = true;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->lengthPasses = (Str::length($value) >= 8);
        $this->upperCasePasses = (Str::lower($value) !== $value);
        $this->numericPasses = ((bool) preg_match('/[0-9]/', $value));
        $this->specialCharacterPasses = ((bool) preg_match('/[^A-Za-z0-9]/', $value));

        return $this->lengthPasses && $this->upperCasePasses && $this->numericPasses && $this->specialCharacterPasses;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
