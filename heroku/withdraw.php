<?php
require('config.php');


require('auth.php');

$current_user = get_user($_SESSION['user_id']);

// post送信されていた場合
if(!empty($_POST['withdraw'])){
  change_delete_flg($current_user,1);

 //セッション削除
  session_destroy();
  $_SESSION = array();

  header("Location:login_form.php");
  exit();
}

$site_title = '退会';
$js_file = 'user_page';
require('head.php');
?>

<body>
  <?php require('header.php'); ?>
  <div class="container flex">
    <!-- メニュー -->
  <?php require_once('setting_menu.php'); ?>

    <div class="setting_container border_white">
      <h2 class="page_title withdraw">退会</h2>
      <form action="" method="post">
        <button class="btn red" name="withdraw" value="withdraw" type="submit">退会する</button>
      </form>
    </div>

  </div>
  <?php require('footer.php'); ?>
