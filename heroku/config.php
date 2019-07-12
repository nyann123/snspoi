<?php

//================================
//  ！！！本番環境設定！！！
//================================

// db接続情報の読み込み
require_once('db_connect.php');
require_once('functions.php');

error_reporting(E_ALL); //E_STRICTレベル以外のエラーを報告する
ini_set('display_errors','on'); //画面にエラーを表示させるか

//================================
//ログを取るか
//================================
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php://stderr');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = false;
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

//================================
// グローバル変数
//================================
//エラーメッセージ格納用の配列
$error_messages = array();

//================================
// フラッシュメッセージ
//================================

//セッション内容を1回だけ取得して破棄する
if( isset($_SESSION['flash']) ){
  $flash_messages = $_SESSION['flash']['message'];
  $flash_type = $_SESSION['flash']['type'];
}
unset($_SESSION['flash']);
