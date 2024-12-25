<?php

declare(strict_types = 1);

namespace App\Shared\FileSystem\File\Interface;

use App\Shared\FileSystem\File\Exception\ReadFileException;

interface IFileReader
{
    /**
     * @throws ReadFileException
     */
    public function readAll() : string;

    /**
     * @throws ReadFileException
     */
    public function readByLine(?callable $lineHandlerClb = null): \Generator;
}