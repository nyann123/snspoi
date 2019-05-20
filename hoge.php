<?php
require('config.php');

debug('「「「「「「「「「');
debug('「　退会ページ　「');
debug('「「「「「「「「「');
debugLogStart();

require('auth.php');

//================================
// 画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  try {
    // SQL文作成
    $sql1 = 'UPDATE users SET  delete_flg = 1 WHERE id = :us_id';
    $sql2 = 'UPDATE product SET  delete_flg = 1 WHERE user_id = :us_id';
    $sql3 = 'UPDATE like SET  delete_flg = 1 WHERE user_id = :us_id';
    $data = array(':us_id' => $_SESSION['user_id']);
    $stmt1 = queryPost($dbh, $sql1, $data);
    $stmt2 = queryPost($dbh, $sql2, $data);
    $stmt3 = queryPost($dbh, $sql3, $data);

    // クエリ実行成功の場合（最悪userテーブルのみ削除成功していれば良しとする）
    if($stmt1){
      session_destroy();
      debug('セッション変数の中身：'.print_r($_SESSION,true));
      debug('トップページへ遷移します。');
      header("Location:index.php");
    }else{
      debug('クエリが失敗しました。');
      $err_msg['common'] = MSG07;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}

$site_title = '退会';
$css_title = '';
class ClassName extends AnotherClass
{

  function __construct(argument)
  {
    // code...
  }
}

require_once('head.php');
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
