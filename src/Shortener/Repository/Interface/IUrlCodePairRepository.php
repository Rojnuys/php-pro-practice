<?php

declare(strict_types=1);

namespace App\Shortener\Repository\Interface;

use App\Shortener\Repository\Exception\CodeAlreadyExistException;
use App\Shortener\Repository\Exception\UrlCodePairDoesNotExistException;
use App\Shortener\VO\Code;
use App\Shortener\VO\Url;

interface IUrlCodePairRepository
{
    /**
     * @throws UrlCodePairDoesNotExistException
     */
    public function getCodeByUrl(Url $url): Code;

    /**
     * @throws UrlCodePairDoesNotExistException
     */
    public function getUrlByCode(Code $code): Url;

    /**
     * @throws CodeAlreadyExistException
     */
    public function createUrlCodePair(Url $url, Code $code): void;
}