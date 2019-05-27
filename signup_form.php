<?php
require_once("signup_process.php");

$site_title = '新規登録';
$css_file_title = $js_file_title = 'signup';
require_once('head.php');
 ?>

<body>
  <?php require_once('header.php') ?>
  <div class="signup_form">
    <h2>とうろく</h2>

    <?php if (isset($flash_messages)): ?>
      <?php foreach ((array)$flash_messages as $message): ?>
        <p class ="flash_message <?php echo $flash_type ?>"><?php echo $message?></p>
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
        <input id="password" autocomplete="flase" type="password" name="pass" value="<?php if (isset($oldpass)) echo $oldpass ?>">
        <span class="js_error_message"></span><br>

        <input id="signup_btn" class="btn" type="submit" name="" value="とうろくする" disabled>
      </form>
    </div>
  </div>
<?php require_once('footer.php'); ?>
