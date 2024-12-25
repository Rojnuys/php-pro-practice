<?php

declare(strict_types=1);

namespace App\Shortener\VO;

use App\Shortener\Validator\UrlValidator;

readonly class Url
{
    protected function __construct(public string $url)
    {
    }

    public static function fromValue(string $url): static
    {
        $url = rtrim($url, '/');
        UrlValidator::validate($url);

        return new static($url);
    }

    public function equalTo(Url $other): bool
    {
        return $this->url === $other->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }
}