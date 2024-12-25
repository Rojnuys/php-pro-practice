<?php

declare(strict_types=1);

use App\Shared\FileSystem\File\FileReader;
use App\Shared\FileSystem\File\FileWriter;
use App\Shortener\Repository\FileUrlCodePairRepository;
use App\Shortener\Shortener;

$urlCodePairFile = __DIR__ . '/../db/url-code-pair.txt';

$shortener = new Shortener(
    new FileUrlCodePairRepository(
        new FileReader($urlCodePairFile),
        new FileWriter($urlCodePairFile, true)
    )
);

$code = $shortener->encode('http://google.com');
echo $code . PHP_EOL;
$url = $shortener->decode($code);
echo $url . PHP_EOL;