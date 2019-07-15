<?php
require_once('vendor/autoload.php');
require_once('config.php');
require_once('auth.php');


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

// 画像一時保存先のパス
$save_path = sha1_file($_FILES["icon"]["tmp_name"]).image_type_to_extension($image_type);

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
//アイコンのサイズ
$iconW = 64;
$iconH = 64;

//アイコンになる土台の画像を作る
$new_icon = imagecreatetruecolor($iconW, $iconH);

//アイコンになる土台の画像に合わせて元の画像を縮小しコピーペーストする
imagecopyresampled($new_icon, $baseImage, 0, 0, $diffX, $diffY, $iconW, $iconH, $diffW, $diffH);

imagejpeg($new_icon, $save_path);

//================================
//  S3アップロード
//================================

$bucket_version = 'latest';
$bucket_region = 'ap-northeast-1';
$bucket_name = getenv('S3_BUCKET_NAME');

$credentials = [
  'key' => getenv('AWS_ACCESS_KEY_ID'),
  'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
];

$s3 = new Aws\S3\S3Client([
    'credentials' => $credentials,
    'region'  => $bucket_region,
    'version' => $bucket_version,
]);

$params = [
  'ACL' => 'public-read',
  'Bucket' => $bucket_name,
  'Key' => 's3/'.$save_path,
  'SourceFile'   => $save_path,
  'ContentType' => mime_content_type($save_path)
];

 $result = $s3 -> putObject($params);

  //読み取り用のパスを返す
 $path = $result['ObjectURL'];
 // file_put_contents("php://stderr", $result);
 // file_put_contents("php://stderr", $path);


echo json_encode($path);
