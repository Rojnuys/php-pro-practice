<?php

declare(strict_types=1);

namespace App\Shared\FileSystem\File;

use App\Shared\FileSystem\File\Exception\WriteFileException;
use App\Shared\FileSystem\File\Interface\IFileWriter;

readonly class FileWriter implements IFileWriter
{
    public function __construct(public string $path, public bool $isAppend = false)
    {
    }

    /**
     * @throws WriteFileException
     */
    public function write(string $data): void
    {
        try {
            $file = fopen($this->path, $this->isAppend ? 'a' : 'w');
            fwrite($file, $data);
        } catch (\Throwable $e) {
            throw new WriteFileException($e->getMessage());
        } finally {
            fclose($file);
        }
    }
}