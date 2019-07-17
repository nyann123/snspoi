<?php
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
//バリデーションエラーがなければ処理を続ける
if(empty($error_messages)){
  debug('バリデーションOK');

  //emailでユーザー情報を取得
  try {
    $dbh = dbConnect();
    $sql = "SELECT password,id,delete_flg
            FROM users
            WHERE email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':email' => $email));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    debug('クエリ結果の中身：'.print_r($user,true));

    //パスワードでユーザー認証
    if (isset($user) && password_verify($password, $user['password'])) {

      //delete_flgが1ならユーザー復元処理
      if($user['delete_flg']){
        debug('ユーザー情報を復元します');
        change_delete_flg($user,0);

        // ログインさせる
        login($user['id'],$pass_save);
        set_flash('sucsess','登録されていたユーザーを復元しました');

        debug('セッション変数の中身：'.print_r($_SESSION,true));

        header("Location:user_page.php?page_id=${user['id']}&type=main");
        exit();
      }else{
        // ログインさせる
        login($user['id'],$pass_save);
        set_flash('sucsess','ログインしました');

        debug('セッション変数の中身：'.print_r($_SESSION,true));

        header("Location:user_page.php?page_id=${user['id']}&type=main");
        exit();
      }
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

reload();

debug('------------------------------');
