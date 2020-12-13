<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileExists implements Rule
{
    private $directory;
    /**
     * Create a new rule instance.
     *
     * @param string $directory
     * @return void
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
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
        return file_exists($this->directory . '/' . $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Temporary File not found';
    }
}
