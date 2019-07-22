<?php
$name = $_SESSION['name'] = $_POST['name'];
$pass = $_SESSION['pass'] = $_POST['pass'];
$email = $provisional_user['email'];

//入力のバリデーション
valid_name($name);
valid_password($pass);

set_flash('error',$error_messages);

//エラーがなければ次の処理に進む
if(empty($error_messages)){
  
  $date = new DateTime();
  $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));

  try {
    // 新規登録
    $dbh = dbConnect();
    $sql = 'INSERT INTO users(name,email,password,created_at)
            VALUES(:name,:email,:password,:created_at)';
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':name' => $name ,
                         ':email' => $email ,
                         ':password' => password_hash($pass,PASSWORD_DEFAULT),
                         ':created_at' => $date->format('Y-m-d H:i:s')));
      //フォーム入力保持用のsession破棄
      unset($_SESSION['name']);
      unset($_SESSION['pass']);

      //登録したユーザーをログインさせる
      $sesLimit = 60*60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sesLimit;
      $_SESSION['user_id'] = $new_user_id = $dbh->lastInsertId();

      //仮登録テーブルから削除
      $sql = 'DELETE
              FROM provisional_users
              WHERE email = :email';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':email' => $email));

      set_flash('sucsess','登録が完了しました');

      header("Location:user_page.php?page_id=${new_user_id}&type=main");
      exit();
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}

reload();
