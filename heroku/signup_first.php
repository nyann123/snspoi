<?php
require_once("config.php");
require_once('vendor/autoload.php');


debug('「「「「「「「「「「「');
debug('「　新規登録ページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

// ログイン中ならマイページへ
check_logged_in();

//エラー発生時の入力保持
set_old_form_data('name');
set_old_form_data('email');
set_old_form_data('pass');

//送信されていれば新規登録処理
if(!empty($_POST)){
  debug('POST送信があります。');
  $email = $_POST['email'];

  valid_email($email);
  set_flash('error',$error_messages);

  if(empty($error_messages)){
    debug('バリデーションOK');

  // ユニークid生成
  $unique_id = uniqid(rand());
  //有効期限(時)
  $limit_time = 2;

  $date = new DateTime();
  $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));
  $date->modify('+'.$limit_time.'hours');

    try {
      $dbh = dbConnect();
      //emailが存在していればINSERT、なければUPDATE
      $sql = 'INSERT INTO provisional_users(email,unique_id,limit_time)
              VALUES(:email,:unique_id,:limit_time)
              ON DUPLICATE KEY UPDATE
              email = :email, unique_id = :unique_id, limit_time = :limit_time';
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':email' => $email,
                           ':unique_id' => $unique_id,
                           ':limit_time' => $date->format('Y-m-d H:i:s')));

      // 成功したらメール送信処理
      if (query_result($stmt)) {

        $from = new SendGrid\Email(null, "test@example.com");
        $subject = "Hello World from the SendGrid PHP Library!";
        $to = new SendGrid\Email(null, $email);
        $content = new SendGrid\Content("text/plain",
         "Hello, Email!\n
         https://agile-wave-88047.herokuapp.com/test.php?u_id=${unique_id} ");
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);

      }
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
      header('Location:signup_first.php');
    }
  }
  // require_once("signup_process.php");
}

$site_title = '新規登録';
$js_file = 'signup';
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

    <div class="form_inner">
      <form action="#" method="post">
        <span class="flash_cursor">｝</span>

        <!-- <label for="name">ユーザー名 <span>※最大８文字</span></label><br>
        <input id="name" type="text" name="name" value="<?php if (isset($oldname)) echo h($oldname); ?>">
        <span class="js_error_message"></span><br> -->

        <label for="email">メールアドレス</label><br>
        <input id="email" autocomplete="false" type="text" name="email" value="<?php if (isset($oldemail)) echo h($oldemail) ?>">
        <span class="js_error_message"></span><br>

        <!-- <label for="password">パスワード <span>※半角英数６文字以上</span> </label><br>
        <input id="password" autocomplete="flase" type="password" name="pass" value="<?php if (isset($oldpass)) echo h($oldpass) ?>">
        <span class="js_error_message"></span><br> -->

        <button id="js_btn" class="btn blue" type="submit" disabled>登録</button>
        <a href="login_form.php" class="login">ログインページへ</a>

      </form>
    </div>
  </div>
<?php require_once('footer.php'); ?>
