<?php

declare(strict_types=1);

namespace App\Shortener\Services;

use App\Shortener\Interfaces\ICodeGenerator;

class CodeGenerator implements ICodeGenerator
{
    protected const string AVAILABLE_CHARACTERS = 'abcdefghijklmnopqrstuvwxyz1234567890';
    protected const int CODE_LENGTH = 6;

    public function generate(?int $length = null): string
    {
        $characters = static::AVAILABLE_CHARACTERS . mb_strtoupper(static::AVAILABLE_CHARACTERS);
        return substr(str_shuffle($characters), 0, $length ?? static::CODE_LENGTH);
    }
}