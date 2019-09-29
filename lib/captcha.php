<?php
    session_start();

    $captchaChars = 'ABCDEFGHIJKMNPQRSTUVWXYZ23456789abcdefghijklmnpqrstuvwxyz';
    $captchaCode = substr(str_shuffle($captchaChars), 0, 6);

    $_SESSION['captchaCode'] = $captchaCode;

    header('Content-type: image/png');

    $image = imagecreate(100, 18);
    imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 0, 0, 0);
    imagestring($image, 5, 2, 1, $captchaCode, $textColor);
    imagepng($image);
?>