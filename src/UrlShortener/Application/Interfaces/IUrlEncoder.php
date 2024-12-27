<?php

namespace App\UrlShortener\Application\Interfaces;

interface IUrlEncoder
{

    /**
     * @param string $url
     * @throws \InvalidArgumentException
     * @return string
     */
    public function encode(string $url): string;
}