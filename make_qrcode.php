<?php
mkqr("MDMS.D1234");

function mkqr($data){
    include ('./phpqrcode/qrlib.php');

    $tmp_url='./img/tmp/'.$data.'.png';
    QRcode::png('MDMS.D0001',$tmp_url,'H',3,4);
    $img = imagecreatefromstring(file_get_contents($tmp_url));
    $font = 'C:\Windows\Fonts\arial.ttf';
    $black = imagecolorallocate($img,0, 0, 0);
    $fontSize = 8;
    $circleSize = 0;
    $left = 10;
    $top = 85;
    imagettftext($img, $fontSize, $circleSize, $left, $top, $black, $font, $data);
    /*//test
    Header("Content-type: image/png");
    imagepng($img);*/
    imagepng($img,'./img/deviceqrcode/'.$data.'.png');
    imagedestroy($img);

    //header('Location:./img/deviceqrcode/'.$data.'.png');
    echo '<img src="./img/deviceqrcode/'.$data.'.png" />';
}
?>