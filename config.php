<?php
session_start();
//フラッシュメッセージ用処理
if( isset($_SESSION['flash']) ){
  $flash_messages = $_SESSION['flash'];
}
unset($_SESSION['flash']);

// 保留
// function flash_message($flash){
//   if( isset($_SESSION[$flash]) ){
//     ${'old'.$flash} = $_SESSION[$flash];
//   }
//   unset($_SESSION[$flash]);
// }

error_reporting(E_ALL); //E_STRICTレベル以外のエラーを報告する
ini_set('display_errors','On'); //画面にエラーを表示させるか

define('DSN', 'mysql:host=localhost;dbname=hogetest');
define('DB_USER', 'hoge');
define('DB_PASS', 'hoge');

//dbへの接続準備
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}
