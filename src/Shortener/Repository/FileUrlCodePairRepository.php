<?php

declare(strict_types=1);

namespace App\Shortener\Repository;

use App\Shared\FileSystem\File\Exception\ReadFileException;
use App\Shared\FileSystem\File\Exception\WriteFileException;
use App\Shared\FileSystem\File\Interface\IFileReader;
use App\Shared\FileSystem\File\Interface\IFileWriter;
use App\Shortener\Entity\UrlCodePair;
use App\Shortener\Repository\Exception\CodeAlreadyExistException;
use App\Shortener\Repository\Exception\UrlCodePairDoesNotExistException;
use App\Shortener\Repository\Interface\IUrlCodePairRepository;
use App\Shortener\VO\Code;
use App\Shortener\VO\Url;
use Generator;

class FileUrlCodePairRepository implements IUrlCodePairRepository
{
    public function __construct(protected IFileReader $fileReader, protected IFileWriter $fileWriter)
    {
    }

    /**
     * @throws UrlCodePairDoesNotExistException
     * @throws ReadFileException
     */
    public function getCodeByUrl(Url $url): Code
    {
        return $this->getFirstUrlCodePairByCondition(function (UrlCodePair $urlCodePair) use ($url) {
            return $urlCodePair->getUrl()->equalTo($url);
        })->getCode();
    }

    /**
     * @throws UrlCodePairDoesNotExistException
     * @throws ReadFileException
     */
    public function getUrlByCode(Code $code): Url
    {
        return $this->getFirstUrlCodePairByCondition(function (UrlCodePair $urlCodePair) use ($code) {
            return $urlCodePair->getCode()->equalTo($code);
        })->getUrl();
    }

    /**
     * @throws CodeAlreadyExistException
     * @throws ReadFileException
     * @throws WriteFileException
     */
    public function createUrlCodePair(Url $url, Code $code): void
    {
        if ($this->isCodeAlreadyExists($code)) {
            throw new CodeAlreadyExistException("The UrlCodePair with code {$code} already exists.");
        }

        $this->fileWriter->write(
            serialize(new UrlCodePair($url, $code)) . PHP_EOL
        );
    }

    /**
     * @throws ReadFileException
     */
    protected function isCodeAlreadyExists(Code $code): bool
    {
        try {
            $this->getUrlByCode($code);
            return true;
        } catch (UrlCodePairDoesNotExistException) {
            return false;
        }
    }

    /**
     * @throws ReadFileException
     */
    protected function getUrlCodePairGenerator(): Generator
    {
        return $this->fileReader->readByLine(function (string $line) {
            return unserialize(trim($line));
        });
    }

    /**
     * @throws UrlCodePairDoesNotExistException
     * @throws ReadFileException
     */
    protected function getFirstUrlCodePairByCondition(callable $conditionClb): UrlCodePair
    {
        /**
         * @var UrlCodePair $codePair
         */
        foreach ($this->getUrlCodePairGenerator() as $codePair) {
            if ($conditionClb($codePair)) {
                return $codePair;
            }
        }

        throw new UrlCodePairDoesNotExistException();
    }
}