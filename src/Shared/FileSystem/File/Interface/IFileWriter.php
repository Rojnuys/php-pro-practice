<?php

declare(strict_types = 1);

namespace App\Shared\FileSystem\File\Interface;

use App\Shared\FileSystem\File\Exception\WriteFileException;

interface IFileWriter
{
    /**
     * @throws WriteFileException
     */
    public function rewrite(string $data): void;

    /**
     * @throws WriteFileException
     */
    public function append(string $data): void;
}