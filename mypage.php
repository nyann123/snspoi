<?php
require_once('config.php');

//ログインしているか確認
cheak_logged_in();

//sessionからユーザー情報を復元
try {
  $sql = "select * from users
          where id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':id' => $_SESSION['user_id']));
  $current_user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

//ユーザーの投稿を取得
try{
  $sql = "select posts.id,user_id,name,post_content,posts.created_at
          from users inner join posts on users.id = posts.user_id
          order by posts.created_at desc";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $user_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

//投稿
if(!empty($_POST['post_content'])){

  $post_content = $_POST['content'];
  $user_id = $user['id'];
  $now = new DateTime('', new DateTimeZone('Asia/Tokyo'));

  try {
    $sql = "insert into posts(user_id,post_content,created_at)
            value(:user_id,:post_content,:created_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_content' => $post_content , ':created_at' => $now->format('Y-m-d H:i:s')));

    $_SESSION['flash'] = '投稿しました';
    header('Location:mypage.php');
  } catch (\Exception $e) {
    echo $e->getMessage() ;
    $_SESSION['flash'] = 'error';
  }
}

//投稿削除
if(!empty($_POST['delete'])){
  $post_id = $_POST['post_id'];
  $sql = "delete
          from posts
          where id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':id' => $post_id));

  $_SESSION['flash'] = '削除しました';
  header('Location:mypage.php');
}

//お気に入り追加
if(!empty($_POST['like'])){
  $post_id = $_POST['post_id'];
  $sql = "insert into likes(user_id,post_id)
          value(:user_id,:post_id)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

  $_SESSION['flash'] = 'いいねしました';
  header('Location:mypage.php');
}
 ?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/mypage.css">
    <meta charset="utf-8">
    <title>MyPage</title>
  </head>
  <body>
    <header>
      <a href="login_form.php">home</a>
      <a href="logout_process.php">ログアウト</a>
    </header>
    <div class="container">

      <?php if (isset($flash_messages)): ?>
        <?php foreach ((array)$flash_messages as $message): ?>
          <p class ="php_message <?php echo $flash_type ?>"><?php echo $message?></p>
        <?php endforeach ?>
      <?php endif ?>

      <div class ="mypage">

        <div class="mypage_left">
          ようこそ
          <?php  echo $current_user['name']; ?>
          さん
          <p>id = <?php echo $current_user['id'] ?></p>
        </div>

        <div class="mypage_right">
          <form class ="post_form" action="#" method="post">
            <textarea name="content" rows="8" cols="80"></textarea><br>
            <input id="post_btn" type="submit" name="post_content" value="投稿">
          </form>

          <?php foreach($user_posts as $post): ?>
              <div class="posts_container">
                <div class="post_data">

                  <?php if ($current_user['id'] === $post['user_id']): ?>
                    <p class="post_user_name myself"><?php echo $post['name']; ?></p>
                  <?php else: ?>
                    <p class="post_user_name other"><?php echo $post['name']; ?></p>
                  <?php endif; ?>

                  <?php $time = new DateTime($post['created_at']) ?>
                  <?php $post_date = $time->format('Y-m-d H:i') ?>
                  <p class="post_date"><?php echo $post_date ?></p>
                </div>

                <p class ="post_content"><?php echo wordwrap($post['post_content'], 60, "<br>\n", true)?></p>

                <form class="" action="#" method="post">
                  <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                  <input type="submit" name="delete" value="削除" method="post">
                </form>
                <form class="" action="#" method="post">
                  <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                  <input type="submit" name="like" value="いいね" method="post">
                </form>
              </div>
          <?php endforeach ?>

        </div>
      </div>
    </div>
  </body>
</html>
