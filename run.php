<?php

require_once __DIR__ . '/vendor/autoload.php';

$photo = __DIR__ . '/photo.jpg';

use Intervention\Image\Image;

$methods = [
    function (Image $image) {
        $image -> resize(200) -> save(__DIR__ . '/resize.x.jpg');
    },
    function (Image $image) {
        $image -> resize(null, 200) -> save(__DIR__ . '/resize.null.x.jpg');
    },
    function (Image $image) {
        $image -> resize(200, 200) -> save(__DIR__ . '/resize.x.x.jpg');
    },
    function (Image $image) {
        $image -> resize(200, 200, true) -> save(__DIR__ . '/resize.x.x.true.jpg');
    },
    function (Image $image) {
        $image -> grab(200) -> save(__DIR__ . '/grab.x.jpg');
    },
    function (Image $image) {
        $image -> grab(100, 200) -> save(__DIR__ . '/grab.x.x.jpg');
    },
    function (Image $image) {
        $image -> crop(200, 200) -> save(__DIR__ . '/crop.x.x.jpg');
    },
    function (Image $image) {
        $image -> crop(200, 200, 0, 0) -> save(__DIR__ . '/crop.x.x.0.0.jpg');
    },
    function (Image $image) {
        $image -> brightness(50) -> save(__DIR__ . '/brightness.+x.jpg');
    },
    function (Image $image) {
        $image -> brightness(-50) -> save(__DIR__ . '/brightness.-x.jpg');
    },
    function (Image $image) {
        $image -> contrast(50) -> save(__DIR__ . '/contrast.+x.jpg');
    },
    function (Image $image) {
        $image -> contrast(-50) -> save(__DIR__ . '/contrast.-x.jpg');
    },
    function (Image $image) {
        $image -> grayscale() -> save(__DIR__ . '/grayscale.jpg');
    },
    function (Image $image) {
        $image -> invert() -> save(__DIR__ . '/invert.jpg');
    },
    function (Image $image) {
        $image -> colorize(50, 0, 0) -> save(__DIR__ . '/colorize.x.0.0.jpg');
    },
    function (Image $image) {
        $image -> colorize(0, 50, 0) -> save(__DIR__ . '/colorize.0.x.0.jpg');
    },
    function (Image $image) {
        $image -> colorize(0, 0, 50) -> save(__DIR__ . '/colorize.0.0.x.jpg');
    },
    function (Image $image) {
        $image -> pixelate(5) -> save(__DIR__ . '/pixelate.x.jpg');
    },
    function (Image $image) {
        $image -> blur(5) -> save(__DIR__ . '/blur.x.jpg');
    },
    function (Image $image) {
        $image -> limitColors(64) -> save(__DIR__ . '/limitColors.x.jpg');
    }
];

$result = array();

foreach ($methods as $method) {

    $method(Image::make($photo));

    $closure = new ReflectionFunction($method);
    $start_line = $closure -> getStartLine();
    $end_line = $closure -> getEndLine();
    $line = array_slice(file(__FILE__), $start_line, $end_line - $start_line - 1);
    $line = trim(reset($line));
    $call = substr($line, 0, strpos($line, ')') + 1);
    preg_match('/\'\/([^\']+)\'/', $line, $matches);

    $result[] = ['call' => $call, 'file' => $matches[1]];

}

$content = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>intervention/image: Image handling and manipulation library</title>
    <link href="vendor/twitter/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h1><code>intervention/image</code> Image handling and manipulation library</h1>
HTML;

foreach ( $result as $method ) {

    $content .= <<<HTML
<h2><code>{$method['call']}</code></h2>
<div class="row">
    <div class="col-md-6"><img src="photo.jpg" class="thumbnail"></div>
    <div class="col-md-6"><img src="{$method['file']}" class="thumbnail"></div>
</div>
HTML;

}

$content .= <<<HTML
</body>
</html>
HTML;

file_put_contents(__DIR__ . '/index.html', $content);