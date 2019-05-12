<?php
require_once('config.php');

if (isset($_SESSION['user'])){
  echo $_SESSION['user'];
}else{
  $_SESSION['flash'] = "ログインしてください";
  header('Location:login_form.php');
}
 ?>
 
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MyPage</title>
  </head>
  <body>
    <header>
      <a href="login_form.php">home</a>
      <a href="logout_process.php">ログアウト</a>
    </header>
    <h1>MyPage</h1>
  </body>
</html>
