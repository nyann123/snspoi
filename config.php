<?php

error_reporting(E_ALL); //E_STRICTレベル以外のエラーを報告する
ini_set('display_errors','On'); //画面にエラーを表示させるか

//================================
//ログを取るか
//================================
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('debug：'.$str);
  }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("C:/var/tmp");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
  debug('セッションID：'.session_id());
  debug('セッション変数の中身：'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ：'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug( 'ログイン期限日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
  }
}

//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('ERR_MSG1','エラーが発生しました。しばらく経ってからやり直してください。');
define('ERR_MSG2','投稿が見つかりません');

//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$error_messages = array();

//================================
// バリデーション関数
//================================
function check_email_duplicate($email){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM users
          WHERE email = :email AND delete_flg = 0 LIMIT 1";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $user = $stmt->fetch();
  return $user ? true : false;
}

//お気に入りの重複チェック
function check_favolite_duplicate($user_id,$post_id){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM favorite
          WHERE user_id = :user_id AND post_id = :post_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id , ':post_id' => $post_id));
  $favorite = $stmt->fetch();
  return $favorite ? true : false;
}

function valid_name($name){
  global $error_messages;
  if ( empty($name) ){
    $error_messages['name'] = 'なまえを入力してください';
  }elseif( strlen($name) > 10 ){
    $error_messages['name'] = 'なまえは10文字以内で入力してください';
  }
}

function valid_email($email){
  global $error_messages;
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
    $error_messages['email'] = 'Emailの形式で入力してください';
  }elseif ( check_email_duplicate( $email ) ){
    $error_messages['email'] = 'すでに登録済みのメールアドレスです';
  }
}

function valid_password($pass){
  global $error_messages;
  if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,100}+\z/i', $pass)) {
    $pass = password_hash($pass, PASSWORD_DEFAULT);
  }else{
    $error_messages['pass'] = 'パスワードは半角英数字をそれぞれ1文字以上含んだ6文字以上で設定してください。';
  }
}

function valid_post_length($post){
  global $error_messages;
  if (empty($post)){
    $error_messages = '投稿の内容がありません';
  }else if(strlen($post) >= 150){
    $error_messages = '内容が長すぎます';
  }
}

//================================
// データベース
//================================
function dbConnect(){
  //DBへの接続準備
  $dsn = 'mysql:host=localhost;dbname=hogetest';
  $user = 'hoge';
  $password = 'hoge';
  $options = array(
    // SQL実行失敗時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  // PDOオブジェクト生成（DBへ接続）
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}

function query_result($stmt){
  if($stmt){
    debug('クエリ成功しました');
    return true;
  }else{
    debug('クエリ失敗しました。');
    set_flash('error',ERR_MSG1);
    return false;
  }
}

function get_user($user_id){
  debug('ユーザー情報を取得します');
  try {
    $dbh = dbConnect();
    $sql = "SELECT *
            FROM users
            WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $user_id));

    if($stmt){
      debug('成功');
    }else{
      debug('失敗しました');
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function get_post($page_id){
  debug('ユーザー投稿を取得します');
  try{
    $dbh = dbConnect();
    $sql = "SELECT posts.id,user_id,name,post_content,posts.created_at
            FROM users INNER JOIN posts ON users.id = posts.user_id
            WHERE :id = posts.user_id AND posts.delete_flg = 0
            ORDER BY posts.created_at DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $page_id));

    if($stmt){
      debug('成功');
    }else{
      debug('失敗しました');
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function get_all_post(){
  debug('全ての投稿を取得します');
  try{
    $dbh = dbConnect();
    $sql = "SELECT posts.id,user_id,name,post_content,posts.created_at
            FROM users INNER JOIN posts ON users.id = posts.user_id
            WHERE posts.delete_flg = 0
            ORDER BY posts.created_at DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    if($stmt){
      debug('成功');
    }else{
      debug('失敗しました');
    }

    return $user_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }
}

function get_favorite_post($page_id){
  debug('お気に入り投稿を取得します');
  try{
    $dbh = dbConnect();
    $sql = "SELECT  users.name,posts.id,posts.user_id,post_content,posts.created_at
            FROM users INNER JOIN favorite ON users.id = favorite.user_id
            INNER JOIN posts ON posts.id = favorite.post_id
            WHERE favorite.user_id = :id AND delete_flg = 0
            ORDER BY favorite.id DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $page_id));

    if($stmt){
      debug('成功');
    }else{
      debug('失敗しました');
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

function change_delete_flg($user,$flg){
  $dbh = dbConnect();
  $sql1 = 'UPDATE users SET  delete_flg = :flg WHERE id = :id';
  $stmt1 = $dbh->prepare($sql1);
  $stmt1->execute(array(':flg' => $flg , ':id' => $user['id']));
  //postsテーブル
  $sql2 = 'UPDATE posts SET  delete_flg = :flg WHERE user_id = :id';
  $stmt2 = $dbh->prepare($sql2);
  $stmt2->execute(array(':flg' => $flg , ':id' => $user['id']));
  //favoriteテーブル
  $sql3 = 'UPDATE favorite SET  delete_flg = :flg WHERE user_id = :id';
  $stmt3 = $dbh->prepare($sql3);
  $stmt3->execute(array(':flg' => $flg , ':id' => $user['id']));

  return $stmt1;
}

function get_form_data($str){
  global $user;
  // ユーザーデータがある場合
  if(isset($user)){
    //フォームのエラーがある場合
    if(isset($error_messages[$str])){
      //POSTにデータがある場合
      if(isset($_POST[$str])){
        return $_POST[$str];
      }else{
        //ない場合（基本ありえない）はDBの情報を表示
        return $user[$str];
      }
    }else{
      //POSTにデータがあり、DBの情報と違う場合
      if(isset($_POST[$str]) && $_POST[$str] !== $user[$str]){
        return $_POST[$str];
      }else{
        return $user[$str];
      }
    }
  }else{
    if(isset($_POST[$str])){
      return $_POST[$str];
    }
  }
}

//================================
// その他
//================================
//ユーザーをログインさせる
function login($user,$pass_save){
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
  debug('ログイン成功');
}
//ログイン中ユーザーのアクセス制限
function check_logged_in(){
  if (isset($_SESSION['user_id'])){
    debug('ログイン中のユーザーはアクセスできません');
    header("Location:mypage.php?page_id=${_SESSION['user_id']}");
    exit();
  }
}
// フラッシュメッセージ用処理
function set_flash($type,$message){
  $_SESSION['flash']['type'] = "flash_${type}";
  $_SESSION['flash']['message'] = $message;
}

if( isset($_SESSION['flash']) ){
  $flash_messages = $_SESSION['flash']['message'];
  $flash_type = $_SESSION['flash']['type'];
}
unset($_SESSION['flash']);
