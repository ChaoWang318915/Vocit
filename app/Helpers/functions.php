<?php

use CodeItNow\BarcodeBundle\Utils\QrCode;

function getQrCode($text) {
    $qrCode = new QrCode();
    $qrCode
        ->setText($text)
        ->setSize(200)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
        ->setLabel($text)
        ->setLabelFontSize(16)
        ->setImageType(QrCode::IMAGE_TYPE_PNG)
    ;
    return $qrCode->generate();
}
