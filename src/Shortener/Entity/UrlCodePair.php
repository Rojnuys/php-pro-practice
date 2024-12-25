<?php

declare(strict_types=1);

namespace App\Shortener\Entity;


use App\Shortener\VO\Code;
use App\Shortener\VO\Url;

class UrlCodePair
{
    public function __construct(protected Url $url, protected Code $code)
    {
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function setUrl(Url $url): void
    {
        $this->url = $url;
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function setCode(Code $code): void
    {
        $this->code = $code;
    }
}