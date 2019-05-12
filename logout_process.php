<?php
session_start();

if (isset($_SESSION['user'])) {
  $_SESSION['user'] = array();
  $_SESSION['flash'] = 'ログアウトしました';
  header('Location:login_form.php');

}else{
  $_SESSION['flash'] = 'ログインしてください';
  header('Location:login_form.php');
}
