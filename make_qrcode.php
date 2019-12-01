<?php
//mkqr("MDMS.D0005");

/*for ($i = 1; $i <= 30; $i++) {
    $s = $i;
    if ($i < 10)
        $s = '0' . $i;
    mkqr("MDMS.D00" . $s);
    mkqr("MDMS.D05" . $s);
}*/

function mkqr($data)
{
    include_once('./phpqrcode/qrlib.php');
    if (is_dir("./img")) {
        if (!is_dir("./img/tmp"))
            mkdir('./img/tmp');
        if (!is_dir("./img/deviceqrcode"))
            mkdir('./img/deviceqrcode');
    } else {
        mkdir("./img");
        mkdir('./img/tmp');
        mkdir('./img/deviceqrcode');
    }
    $tmp_url = './img/tmp/' . $data . '.png';
    $opt_url = './img/deviceqrcode/' . $data . '.png';
    QRcode::png($data, $tmp_url, 'H', 3, 4);
    $img = imagecreatefromstring(file_get_contents($tmp_url));
    $font = 'C:\Windows\Fonts\arial.ttf';
    $black = imagecolorallocate($img, 0, 0, 0);
    $fontSize = 8;
    $circleSize = 0;
    $left = 10;
    $top = 85;
    imagettftext($img, $fontSize, $circleSize, $left, $top, $black, $font, $data);
    /*//test
    Header("Content-type: image/png");
    imagepng($img);*/
    imagepng($img, $opt_url);
    imagedestroy($img);
    if (is_file($tmp_url))
        unlink($tmp_url);
    //header('Location:'.$opt_url);
    echo '<img src="' . $opt_url . '" />';
}

?>