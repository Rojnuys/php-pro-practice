<?php

declare(strict_types=1);

namespace App\UrlShortener\Domain\DTO;

readonly class UrlCodePairCreateDTO
{
    public function __construct(public string $url, public string $code)
    {
    }
}