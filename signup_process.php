<?php
require_once("config.php");

function cheak_email_duplicate($email, $pdo){
  $sql = "select * from users where email = :email limit 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $user = $stmt->fetch();
  return $user ? true : false;
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

if( isset($_SESSION['password']) ){
  $oldpassword = $_SESSION['password'];
}
unset($_SESSION['password']);

// 送信されていれば実行する
if(!empty($_POST)){
  $name = $_SESSION['name'] = $_POST['name'];
  $email = $_SESSION['email'] = $_POST['email'];
  $password = $_SESSION['password'] = $_POST['password'];

  // 名前のバリデーション
  if ( empty($name) ){
    $error_messages[] = 'なまえを入力してください';
  }elseif( strlen($name) > 10 ){
    $error_messages[] = 'なまえは10文字以内で入力してください';
  }

  // メールのバリデーション
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
    $error_messages[] = 'Emailの形式で入力してください';
  }elseif ( cheak_email_duplicate( $email,$pdo ) ){
    $error_messages[] = 'すでに登録済みのメールアドレスです';
  }

  // パスワードのバリデーション
  if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,100}+\z/i', $password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
  }else{
    $error_messages[] = 'パスワードは半角英数字をそれぞれ1文字以上含んだ6文字以上で設定してください。';
  }

  if(empty($error_messages)){

    try {
      $sql = "insert into users(name,email,password,created_at) value(:name,:email,:password,:created_at)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':name' => $name , ':email' => $email , ':password' => $password , ':created_at' => date('Y-m-d H:i:s')));

      $_SESSION['flash']['type'] = "flash_sucsess";
      $_SESSION['flash']['message'] = '登録が完了しました';

      unset($_SESSION['name']);
      unset($_SESSION['email']);
      unset($_SESSION['password']);

      header('Location:login_form.php');
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
  }else{
    $_SESSION['flash']['type'] = 'flash_error';
    $_SESSION['flash']['message'] = $error_messages;
    header('Location:signup_form.php');
  }
}
