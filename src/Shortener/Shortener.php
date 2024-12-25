<?php

namespace App\Shortener;

use App\Shortener\Interface\IUrlDecoder;
use App\Shortener\Interface\IUrlEncoder;
use App\Shortener\Repository\Exception\CodeAlreadyExistException;
use App\Shortener\Repository\Exception\UrlCodePairDoesNotExistException;
use App\Shortener\Repository\Interface\IUrlCodePairRepository;
use App\Shortener\VO\Code;
use App\Shortener\VO\Url;

class Shortener implements IUrlEncoder, IUrlDecoder
{
    protected const int NUMBER_OF_ATTEMPTS_TO_CREATE_CODE = 5;

    public function __construct(protected IUrlCodePairRepository $repository, protected int $codeLength = 6)
    {
        if ($this->codeLength < 1) {
            throw new \InvalidArgumentException('Code length must be greater than 0');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function decode(string $code): string
    {
        try {
            $url = $this->repository->getUrlByCode(Code::fromValue($code));
            return $url->url;
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
            $code = $this->repository->getCodeByUrl(Url::fromValue($url));
            return $code->code;
        } catch (UrlCodePairDoesNotExistException) {
            return $this->createNewUrlCodePair(Url::fromValue($url))->code;
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function createNewUrlCodePair(Url $url): Code
    {
        $attempts = 0;

        while ($attempts < static::NUMBER_OF_ATTEMPTS_TO_CREATE_CODE) {
            $code = Code::generate($this->codeLength);

            try {
                $this->repository->createUrlCodePair(Url::fromValue($url), $code);
                return $code;
            } catch (CodeAlreadyExistException $e) {
                $attempts++;
            }
        }

        throw new \InvalidArgumentException('The service cannot create a code. Please try again.');
    }
}