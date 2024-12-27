<?php

declare(strict_types=1);

namespace App\Shortener\DTO;

readonly class UrlCodePairCreateDTO
{
    public function __construct(public string $url, public string $code)
    {
    }
}