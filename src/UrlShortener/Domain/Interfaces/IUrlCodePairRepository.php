<?php

declare(strict_types=1);

namespace App\UrlShortener\Domain\Interfaces;

use App\UrlShortener\Domain\DTO\UrlCodePairCreateDTO;
use App\UrlShortener\Domain\Entities\UrlCodePair;
use App\UrlShortener\Domain\Exceptions\CodeAlreadyExistException;
use App\UrlShortener\Domain\Exceptions\UrlCodePairDoesNotExistException;
use App\UrlShortener\Domain\VO\Code;
use App\UrlShortener\Domain\VO\Url;

interface IUrlCodePairRepository
{
    /**
     * @throws UrlCodePairDoesNotExistException
     */
    public function getByUrl(Url $url): UrlCodePair;

    /**
     * @throws UrlCodePairDoesNotExistException
     */
    public function getByCode(Code $code): UrlCodePair;

    /**
     * @throws CodeAlreadyExistException
     */
    public function create(UrlCodePairCreateDTO $dto): void;

    /**
     * @throws UrlCodePairDoesNotExistException
     */
    public function update(UrlCodePair $urlCodePair): void;
}