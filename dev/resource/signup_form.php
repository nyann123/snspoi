<?php
require_once("config.php");

debug('「「「「「「「「「「「');
debug('「　新規登録ページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

//ログイン中はアクセスできないように
check_logged_in();

//エラー発生時の入力保持
set_old_form_data('name');
set_old_form_data('email');
set_old_form_data('pass');

//送信されていれば新規登録処理
if(!empty($_POST)){
  debug('POST送信があります。');
  require_once("signup_process.php");
}

$site_title = '新規登録';
$js_file = 'signup';
require_once('head.php');
 ?>

<body>
  <?php require_once('header.php') ?>
  <div class="form_container border_white">
    <h2 class="page_title">新規登録</h2>

    <?php if (isset($flash_messages)): ?>
      <?php foreach ((array)$flash_messages as $message): ?>
        <p class ="flash_message <?= $flash_type ?>"><?= $message?></p>
      <?php endforeach ?>
    <?php endif ?>

    <div class="form_inner">
      <form action="#" method="post">
        <span class="flash_cursor">｝</span>

        <label for="name">ユーザー名 <span>※最大８文字</span></label><br>
        <input id="name" type="text" name="name" value="<?php if (isset($oldname)) echo h($oldname); ?>">
        <span class="js_error_message"></span><br>

        <label for="email">メールアドレス</label><br>
        <input id="email" type="text" name="email" value="<?php if (isset($oldemail)) echo h($oldemail) ?>">
        <span class="js_error_message"></span><br>

        <label for="password">パスワード <span>※半角英数６文字以上</span> </label><br>
        <input id="password" type="password" name="pass" value="<?php if (isset($oldpass)) echo h($oldpass) ?>">
        <span class="js_error_message"></span><br>

        <button id="js_btn" class="btn blue" type="submit" disabled>登録</button>
        <a href="login_form.php" class="login">ログインページへ</a>

      </form>
    </div>
  </div>
<?php require_once('footer.php'); ?>
