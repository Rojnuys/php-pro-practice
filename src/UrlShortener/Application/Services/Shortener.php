<?php

namespace App\UrlShortener\Application\Services;

use App\UrlShortener\Application\Interfaces\IUrlDecoder;
use App\UrlShortener\Application\Interfaces\IUrlEncoder;
use App\UrlShortener\Domain\DTO\UrlCodePairCreateDTO;
use App\UrlShortener\Domain\Exceptions\CodeAlreadyExistException;
use App\UrlShortener\Domain\Exceptions\UrlCodePairDoesNotExistException;
use App\UrlShortener\Domain\Interfaces\ICodeGenerator;
use App\UrlShortener\Domain\Interfaces\IUrlCodePairRepository;
use App\UrlShortener\Domain\VO\Code;
use App\UrlShortener\Domain\VO\Url;

class Shortener implements IUrlEncoder, IUrlDecoder
{
    protected const int NUMBER_OF_ATTEMPTS_TO_CREATE_CODE = 5;

    public function __construct(
        protected IUrlCodePairRepository $repository,
        protected ICodeGenerator         $codeGenerator,
        protected int                    $codeLength = 6
    )
    {
        if ($this->codeLength < 1 || $this->codeLength > 100) {
            throw new \InvalidArgumentException('Code length must be between 1 and 100');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function decode(string $code): string
    {
        try {
            $urlCodePair = $this->repository->getByCode(Code::fromValue($code));
            $urlCodePair->increaseCount();
            $this->repository->update($urlCodePair);
            return $urlCodePair->getUrl();
        } catch (UrlCodePairDoesNotExistException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function encode(string $url): string
    {
        try {
            return $this->repository->getByUrl(Url::fromValue($url))->getCode();
        } catch (UrlCodePairDoesNotExistException) {
            return $this->createNewUrlCodePair($url);
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function createNewUrlCodePair(string $url): string
    {
        $attempts = 0;

        while ($attempts < static::NUMBER_OF_ATTEMPTS_TO_CREATE_CODE) {
            $code = Code::fromValue($this->codeGenerator->generate($this->codeLength));
            $url = Url::fromValue($url);

            try {
                $this->repository->create(new UrlCodePairCreateDTO($url, $code));
                return $code->code;
            } catch (CodeAlreadyExistException) {
                $attempts++;
            }
        }

        throw new \InvalidArgumentException('The service cannot create a code. Please try again.');
    }
}