<?php
require_once("config.php");

debug('「「「「「「「「「「「');
debug('「　新規登録ページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

function cheak_email_duplicate($email){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM users
          WHERE email = :email LIMIT 1";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $user = $stmt->fetch();
  return $user ? true : false;
}

function cheak_delete_flg($email){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM users
          WHERE email = :email LIMIT 1
          AND delete_flg = 1";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $delete_user = $stmt->fetch();
  return $delete_user ? true : false;
}
// ログイン中ならマイページへ
if (isset($_SESSION['user_id'])){
  debug('ログイン中のユーザーはアクセスできません');
  header('Location:mypage.php');
}

// まとめたい
if( isset($_SESSION['name']) ){
  $oldname = $_SESSION['name'];
}
unset($_SESSION['name']);

if( isset($_SESSION['email']) ){
  $oldemail = $_SESSION['email'];
}
unset($_SESSION['email']);

if( isset($_SESSION['pass']) ){
  $oldpass = $_SESSION['pass'];
}
unset($_SESSION['pass']);

// 送信されていれば登録処理
if(!empty($_POST)){
  debug('POST送信があります。');

  $name = $_SESSION['name'] = $_POST['name'];
  $email = $_SESSION['email'] = $_POST['email'];
  $pass = $_SESSION['pass'] = $_POST['pass'];

  //入力のバリデーション
  valid_name($name);
  valid_email($email);
  valid_password($pass);

  if(empty($error_messages)){
    try {
      $dbh = dbConnect();
      $sql = 'INSERT INTO users(name,email,password,created_at)
              VALUES(:name,:email,:password,:created_at)';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':name' => $name , ':email' => $email , ':password' => $pass , ':created_at' => date('Y-m-d H:i:s')));

      //フォーム入力保持用のsession破棄
      unset($_SESSION['name']);
      unset($_SESSION['email']);
      unset($_SESSION['pass']);
      //登録したユーザーをログインさせる
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $user_id = $dbh->lastInsertId();

      $_SESSION['flash']['type'] = "flash_sucsess";
      $_SESSION['flash']['message'] = '登録が完了しました';

      debug('新規登録成功');
      debug(print_r($_SESSION['flash'],true));
      header("Location:mypage.php?page_id=${user_id}");
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
  }else{
    $_SESSION['flash']['type'] = 'flash_error';
    $_SESSION['flash']['message'] = $error_messages;
    debug('新規登録失敗');
    debug(print_r($_SESSION['flash'],true));

    header('Location:signup_form.php');
  }
}
