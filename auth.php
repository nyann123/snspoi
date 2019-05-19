<?php

//================================
// ログイン認証・自動ログアウト
//================================
//ログインしていない場合
if( empty($_SESSION['user_id']) ){
  debug('未ログインユーザーです。');

  if(basename($_SERVER['PHP_SELF']) !== 'login_form.php'){

    $_SESSION['flash']['type'] = "flash_error";
    $_SESSION['flash']['message'] = "ログインしてください";

  header("Location:login_form.php");
  }
  // 現在日時が最終ログイン日時＋有効期限を超えていた場合
}else if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
    debug('ログイン有効期限オーバーです。');
    session_destroy();
    header('Location:login_form.php');

}else{
  debug('ログイン済みユーザーです。');
  $_SESSION['login_date'] = time();
}
