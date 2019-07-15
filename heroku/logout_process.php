<?php
require_once("config.php");


require_once('auth.php');

//ログイン中ならログアウトさせる
if (isset($_SESSION['user_id'])) {
  session_destroy();
  session_start();
  set_flash('sucsess','ログアウトしました');
    
  header('Location:login_form.php');
}
