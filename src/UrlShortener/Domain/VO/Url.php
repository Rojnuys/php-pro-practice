<?php

declare(strict_types=1);

namespace App\UrlShortener\Domain\VO;

use App\UrlShortener\Domain\Validators\UrlValidator;

readonly class Url
{
    protected function __construct(public string $url)
    {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromValue(string $url): static
    {
        UrlValidator::formatValidate($url);
        UrlValidator::availabilityValidate($url);
        $url = rtrim($url, '/');

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