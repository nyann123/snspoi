<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　ログインページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

//ログイン中ならマイページへ
cheak_logged_in();

// 送信されていればログイン処理
if(!empty($_POST)){
  debug('POST送信があります。');

  $email = $_POST['email'];
  $password = $_POST['password'];
  $pass_save = (isset($_POST['pass_save'])) ? true : false;

  // メールのバリデーション
  if( empty($email) ){
    $error_messages['email'] = "メールアドレスを入力してください";
  }
  // パスワードのバリデーション
  if ( empty($password) ) {
    $error_messages['pass'] = "パスワードを入力してください";
  }

  if(empty($error_messages)){
    debug('バリデーションOK')
    //emailでユーザー情報を取得
    try {
      $dbh = dbConnect();
      $sql = "SELECT password,id FROM users WHERE email = :email";
      $stmt = $dbh->prepare($sql);
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
          $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        }else{
          debug('ログイン保持にチェックはありません。');
          $_SESSION['login_limit'] = $sesLimit;
        }

        $_SESSION['user_id'] = $user['id'];
        set_flash('sucsess',"ログインしました");

        debug('ログイン成功');
        debug('セッション変数の中身：'.print_r($_SESSION,true));
        header("Location:mypage.php?page_id=${user['id']}");
        exit();
      }else{
        $error_messages[] = "メールアドレス又はパスワードが間違っています。";
      }
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $error_messages = ERR_MSG1;
    }
  }
  set_flash('error',$error_messages);

  debug('ログイン失敗');
  debug(print_r($_SESSION['flash'],true));

  header('Location:login_form.php');
}
