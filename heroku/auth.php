<?php

//================================
// ログイン認証・自動ログアウト
//================================
//ログインしていない場合
if( empty($_SESSION['user_id']) ){
  debug('未ログインユーザーです。');
  //ユーザーページはログインしてなくても見られるように
  if(basename($_SERVER['PHP_SELF']) === 'user_page.php'){
    // タイムラインは見れないように
    //(タイムラインは投稿取得処理にログイン中のユーザーIDを使用しているため)
    if ($_GET['type'] === 'timeline') {
      set_flash('error','ログインしてください');
      header("Location:login_form.php");
      exit();
    }
  }elseif(basename($_SERVER['PHP_SELF']) !== 'login_form.php'){
    set_flash('error','ログインしてください');
    header("Location:login_form.php");
    exit();
  }
  // 現在日時が最終ログイン日時＋有効期限を超えていた場合
}else if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
    debug('ログイン有効期限オーバーです。');
    session_destroy();
    header('Location:login_form.php');
    exit();

}else{
  debug('ログイン済みユーザーです。');
  $_SESSION['login_date'] = time();
}
