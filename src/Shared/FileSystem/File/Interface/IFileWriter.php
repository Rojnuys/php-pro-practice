<?php

declare(strict_types = 1);

namespace App\Shared\FileSystem\File\Interface;

use App\Shared\FileSystem\File\Exception\WriteFileException;

interface IFileWriter
{
    /**
     * @throws WriteFileException
     */
    public function write(string $data): void;
}