<?php
// データベースの接続準備
function dbConnect(){
  //DBへの接続準備
  $url = parse_url(getenv('CLEARDB_DATABASE_URL'));

   $server = $url["host"];
   $username = $url["user"];
   $password = $url["pass"];
   $db = substr($url["path"], 1);

     $pdo = new PDO(
       'mysql:host=' . $server . ';dbname=' . $db . ';charset=utf8mb4',
       $username,
       $password,
       [
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
       ]
     );

  // PDOオブジェクト生成（DBへ接続）
  return $pdo;
}
