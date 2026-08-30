<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxWords implements Rule
{
    protected $maxWords;

    public function __construct($maxWords)
    {
        $this->maxWords = $maxWords;
    }

    public function passes($attribute, $value): bool
    {
        return str_word_count($value) <= $this->maxWords;
    }

    public function message(): string
    {
        return "The branch name must not be longer than {$this->maxWords} words. Example 'New Baneshwor'";
    }
}
