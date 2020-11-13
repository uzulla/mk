<?php

$glob = glob(__DIR__ . '/testsvg/*');
// var_dump($glob);

foreach ($glob as $svg) {
    echo $svg.PHP_EOL;
    $im = new Imagick();
    // $im->setBackgroundColor(new ImagickPixel('transparent'));
    # リサイズ前提で少々大きめにラスタライズする
    $im->setResolution(1000, 1000);
    $im->readImage($svg);

    $im->setImageFormat("jpeg");
    $im->setImageCompressionQuality(80);

    $im->thumbnailImage(128, 128, true, true);
    // $im->resizeImage(720, 445, Imagick::FILTER_LANCZOS, 1);

    $im->writeImage($svg.".jpg");
    echo $svg.".jpg".PHP_EOL;
}
