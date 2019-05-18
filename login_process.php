<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　ログインページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

// 送信されていればdb処理
if(!empty($_POST)){

  $email = $_POST['email'];
  $password = $_POST['password'];

  // メールのバリデーション
  if( empty($email) ){
    $error_messages[] = "メールアドレスを入力してください";
  }
  // パスワードのバリデーション
  if ( empty($password) ) {
    $error_messages[] = "パスワードを入力してください";
  }


  if(empty($error_messages)){

    //DBからユーザーを取得
    try {
      $sql = "select password,id from users where email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      $error_messages[] = 'error';
    }

    //パスワードでユーザー認証
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];

      $_SESSION['flash']['type'] = 'flash_sucsess';
      $_SESSION['flash']['message'] = "ログインしました";
      debug($_SESSION);
      header('Location:mypage.php');
    }else{
      $error_messages[] = "メールアドレス又はパスワードが間違っています。";
    }

  }else{
    $_SESSION['flash']['type'] = 'flash_error';
    $_SESSION['flash']['message'] = $error_messages;
    header('Location:login_form.php');
  }
}
