<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　タイムライン :未完成   「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

//sessionからログインユーザー情報を復元
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
  $sql = "SELECT posts.id,user_id,name,post_content,posts.created_at
          FROM users INNER JOIN posts ON users.id = posts.user_id
          ORDER BY posts.created_at DESC";
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

    $_SESSION['flash']['type'] = "flash_sucsess";
    $_SESSION['flash']['message'] = '投稿しました';

    header("Location:mypage.php?page_id=${current_user['id']}");
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

  $_SESSION['flash']['type'] = 'flash_error';
  $_SESSION['flash']['message'] = '削除しました';
  header("Location:mypage.php");
}

//お気に入り追加
if(!empty($_POST['like'])){
  $post_id = $_POST['post_id'];
  $sql = "insert into favorite(user_id,post_id)
          value(:user_id,:post_id)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

  $_SESSION['flash']['type'] = 'flash_sucsess';
  $_SESSION['flash']['message'] = 'お気に入りに登録しました';
  header('Location:mypage.php');
}

$site_title = "たいむらいん";
$css_title = 'mypage';
require_once('head.php');
 ?>
  <body>
    <header>
      <a href="login_form.php">home</a>
      <a href="logout_process.php">ログアウト</a>
    </header>
    <div class="container">

      <?php if (isset($flash_messages)): ?>
        <?php foreach ((array)$flash_messages as $message): ?>
          <p class ="flash_message <?php echo $flash_type ?>"><?php echo $message?></p>
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

          <?php if (empty($user_posts)): ?>
            <p>投稿がありません</p>
          <?php endif; ?>

          <?php foreach($user_posts as $post): ?>
            <!-- <?php var_dump($post)?> -->
              <div class="posts_container">
                <div class="post_data">
                  <!-- ユーザーによって名前を色替え -->
                  <?php if ($current_user['id'] === $post['user_id']): ?>
                    <a href="mypage.php?page_id=<?php echo $post['user_id']?>"
                      class="post_user_name myself"><?php echo $post['name']; ?></a>
                  <?php else: ?>
                    <a href="mypage.php?page_id=<?php echo $post['user_id']?>"
                      class="post_user_name other"><?php echo $post['name']; ?></a>
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
                  <input type="submit" name="like" value="お気に入り" method="post">
                </form>
              </div>

          <?php endforeach ?>

        </div>
      </div>
    </div>
  </body>
</html>
