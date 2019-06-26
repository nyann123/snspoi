<?php
// db接続情報の読み込み
require_once('db_connect.php');

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
//メールアドレスの重複チェック
function check_email_duplicate($email){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM users
          WHERE email = :email AND delete_flg = 0 LIMIT 1";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $user = $stmt->fetch();
  return $user;
}
//お気に入りの重複チェック
function check_favolite_duplicate($user_id,$post_id){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM favorite
          WHERE user_id = :user_id AND post_id = :post_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id ,
                       ':post_id' => $post_id));
  $favorite = $stmt->fetch();
  return $favorite;
}
// 名前のバリデーション
function valid_name($name){
  global $error_messages;
  if ( empty($name) ){
    $error_messages['name'] = 'なまえを入力してください';
  }elseif( mb_strlen($name, $post, "UTF-8") > 8 ){
    $error_messages['name'] = 'なまえは10文字以内で入力してください';
  }
}
// メールアドレスのバリデーション
function valid_email($email){
  global $error_messages;
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
    $error_messages['email'] = 'Emailの形式で入力してください';
  }elseif ( check_email_duplicate( $email ) ){
    $error_messages['email'] = 'すでに登録済みのメールアドレスです';
  }
}
// パスワードのバリデーション
function valid_password($pass){
  global $error_messages;
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,100}+\z/i', $pass)) {
    $error_messages['pass'] = 'パスワードは半角英数字をそれぞれ1文字以上含んだ6文字以上で設定してください。';
  }
}
// 投稿内容のバリデーション
function valid_post_length($post){
  global $error_messages;
  if (empty($post)){
    $error_messages = '投稿の内容がありません';
  }else if(mb_strlen($post, "UTF-8" ) > 150){
    $error_messages = '内容が長すぎます';
  }
}

//================================
// データベース
//================================
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
// ユーザー情報を取得する
function get_user($user_id){
  debug('ユーザー情報を取得します');
  try {
    $dbh = dbConnect();
    $sql = "SELECT id,name,email,user_icon,user_icon_small
            FROM users
            WHERE id = :id AND delete_flg = 0 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $user_id));
    if(query_result($stmt)){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}


// ユーザーの投稿を取得する
function get_posts($page_id,$type,$offset_count=0){
  debug(($offset_count + 1).'~'.($offset_count + 10).'件目のユーザー投稿を取得します');
  global $current_user;
  $dbh = dbConnect();

  switch ($type) {
    //自分の投稿を取得する
    case 'my_post':
    $sql = "SELECT u.name,u.user_icon_small,p.id,p.user_id,p.post_content,p.created_at
            FROM users u INNER JOIN posts p ON u.id = p.user_id
            WHERE p.user_id = :id AND p.delete_flg = 0
            ORDER BY p.created_at DESC
            LIMIT 10 OFFSET :offset_count";
    break;

    //お気に入り登録した投稿を取得する
    case 'favorite':
    $sql = "SELECT u.name,u.user_icon_small,p.id,p.user_id,p.post_content,p.created_at
            FROM posts p INNER JOIN favorite f ON p.id = f.post_id
            INNER JOIN users u ON u.id = p.user_id
            WHERE f.user_id = :id AND p.delete_flg = 0
            ORDER BY f.id DESC
            LIMIT 10 OFFSET :offset_count";
      break;
  }

  try{
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $page_id);
    $stmt->bindValue(':offset_count', $offset_count, PDO::PARAM_INT);
    $stmt->execute();
    if(query_result($stmt)){
      debug($stmt->rowCount().'件取得しました');
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}

// 自分とフォロー中のユーザー投稿取得
// $sql = "SELECT u.name,u.user_icon_small,p.id,p.user_id,p.post_content,p.created_at
//         FROM users u INNER JOIN posts p ON u.id = p.user_id
//         WHERE  p.user_id = :id AND p.delete_flg = 0
//         UNION
//         SELECT u.name,u.user_icon_small,p.id,p.user_id,p.post_content,p.created_at
//         FROM users u INNER JOIN posts p ON u.id = p.user_id
//         INNER JOIN follows ON follows.followed_id = p.user_id
//         WHERE follows.follow_id = :id AND p.delete_flg = 0
//         ORDER BY created_at DESC";

// 全ての投稿を取得する
function get_all_posts(){
  debug('全ての投稿を取得します');
  try{
    $dbh = dbConnect();
    $sql = "SELECT u.name,u.user_icon_small,p.id,p.user_id,p.post_content,p.created_at
            FROM users u INNER JOIN posts p ON u.id = p.user_id
            WHERE p.delete_flg = 0
            ORDER BY p.created_at DESC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    if(query_result($stmt)){
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  } catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
  }
}


// delete_flgを変更する
function change_delete_flg($user,$flg){
  $dbh = dbConnect();
  $dbh->beginTransaction();

  $sql1 = 'UPDATE users SET delete_flg = :flg WHERE id = :id';
  $stmt1 = $dbh->prepare($sql1);
  $stmt1->execute(array(':flg' => $flg , ':id' => $user['id']));
  //postsテーブル
  $sql2 = 'UPDATE posts SET delete_flg = :flg WHERE user_id = :id';
  $stmt2 = $dbh->prepare($sql2);
  $stmt2->execute(array(':flg' => $flg , ':id' => $user['id']));
  //favoriteテーブル
  $sql3 = 'UPDATE favorite SET delete_flg = :flg WHERE user_id = :id';
  $stmt3 = $dbh->prepare($sql3);
  $stmt3->execute(array(':flg' => $flg , ':id' => $user['id']));

  if (query_result($stmt1) && query_result($stmt2) && query_result($stmt3)) {
    $dbh->commit();
    return $stmt1;
  }else{
    $dbh->rollback();
  }
}

//既にフォローされているか確認する
function check_follow($follow_user,$followed_user){
  $dbh = dbConnect();
  $sql = "SELECT follow_id,followed_id
          FROM follows
          WHERE :follow_id =follow_id AND :followed_id = followed_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':follow_id' => $follow_user,
                       ':followed_id' => $followed_user));
  return  $stmt->fetch();
}

function get_follows($page_id){
  $dbh = dbConnect();
  $sql = "SELECT followed_id
          FROM follows
          WHERE :follow_id = follow_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':follow_id' => $page_id));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_followers($page_id){
  $dbh = dbConnect();
  $sql = "SELECT follow_id
          FROM followers
          WHERE :followed_id = followed_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':followed_id' => $page_id));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//ユーザーの各種カウントを取得する
function get_user_count($object,$user_id){
  $dbh = dbConnect();
  switch ($object) {
    case 'post':
    $sql ="SELECT COUNT(post_content)
          FROM posts
          WHERE user_id = :id";
      break;
    case 'follow':
    $sql ="SELECT COUNT(followed_id)
          FROM follows
          WHERE follow_id = :id";
      break;
    case 'follower':
    $sql ="SELECT COUNT(follow_id)
          FROM followers
          WHERE followed_id = :id";
      break;
    case 'favorite':
    $sql ="SELECT COUNT(post_id)
          FROM favorite
          WHERE user_id = :id";
  }
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':id' => $user_id));
  return $stmt->fetch();
}

//投稿のお気に入り数を取得する
function get_post_favorite_count($post_id){
  $dbh = dbConnect();
  $sql = "SELECT COUNT(user_id)
          FROM favorite
          WHERE post_id = :post_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':post_id' => $post_id));
  return $stmt->fetch();
}

//================================
// その他
//================================
//ユーザーをログインさせる
function login($user_id,$pass_save){
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
  $_SESSION['user_id'] = $user_id;
  debug('ログイン成功');
}
//ログイン中ユーザーのアクセス制限
function check_logged_in(){
  if (isset($_SESSION['user_id'])){
    debug('ログイン中のユーザーはアクセスできません');
    header("Location:user_page.php?page_id=${_SESSION['user_id']}&type=main");
    exit();
  }
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES);
}

function get_line_count($str){
  return substr_count($str,"\n") + 1;
}

//ログイン中のユーザーであるか確認
function is_myself($user){
  global $current_user;
  $result = $current_user['id'] === $user ? true : false;
  return $result;
}

function set_old_form_data($str){
  global ${'old'.$str};
  if(isset($_SESSION[$str])){
    ${'old'.$str} = $_SESSION[$str];
  }
  unset($_SESSION[$str]);
}

function get_form_data($str){
  global $current_user;
  global ${'old'.$str};
  //入力フォームにエラーがあれば送信したデータを表示
  //なければDBのデーターを表示
  if(isset(${'old'.$str}) && ${'old'.$str} !== $current_user[$str]){
    return ${'old'.$str};
  }else{
    return $current_user[$str];
  }
}

// フラッシュメッセージ用処理
function set_flash($type,$message){
  $_SESSION['flash']['type'] = "flash_${type}";
  $_SESSION['flash']['message'] = $message;
}

//セッション内容を1回だけ取得して破棄する
if( isset($_SESSION['flash']) ){
  $flash_messages = $_SESSION['flash']['message'];
  $flash_type = $_SESSION['flash']['type'];
}
unset($_SESSION['flash']);
