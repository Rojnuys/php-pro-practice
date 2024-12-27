<?php

declare(strict_types=1);

use App\Shared\FileSystem\File\FileReader;
use App\Shared\FileSystem\File\FileWriter;
use App\Shortener\Repositories\FileUrlCodePairRepository;
use App\Shortener\Services\CodeGenerator;
use App\Shortener\Shortener;
use App\Shortener\Validators\UrlValidator;

$urlCodePairFile = __DIR__ . '/../storage/url-code-pair.txt';

$shortener = new Shortener(
    new FileUrlCodePairRepository(
        new FileReader($urlCodePairFile),
        new FileWriter($urlCodePairFile),
    ),
    new UrlValidator(),
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