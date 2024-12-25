<?php

namespace App\Shortener\Interface;

interface IUrlDecoder
{
    /**
     * @param string $code
     * @throws \InvalidArgumentException
     * @return string
     */
    public function decode(string $code): string;
}