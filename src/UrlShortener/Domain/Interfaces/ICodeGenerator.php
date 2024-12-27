<?php

declare(strict_types = 1);

namespace App\UrlShortener\Domain\Interfaces;

interface ICodeGenerator
{
    public static function generate(?int $length = null): string;
}