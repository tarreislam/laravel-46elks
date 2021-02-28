<?php


namespace Tarre\Laravel46Elks\Rules;

use Illuminate\Contracts\Validation\Rule;

class TextSenderIdRule implements Rule
{
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[a-z]{1}[a-z0-9]{2,10}$/i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Text Sender ID';
    }
}
