<?php

declare(strict_types=1);

namespace App\UrlShortener\Domain\VO;

use App\UrlShortener\Domain\Validators\CodeValidator;

readonly class Code
{
    protected function __construct(public string $code)
    {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromValue(string $code): static
    {
        CodeValidator::lengthValidate($code);

        return new static($code);
    }

    public function equalTo(Code $other): bool
    {
        return $this->code === $other->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}