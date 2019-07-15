<?php
require_once("config.php");
require_once('vendor/autoload.php');


// ログイン中ならマイページへ
check_logged_in();

if(isset($_SESSION['send_to'])){
  $send_to = $_SESSION['send_to'];
  unset($_SESSION['send_to']);
}

//送信されていれば新規登録処理
if(!empty($_POST)){
  $email = $_POST['email'];

  valid_email($email);
  set_flash('error',$error_messages);

  if(empty($error_messages)){

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
         https://agile-wave-88047.herokuapp.com/signup_second.php?u_id=${unique_id} ");
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        $_SESSION['send_to'] = $email;
        header('Location:signup_first.php');
      }
    } catch (\Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
      header('Location:signup_first.php');
    }
  }
}

$site_title = '新規登録';
$js_file = 'signup_first';
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
    <!-- メール送信前に表示 -->
    <?php if (empty($send_to)): ?>
      <div class="form_inner">
        <form action="#" method="post">
          <span class="flash_cursor">｝</span>

          <label for="email">メールアドレス</label><br>
          <input id="email" autocomplete="false" type="text" name="email">
          <span class="js_error_message"></span><br>

          <button id="js_btn" class="btn blue" type="submit" disabled>メールを送信する</button>
          <a href="login_form.php" class="login link">>>ログインページへ</a>

        </form>
      </div>
    <!-- メール送信後に表示 -->
    <?php else: ?>
      <div class="send_to">
        <p><?= $send_to ?></p>
        <p>にメールを送信しました。<br>メールを確認して登録を完了してください。</p>
      </div>
      <a href="login_form.php" class="link">>>ログインページへ</a>
    <?php endif; ?>
  </div>
<?php require_once('footer.php'); ?>
