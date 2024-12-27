<?php

declare(strict_types=1);

namespace App\UrlShortener\Domain\Validators;

class CodeValidator
{
    /**
     * @throws \InvalidArgumentException
     */
    public static function lengthValidate(string $code): void
    {
        if (strlen($code) > 100 || strlen($code) < 1) {
            throw new \InvalidArgumentException('Code length must be between 1 and 100');
        }
    }
}