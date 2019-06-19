<?php
require_once('config.php');
debugLogStart();
debug(print_r($_FILES,true));

$max_file_size = 10485760;

if($max_file_size < $_SERVER["CONTENT_LENGTH"]){
  set_flash('error','ファイルサイズは10M以下にしてください');
  exit();
}

//元の画像のサイズを取得する
$file = $_FILES["icon"]["tmp_name"];

 // 画像タイプ判定用
$image_type = exif_imagetype($file);

//元の画像を読み込む
if ($image_type === IMAGETYPE_JPEG){
  $baseImage = ImageCreateFromJPEG($file);
}else if($image_type === IMAGETYPE_PNG){
  $baseImage = ImageCreateFromPNG($file);
}

// 画像保存先のパス
$save_path1 = "img/".sha1_file($_FILES["icon"]["tmp_name"]).image_type_to_extension($image_type);
$save_path2 = "img/small".sha1_file($_FILES["icon"]["tmp_name"]).image_type_to_extension($image_type);


//元画像の縦横の大きさを比べてどちらかにあわせる
// なおかつ縦横の差をコピー開始位置として使えるようセット
list($w, $h) = getimagesize($file);

if($w > $h){
    $diff  = ($w - $h) * 0.5;
    $diffW = $h;
    $diffH = $h;
    $diffY = 0;
    $diffX = $diff;
}elseif($w < $h){
    $diff  = ($h - $w) * 0.5;
    $diffW = $w;
    $diffH = $w;
    $diffY = $diff;
    $diffX = 0;
}elseif($w === $h){
    $diffW = $w;
    $diffH = $h;
    $diffY = 0;
    $diffX = 0;
}
//大アイコンのサイズ
$iconW = 64;
$iconH = 64;

// 小アイコンのサイズ
$small_iconW = 48;
$small_iconH = 48;

//アイコンになる土台の画像を作る
$new_icon = imagecreatetruecolor($iconW, $iconH);
$new_small_icon = imagecreatetruecolor($small_iconW, $small_iconH);

//アイコンになる土台の画像に合わせて元の画像を縮小しコピーペーストする
imagecopyresampled($new_icon, $baseImage, 0, 0, $diffX, $diffY, $iconW, $iconH, $diffW, $diffH);
imagecopyresampled($new_small_icon, $baseImage, 0, 0, $diffX, $diffY, $small_iconW, $small_iconH, $diffW, $diffH);

imagejpeg($new_icon, $save_path1);
imagejpeg($new_small_icon, $save_path2);

echo json_encode($save_path1);
debug('------------------------------');