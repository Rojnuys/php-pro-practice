<?php

declare(strict_types = 1);

namespace App\UrlShortener\Domain\Validators;

use App\UrlShortener\Domain\Enums\UrlAvailableHttpStatus;

class UrlValidator
{
    /**
     * @throws \InvalidArgumentException
     */
    public static function availabilityValidate(string $url): void
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $httpStatus = UrlAvailableHttpStatus::tryFrom($httpCode);

        if (is_null($httpStatus)) {
            throw new \InvalidArgumentException('The Url is not available.');
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function formatValidate(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Invalid URL format.");
        }
    }
}