<?php

declare(strict_types=1);

namespace App\Shortener\VO;

readonly class Code
{
    protected function __construct(public string $code)
    {
    }

    public static function fromValue(string $code): static
    {
        return new static($code);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function generate(int $codeLength): static
    {
        if ($codeLength < 1) {
            throw new \InvalidArgumentException('Code length must be greater than 0');
        }

        $availableSymbols = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $code = substr(str_shuffle($availableSymbols), 0, $codeLength);

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