<?php
require_once('login_process.php');
?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/login.css">
    <meta charset="utf-8">
    <title>home</title>
  </head>
  <body>
    <header>
      <a href="logout_process.php">ログアウト</a>
      <a href="mypage.php">mypage</a>
    </header>
      <div class="login_form">
        <h2>ログイン</h2>

        <?php if (isset($flash_messages)): ?>
          <?php foreach ((array)$flash_messages as $error_message): ?>
            <p class ="php_message"><?php echo $error_message?></p>
          <?php endforeach ?>
        <?php endif ?>

        <div class="form_inner">
          <span class="flash_cursor">｝</span>
          <form action="#" method="post">
            <input id="email" type="email" name="email" placeholder="メールアドレス">
            <input id="password" type="password" name="password" placeholder="パスワード">
            <input id="login_btn" type="submit" name="" value="ログイン">
            <a href="signup_form.php" class="signup">新規登録はこちら</a>
          </form>
        </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
      <script src="js/login.js"></script>
  </body>
</html>
