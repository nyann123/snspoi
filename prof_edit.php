<?php

require('config.php');

debug('「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ「');
debug('「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

// DBからユーザーデータを取得
$user = get_user($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($user,true));

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));

  $name = $_POST['username'];
  $email = $_POST['email'];
  $user_id = $user['id'];

  //DBの情報と入力情報が異なる場合にバリデーションを行う
  if($user['name'] !== $name){
    valid_name($name);
  }
  if($user['email'] !== $email){
    validEmail($email);
  }

  if(empty($error_messages)){
    debug('バリデーションOKです。');

    try {
      $dbh = dbConnect();
      $sql = 'UPDATE users  SET name = :u_name, email = :email WHERE id = :u_id';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':u_name' => $name , ':email' => $email, ':u_id' => $user['id']));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // クエリ成功の場合
      if($stmt){
        debug('クエリ成功。');
        $_SESSION['flash']['type'] = 'flash_sucsess';
        $_SESSION['flash']['message'] = 'プロフィールの編集が完了しました';

        header("Location:mypage.php?page_id=${user_id}");

      }else{
        debug('クエリに失敗しました。');
        $err_msg['common'] = MSG08;
      }

    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}

$site_title = 'プロフィール編集';
// $css_title = ''
require_once('head.php');
?>

<body class="page-profEdit page-2colum page-logined">

  <!-- メニュー -->
  <?php
  require('header.php');
  ?>

  <!-- メインコンテンツ -->
    <h1 class="page-title">プロフィール編集</h1>
    <!-- Main -->
    <section id="main" >
      <div class="form-container">
        <form action="" method="post" class="form">
          <div class="area-msg">
            <?php
            if(!empty($err_msg['common'])) echo $err_msg['common'];
            ?>
          </div>
          <label class="<?php if(!empty($err_msg['username'])) echo 'err'; ?>">
            名前
            <input type="text" name="username" value="<?php echo get_form_data('name'); ?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($err_msg['username'])) echo $err_msg['username'];
            ?>
          </div>
          <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
            Email
            <input type="text" name="email" value="<?php echo get_form_data('email'); ?>">
          </label>
          <div class="area-msg">
            <?php
            if(!empty($error_messages['email'])) echo $error_messages['email'];
            ?>
          </div>

          <div class="btn-container">
            <input type="submit" class="btn btn-mid" value="変更する">
          </div>
        </form>
      </div>
    </section>
