<?php
require_once('login_process.php');
?>

<?php
$site_title = 'ログイン';
$css_title = 'login';
require('head.php');
?>
  <body>
    <header>
      <a href="logout_process.php">ログアウト</a>
      <a href="mypage.php">mypage</a>
    </header>
      <div class="login_form">
        <h2>ログイン</h2>


        <?php if (isset($flash_messages)): ?>
          <?php foreach ((array)$flash_messages as $message): ?>
            <p class ="flash_message <?php echo $flash_type ?>"><?php echo $message?></p>
          <?php endforeach ?>
        <?php endif ?>

        <div class="form_inner">
          <span class="flash_cursor">｝</span>
          <form action="#" method="post">
            <input id="email" type="email" name="email" placeholder="メールアドレス">
            <input id="password" type="password" name="password" placeholder="パスワード">
            <label id="pass_save" for="checkbox">
              <input id="checkbox" type="checkbox" name="pass_save">ログインを維持する
            </label><br>
            <input id="login_btn" class="btn" type="submit" name="" value="ログイン">
            <a href="signup_form.php" class="signup">新規登録はこちら</a>
          </form>
        </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
      <script src="js/login.js"></script>
  </body>
</html>
