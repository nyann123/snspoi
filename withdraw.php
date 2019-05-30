<?php

//共通変数・関数ファイルを読込み
require('config.php');

debug('「「「「「「「「「');
debug('「　退会ページ:未完成　「');
debug('「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

$user = get_user($_SESSION['user_id']);

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  try {
    if(query_result(change_delete_flg($user,1))){
     //セッション削除
      session_destroy();
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      header("Location:login_form.php");
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}


$site_title = '退会';
$css_file_title = 'withdraw';
require('head.php');
?>

  <body>
    <?php require('header.php'); ?>

    <div class="form_container">
      <h2 class="page_title">退会</h2>
      <form action="" method="post" class="form">
        <input id="btn" class="btn normal"type="submit" class="btn btn-mid" value="退会する" name="submit">
      </form>
      <a href="mypage.php?page_id=<?php echo $user['id'] ?>"></a>
    </div>

    <!-- footer -->
    <?php require('footer.php'); ?>
