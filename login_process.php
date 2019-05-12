<?php
require_once('config.php');

// 送信されていればdb処理
if(!empty($_POST)){

  $error_messages = array();
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
      $sql = "select * from users where email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      $error_messages[] = 'error';
    }

    //パスワードでユーザー認証
    if (password_verify($_POST['password'], $user['password'])) {
      $_SESSION['user'] = $user['id'];
      header('Location:mypage.php');
    }else{
      $error_messages[] = "メールアドレス又はパスワードが間違っています。";
    }

  }else{
    $_SESSION['flash'] = $error_messages;
    header('Location:login_form.php');
  }
}
