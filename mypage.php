<?php
require_once('config.php');

if (!isset($_SESSION['user_id'])){
  $_SESSION['flash'] = "ログインしてください";
  header('Location:login_form.php');
}

try {
  $sql = "select * from users where id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':id' => $_SESSION['user_id']));
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
  $error_messages[] = 'error';
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
    <h2 class="mypage2">MyPage</h2>
    <div class="mypage">
      <div class="user_info">
        ようこそ
        <?php  echo $user['name']; ?>
        さん
      </div>
      <div class="messages">
        <form class ="post_form" action="#" method="post">
          <textarea name="name" rows="8" cols="80"></textarea><br>
          <input id="post_btn" type="submit" name="" value="投稿">
        </form>
        <?php foreach($user as $info): ?>
        <p><?php echo $info ?></p>
        <?php endforeach ?>
      </div>
    </div>
  </body>
</html>
