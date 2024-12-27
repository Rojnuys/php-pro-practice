<?php

declare(strict_types = 1);

namespace App\UrlShortener\Domain\Services;

use App\UrlShortener\Domain\Interfaces\ICodeGenerator;

class CodeGenerator implements ICodeGenerator
{
    protected const int DEFAULT_CODE_LENGTH = 6;
    protected const string AVAILABLE_SYMBOLS = 'abcdefghijklmnopqrstuvwxyz1234567890';

    public static function generate(?int $length = null): string
    {
        return substr(str_shuffle(static::AVAILABLE_SYMBOLS), 0, $length ?? static::DEFAULT_CODE_LENGTH);
    }
}