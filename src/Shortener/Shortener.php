<?php

namespace App\Shortener;

use App\Shortener\DTO\UrlCodePairCreateDTO;
use App\Shortener\Exceptions\CodeAlreadyExistException;
use App\Shortener\Exceptions\UrlCodePairDoesNotExistException;
use App\Shortener\Interfaces\ICodeGenerator;
use App\Shortener\Interfaces\IUrlCodePairRepository;
use App\Shortener\Interfaces\IUrlDecoder;
use App\Shortener\Interfaces\IUrlEncoder;
use App\Shortener\Interfaces\IUrlValidator;

class Shortener implements IUrlEncoder, IUrlDecoder
{
    protected const int NUMBER_OF_ATTEMPTS_TO_CREATE_CODE = 5;

    public function __construct(
        protected IUrlCodePairRepository $repository,
        protected IUrlValidator $urlValidator,
        protected ICodeGenerator $codeGenerator,
        protected int $codeLength = 6
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
            $urlCodePair = $this->repository->getByCode($code);
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
        $this->urlValidator->checkFormat($url);
        $this->urlValidator->checkAvailability($url);
        $url = rtrim($url, "/");

        try {
            return $this->repository->getByUrl($url)->getCode();
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
            $code = $this->codeGenerator->generate($this->codeLength);

            try {
                $this->repository->create(new UrlCodePairCreateDTO($url, $code));
                return $code;
            } catch (CodeAlreadyExistException) {
                $attempts++;
            }
        }

        throw new \InvalidArgumentException('The service cannot create a code. Please try again.');
    }
}