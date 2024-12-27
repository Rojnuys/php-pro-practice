<?php

declare(strict_types=1);

namespace App\UrlShortener\Infrastructure\File\Mappers;

use App\UrlShortener\Infrastructure\File\Entities\UrlCodePair;
use App\UrlShortener\Domain\Entities as Domain;
use App\UrlShortener\Domain\VO\Code;
use App\UrlShortener\Domain\VO\Url;

class UrlCodePairMapper
{
    protected function __construct()
    {
    }

    public static function toDomain(UrlCodePair $urlCodePair): Domain\UrlCodePair
    {
        return new Domain\UrlCodePair(
            $urlCodePair->getId(),
            Url::fromValue($urlCodePair->getUrl()),
            Code::fromValue($urlCodePair->getCode()),
            $urlCodePair->getCount(),
        );
    }

    public static function toEntity(Domain\UrlCodePair $urlCodePairEntity): UrlCodePair
    {
        return new UrlCodePair(
            $urlCodePairEntity->getId(),
            $urlCodePairEntity->getUrl()->url,
            $urlCodePairEntity->getCode()->code,
            $urlCodePairEntity->getCount(),
        );
    }
}