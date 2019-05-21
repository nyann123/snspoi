<?php
// ログ
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
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$error_messages = array();

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
            WHERE :id = posts.user_id
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

function get_favorite_post($page_id){
  debug('お気に入り投稿を取得します');
  try{
    $dbh = dbConnect();
    $sql = "SELECT  users.name,posts.id,posts.user_id,post_content,posts.created_at
            FROM users INNER JOIN favorite ON users.id = favorite.user_id
            INNER JOIN posts ON posts.id = favorite.post_id
            WHERE favorite.user_id = :id
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





function cheak_logged_in(){
  if (empty($_SESSION['user_id'])){
    $_SESSION['flash'] = "ログインしてください";
    $_SESSION['flash']['type'] = "error";
    $_SESSION['flash']['message'] = "ログインしてください";
    header('Location:login_form.php');
  }
}

error_reporting(E_ALL); //E_STRICTレベル以外のエラーを報告する
ini_set('display_errors','On'); //画面にエラーを表示させるか


// フラッシュメッセージ用処理
if( isset($_SESSION['flash']) ){
  $flash_messages = $_SESSION['flash']['message'];
  $flash_type = $_SESSION['flash']['type'];
}
unset($_SESSION['flash']);
