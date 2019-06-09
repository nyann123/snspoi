<?php
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
    $stmt->execute(array(':name' => $name , ':email' => $email , ':password' => password_hash($pass,PASSWORD_DEFAULT) , ':created_at' => date('Y-m-d H:i:s')));
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
      header("Location:user_page.php?page_id=${new_user_id}");
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
debug('------------------------------');
