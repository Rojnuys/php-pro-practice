<?php

declare(strict_types=1);

namespace App\UrlShortener\Infrastructure\File\Repositories;

use App\Shared\FileSystem\File\Exception\ReadFileException;
use App\Shared\FileSystem\File\Exception\WriteFileException;
use App\Shared\FileSystem\File\Interface\IFileReader;
use App\Shared\FileSystem\File\Interface\IFileWriter;
use App\UrlShortener\Domain\DTO\UrlCodePairCreateDTO;
use App\UrlShortener\Domain\Entities as Domain;
use App\UrlShortener\Domain\Exceptions\CodeAlreadyExistException;
use App\UrlShortener\Domain\Exceptions\UrlCodePairDoesNotExistException;
use App\UrlShortener\Domain\Interfaces\IUrlCodePairRepository;
use App\UrlShortener\Domain\VO\Code;
use App\UrlShortener\Domain\VO\Url;
use App\UrlShortener\Infrastructure\File\Entities\UrlCodePair;
use App\UrlShortener\Infrastructure\File\Mappers\UrlCodePairMapper;

class UrlCodePairRepository implements IUrlCodePairRepository
{
    public function __construct(protected IFileReader $fileReader, protected IFileWriter $fileWriter)
    {
    }

    /**
     * @throws UrlCodePairDoesNotExistException
     * @throws ReadFileException
     */
    public function getByUrl(Url $url): Domain\UrlCodePair
    {
        return UrlCodePairMapper::toDomain($this->getFirstUrlCodePairByCondition(function (UrlCodePair $urlCodePair) use ($url) {
            return $url->equalTo(Url::fromValue($urlCodePair->getUrl()));
        }));
    }

    /**
     * @throws UrlCodePairDoesNotExistException
     * @throws ReadFileException
     */
    public function getByCode(Code $code): Domain\UrlCodePair
    {
        return UrlCodePairMapper::toDomain($this->getFirstUrlCodePairByCondition(function (UrlCodePair $urlCodePair) use ($code) {
            return $code->equalTo(Code::fromValue($urlCodePair->getCode()));
        }));
    }

    /**
     * @throws CodeAlreadyExistException
     * @throws ReadFileException
     * @throws WriteFileException
     */
    public function create(UrlCodePairCreateDTO $dto): void
    {
        if ($this->isCodeAlreadyExists(Code::fromValue($dto->code))) {
            throw new CodeAlreadyExistException("The UrlCodePair with code {$dto->code} already exists.");
        }

        $this->fileWriter->append(
            $this->formatUrlCodePair(new UrlCodePair(uniqid('ucp_'), $dto->url, $dto->code))
        );
    }

    /**
     * @throws UrlCodePairDoesNotExistException
     * @throws ReadFileException
     * @throws WriteFileException
     */
    public function update(Domain\UrlCodePair $urlCodePair): void
    {
        $urlCodePairUpdated = UrlCodePairMapper::toEntity($urlCodePair);
        $updatedFile = '';
        $isExist = false;

        foreach ($this->getUrlCodePairGenerator() as $ucp) {
            if ($ucp->getId() === $urlCodePairUpdated->getId()) {
                $updatedFile .= $this->formatUrlCodePair($urlCodePairUpdated);
                $isExist = true;
                continue;
            }

            $updatedFile .= $this->formatUrlCodePair($ucp);
        }

        if (!$isExist) {
            throw new UrlCodePairDoesNotExistException();
        }

        $this->fileWriter->rewrite($updatedFile);
    }

    /**
     * @throws ReadFileException
     */
    protected function isCodeAlreadyExists(Code $code): bool
    {
        try {
            $this->getByCode($code);
            return true;
        } catch (UrlCodePairDoesNotExistException) {
            return false;
        }
    }

    /**
     * @throws ReadFileException
     */
    protected function getUrlCodePairGenerator(): \Generator
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

    protected function formatUrlCodePair(UrlCodePair $urlCodePair): string
    {
        return serialize($urlCodePair) . PHP_EOL;
    }
}