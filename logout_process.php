<?php
session_start();

if (isset($_SESSION['user_id'])) {
  unset($_SESSION['user_id']);
  $_SESSION['flash'] = 'ログアウトしました';
  header('Location:login_form.php');

}else{
  $_SESSION['flash'] = 'ログインしてください';
  header('Location:login_form.php');
}
