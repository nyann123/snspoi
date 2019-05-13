<?php
require_once('config.php');

if (!isset($_SESSION['user'])){
  $_SESSION['flash'] = "ログインしてください";
  header('Location:login_form.php');
}
 ?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/mypage.css">
    <meta charset="utf-8">
    <title>MyPage</title>
  </head>
  <body>
    <header>
      <a href="login_form.php">home</a>
      <a href="logout_process.php">ログアウト</a>
    </header>
    <h2>MyPage</h2>
  </body>
</html>
