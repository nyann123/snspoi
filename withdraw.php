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
$css_title = '';
require('head.php');
?>

  <body class="page-withdraw page-1colum">
    <!-- メニュー -->
    <?php
    require('header.php');
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main" >
        <div class="form-container">
          <form action="" method="post" class="form">
            <h2 class="title">退会</h2>
            <div class="area-msg">
              <?php
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="退会する" name="submit">
            </div>
          </form>
        </div>
        <a href="mypage.php">&lt; マイページに戻る</a>
      </section>
    </div>

    <!-- footer -->
    <?php
    require('footer.php');
    ?>
