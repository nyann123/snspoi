<?php
require_once("signup_process.php");
?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/signup.css">
    <meta charset="utf-8">
    <title>signup</title>
  </head>
  <body>
    <header>
    </header>
    <a href="login_form.php">login</a>
    <div class="signup_form">
      <h2>とうろく</h2>

      <?php if (isset($flash_messages)): ?>
        <?php foreach ((array)$flash_messages as $error_message): ?>
          <p class ="php_message"><?php echo $error_message?></p>
        <?php endforeach ?>
      <?php endif ?>

      <div class="form_inner">
        <form action="#" method="post">
          <span class="flash_cursor">｝</span>

          <label for="name">おなまえ</label><br>
          <input id="name" type="text" name="name" value="<?php if (isset($oldname)) echo $oldname; ?>">
          <span class="js_error_message"></span><br>

          <label for="email">メールアドレス</label><br>
          <input id="email" autocomplete="false" type="text" name="email" value="<?php if (isset($oldemail)) echo $oldemail?>">
          <span class="js_error_message"></span> <br>

          <label for="password">パスワード</label><br>
          <input id="password" autocomplete="flase" type="password" name="password" value="<?php if (isset($oldpassword)) echo $oldpassword ?>">
          <span class="js_error_message"></span><br>

          <input id="signup_btn" type="submit" name="" value="とうろくする" disabled>
        </form>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="js/signup.js"></script>
  </body>
</html>
