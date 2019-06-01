<?php
require_once("config.php");

debug('「「「「「「「「「「「');
debug('「　新規登録ページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

function check_delete_flg($email){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM users
          WHERE email = :email AND delete_flg = 1 LIMIT 1";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $removed_user = $stmt->fetch();
  return $removed_user;
}
// ログイン中ならマイページへ
check_logged_in();

//エラー発生時の入力保持
set_old_form_data('name');
set_old_form_data('email');
set_old_form_data('pass');

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

  //メッセージをsessionに格納（エラーが発生したら定数で上書きされる）
  set_flash('error',$error_messages);

  //エラーがなければ次の処理に進む
  if(empty($error_messages)){
    debug('バリデーションOK');
    try {
      $dbh = dbConnect();
      $sql = 'INSERT INTO users(name,email,password,created_at)
              VALUES(:name,:email,:password,:created_at)';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':name' => $name , ':email' => $email , ':password' => $pass , ':created_at' => date('Y-m-d H:i:s')));

      if (query_result($stmt)) {
        debug('クエリ成功しました');

        //フォーム入力保持用のsession破棄
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['pass']);

        //登録したユーザーをログインさせる
        $sesLimit = 60*60;
        $_SESSION['login_date'] = time();
        $_SESSION['login_limit'] = $sesLimit;
        $_SESSION['user_id'] = $new_user_id = $dbh->lastInsertId();

        set_flash('sucsess','登録が完了しました');

        debug('新規登録成功');
        debug(print_r($_SESSION['flash'],true));
        header("Location:mypage.php?page_id=${new_user_id}");
        exit();
      }
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
    }
  }
  debug('新規登録失敗');
  debug(print_r($_SESSION['flash'],true));

  header('Location:signup_form.php');
}
debug('------------------------------');
