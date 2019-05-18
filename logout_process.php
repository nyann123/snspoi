<?php
require_once("config.php");

if (isset($_SESSION['user_id'])) {
  unset($_SESSION['user_id']);
  // session_destroy();
  // session_start();
  $_SESSION['flash']['type'] = 'flash_sucsess';
  $_SESSION['flash']['message'] = 'ログアウトしました';

  header('Location:login_form.php');

}else{
  $_SESSION['flash']['type'] = 'flash_error';
  $_SESSION['flash']['message'] = 'ログインしてください';

  header('Location:login_form.php');
}
