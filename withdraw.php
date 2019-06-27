<?php
require('config.php');

debug('「「「「「「「「「');
debug('「　退会ページ  「');
debug('「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

$current_user = get_user($_SESSION['user_id']);

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  try {
    if(query_result(change_delete_flg($current_user,1))){
     //セッション削除
      session_destroy();
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      header("Location:login_form.php");
      exit();
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
debug('------------------------------');

$site_title = '退会';
$css_file_title = 'setting';
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
        <button class="btn red" name="withdraw" type="submit">退会する</button>
      </form>
    </div>

  </div>
  <?php require('footer.php'); ?>
