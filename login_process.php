<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　ログインページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

//================================
// ログイン処理
//================================
// 送信されていればdb処理
if(!empty($_POST)){
  debug('POST送信があります。');

  $email = $_POST['email'];
  $password = $_POST['password'];
  $pass_save = (isset($_POST['pass_save'])) ? true : false;

  // メールのバリデーション
  if( empty($email) ){
    $error_messages[] = "メールアドレスを入力してください";
  }
  // パスワードのバリデーション
  if ( empty($password) ) {
    $error_messages[] = "パスワードを入力してください";
  }


  if(empty($error_messages)){

    //emailでユーザー情報を取得
    try {
      $sql = "select password,id from users where email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':email' => $email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      debug('クエリ結果の中身：'.print_r($user,true));

    //パスワードでユーザー認証
    if (isset($user) && password_verify($password, $user['password'])) {

      //ログイン有効期限（デフォルトを１時間とする）
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();

      // ログイン保持にチェックがある場合
      if($pass_save){
        debug('ログイン保持にチェックがあります。');
        // ログイン有効期限を30日にしてセット
        $_SESSION['login_limit'] = $sesLimit * 24 * 30;
      }else{
        debug('ログイン保持にチェックはありません。');
        // 次回からログイン保持しないので、ログイン有効期限を1時間後にセット
        $_SESSION['login_limit'] = $sesLimit;
      }

      $_SESSION['user_id'] = $user['id'];
      $_SESSION['flash']['type'] = 'flash_sucsess';
      $_SESSION['flash']['message'] = "ログインしました";

      debug('ログイン成功');
      debug('セッション変数の中身：'.print_r($_SESSION,true));

      header('Location:mypage.php');
    }else{
      $error_messages[] = "メールアドレス又はパスワードが間違っています。";
    }
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $error_messages[] = 'error';
  }

  }else{
    $_SESSION['flash']['type'] = 'flash_error';
    $_SESSION['flash']['message'] = $error_messages;

    debug('ログイン失敗');
    debug(print_r($_SESSION['flash'],true));

    header('Location:login_form.php');
  }
}
