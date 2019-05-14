<?php
require_once('config.php');

//アクセス制限
if (!isset($_SESSION['user_id'])){
  $_SESSION['flash'] = "ログインしてください";
  header('Location:login_form.php');
}

//sessionからユーザー情報を取得
try {
  $sql = "select * from users
          where id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':id' => $_SESSION['user_id']));
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

//ログイン中のユーザーの投稿を取得
try{
  $sql = "select post_content
          from users inner join posts on users.id = posts.user_id
          where :id = posts.user_id
          order by posts.created_at desc";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':id' => $user['id']));
  $user_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}

if(!empty($_POST)){

  $post_content = $_POST['content'];
  $user_id = $user['id'];

  try {
    $sql = "insert into posts(user_id,post_content,created_at) value(:user_id,:post_content,:created_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':user_id' => $user_id , ':post_content' => $post_content , ':created_at' => date('Y-m-d H:i:s')));

    $_SESSION['flash'] = '投稿しました';
    header('Location:mypage.php');
  } catch (\Exception $e) {
    echo $e->getMessage() ;
    $_SESSION['flash'] = 'error';
  }
}

 ?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <link rel="stylesheet" href="css/normalize.css">
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
      <h2 class="top_title">まいぺーじ</h2> <!-- いらないかも-->
      <div class ="mypage">

        <div class="mypage_left">
          ようこそ
          <?php  echo $user['name']; ?>
          さん
          <p>id = <?php echo $user['id'] ?></p>
        </div>
        
        <div class="mypage_right">
          <form class ="post_form" action="#" method="post">
            <textarea name="content" rows="8" cols="80"></textarea><br>
            <input id="post_btn" type="submit" name="" value="投稿">
          </form>

            <?php foreach($user_posts as $post): ?>
              <?php foreach ($post as $key => $value): ?>
                <div class="posts_container">
                  <div class="user_name">
                    <?php echo $user['name']; ?>
                  </div>
                  <p class="post"><?php echo wordwrap($value, 60, "<br>\n", true)  ?></p>
                </div>
              <?php endforeach; ?>
            <?php endforeach ?>

        </div>
      </div>
    </div>
  </body>
</html>
