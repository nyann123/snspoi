<?php

require('config.php');

debug('「「「「「「「「「「「「「「');
debug('「　プロフィール編集ページ「');
debug('「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

// DBからユーザーデータを取得
$user = get_user($_SESSION['user_id']);
debug('取得したユーザー情報：'.print_r($user,true));

// post送信されていた場合
if(!empty($_POST)){
  debug('POST送信があります。');
  debug('POST情報：'.print_r($_POST,true));

  $name = $_POST['username'];
  $email = $_POST['email'];
  $user_id = $user['id'];

  //DBの情報と入力情報が異なる場合にバリデーションを行う
  if($user['name'] !== $name){
    valid_name($name);
  }
  if($user['email'] !== $email){
    valid_email($email);
  }

  //メッセージをsessionに格納（エラーが発生したら定数で上書きされる）
  set_flash('error',$error_messages);

  if(empty($error_messages)){
    debug('バリデーションOKです。');

    try {
      $dbh = dbConnect();
      $sql = 'UPDATE users  SET name = :name, email = :email WHERE id = :id';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':name' => $name , ':email' => $email, ':id' => $user['id']));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // クエリ成功の場合
      if($stmt){
        debug('クエリ成功しました');
        set_flash('sucsess','プロフィールの編集が完了しました');
        header("Location:mypage.php?page_id=${user_id}");
        exit();
      }else{
        debug('クエリ失敗しました。');
        set_flash('error',ERR_MSG1);
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
    }
  }
  debug('プロフィール編集失敗');
  debug(print_r($_SESSION['flash'],true));

  header('Location:prof_edit.php');
}

$site_title = 'プロフィール編集';
$css_file_title = $js_file_title =  'prof_edit';
require_once('head.php');
?>

<body>
<?php require('header.php'); ?>

<div class="prof_edit_form">
  <h2>プロフィール編集</h2>

  <?php if (isset($flash_messages)): ?>
    <?php foreach ((array)$flash_messages as $message): ?>
      <p class ="flash_message <?php echo $flash_type ?>"><?php echo $message?></p>
    <?php endforeach ?>
  <?php endif ?>

  <div class="form_inner">
    <form action="" method="post">
      <label for="name">名前</label><br>
      <input id="name" type="text" name="username" value="<?php echo get_form_data('name'); ?>">
      <span class="js_error_message"></span><br>

      <label for="email">Email</label><br>
      <input id="email" type="email" name="email" value="<?php echo get_form_data('email'); ?>">
      <span class="js_error_message"></span><br>

      <input id="btn" type="submit"  value="変更する" disabled>
    </form>
  </div>
  </div>

<?php require_once('footer.php'); ?>
