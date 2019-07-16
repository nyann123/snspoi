<?php
require_once("config.php");

//ログイン中はアクセスできないように
check_logged_in();

$now = new DateTime();
$now->setTimeZone(new DateTimeZone('Asia/Tokyo'));
$now->format('Y-m-d H:i:s');



$u_id = $_GET['u_id'];

$dbh = dbConnect();
$sql = 'SELECT *
        FROM provisional_users
        WHERE unique_id = :u_id';
$stmt = $dbh->prepare($sql);
$stmt->execute(array(':u_id' => $u_id));
$provisional_user = $stmt->fetch();

// 仮登録確認
if ($provisional_user){

  // 登録期限確認
  if($provisional_user['limit_time'] > $now->format('Y-m-d H:i:s')){
    $authentication = true;
  }else{
    set_flash("error",'有効期限切れです。最初からやり直してください。');
    header('Location:login_form.php');
    exit();
  }

}else{
  set_flash("error",'不正なアクセスです');
  header('Location:login_form.php');
  exit();
}

//エラー発生時の入力保持
set_old_form_data('name');

if(!empty($_POST)){
  require_once("signup_process.php");
}

$site_title = '新規登録';
$js_file = 'signup_second';
require_once('head.php');
 ?>

<body>
  <?php require_once('header.php') ?>
  <div class="form_container border_white">
    <h2 class="page_title">新規登録</h2>

    <?php if (isset($flash_messages)): ?>
      <?php foreach ((array)$flash_messages as $message): ?>
        <p class ="flash_message <?= $flash_type ?>"><?= $message?></p>
      <?php endforeach ?>
    <?php endif ?>


    <?php if (isset($authentication)): ?>
      <div class="form_inner">
        <form action="#" method="post">
          <span class="flash_cursor">｝</span>

          <label for="name">ユーザー名 <span>※最大８文字</span></label><br>
          <input id="name" type="text" name="name" value="<?php if (isset($oldname)) echo h($oldname); ?>">
          <span class="js_error_message"></span><br>

          <label for="password">パスワード <span>※半角英数６文字以上</span> </label><br>
          <input id="password" type="password" name="pass">
          <span class="js_error_message"></span><br>

          <button id="js_btn" class="btn blue" type="submit" disabled>登録</button>
        </form>
      </div>
    <?php endif; ?>

  </div>
<?php require_once('footer.php'); ?>
