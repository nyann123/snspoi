<?php
require_once("config.php");

debug('「「「「「「「「「「「');
debug('「　ログアウトページ「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

//ログイン中ならログアウトさせる
if (isset($_SESSION['user_id'])) {
  session_destroy();
  $_SESSION = array();
  debug('ログアウト成功');

  header('Location:login_form.php');
}
debug('------------------------------');
