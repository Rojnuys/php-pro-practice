<?php

declare(strict_types=1);

namespace App\UrlShortener\Domain\Entities;

use App\UrlShortener\Domain\VO\Code;
use App\UrlShortener\Domain\VO\Url;

class UrlCodePair
{
    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(
        protected string $id,
        protected Url    $url,
        protected Code   $code,
        protected int    $count
    )
    {
        if ($count < 0) {
            throw new \InvalidArgumentException('Count must be a positive integer');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function increaseCount(): void
    {
        $this->count++;
    }
}