<?php

declare(strict_types=1);

use App\Shared\FileSystem\File\FileReader;
use App\Shared\FileSystem\File\FileWriter;
use App\UrlShortener\Application\Services\Shortener;
use App\UrlShortener\Domain\Services\CodeGenerator;
use App\UrlShortener\Infrastructure\File\Repositories\UrlCodePairRepository;

$urlCodePairFile = __DIR__ . '/../storage/url-code-pair2.txt';

$shortener = new Shortener(
    new UrlCodePairRepository(
        new FileReader($urlCodePairFile),
        new FileWriter($urlCodePairFile),
    ),
    new CodeGenerator(),
);

$urls = [
    'http://google.com/',
    'http://chrome.com/',
    'http://example.com/',
];

foreach ($urls as $url) {
    $code = $shortener->encode($url);
    echo $code . PHP_EOL;
    echo $shortener->decode($code) . PHP_EOL;
}