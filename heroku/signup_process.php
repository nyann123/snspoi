<?php
$name = $_SESSION['name'] = $_POST['name'];
$pass = $_SESSION['pass'] = $_POST['pass'];
$email = $provisional_user['email'];

//入力のバリデーション
valid_name($name);
valid_password($pass);

//メッセージをsessionに格納（エラーが発生したら定数で上書きされる）
set_flash('error',$error_messages);

//エラーがなければ次の処理に進む
if(empty($error_messages)){
  debug('バリデーションOK');
  try {
    // 新規登録
    $dbh = dbConnect();
    $sql = 'INSERT INTO users(name,email,password)
            VALUES(:name,:email,:password)';
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':name' => $name , ':email' => $email , ':password' => password_hash($pass,PASSWORD_DEFAULT)));
    if (query_result($stmt)) {
      debug('クエリ成功しました');

      //仮登録テーブルから削除
      $sql = 'DELETE
              FROM provisional_users
              WHERE email = :email';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':email' => $email));

      //フォーム入力保持用のsession破棄
      unset($_SESSION['name']);
      unset($_SESSION['pass']);

      //登録したユーザーをログインさせる
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $new_user_id = $dbh->lastInsertId();

      set_flash('sucsess','登録が完了しました');

      debug('新規登録成功');
      debug(print_r($_SESSION['flash'],true));
      header("Location:user_page.php?page_id=${new_user_id}&type=main");
      exit();
    }
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}
debug('新規登録失敗');
debug(print_r($_SESSION['flash'],true));

header("Location:signup_second.php?u_id=${u_id}");
debug('------------------------------');
