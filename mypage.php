<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　マイページ     「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');
$page_id = $_GET['page_id'];

//sessionからログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);
//ユーザーの投稿を取得
$user_posts = get_post($page_id);
// お気に入り登録した投稿を取得
$favorite_posts = get_favorite_post($page_id);
// var_dump($user_posts);


//投稿
if(!empty($_POST['post_content'])){

  $post_content = $_POST['content'];
  $user_id = $user['id'];
  $now = new DateTime('', new DateTimeZone('Asia/Tokyo'));

  try {
    $dbh = dbConnect();
    $sql = "insert into posts(user_id,post_content,created_at)
            value(:user_id,:post_content,:created_at)";
    $stmt = $dbh->prepare($sql);
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
  $dbh = dbConnect();
  $post_id = $_POST['post_id'];
  $sql = "insert into favorite(user_id,post_id)
          value(:user_id,:post_id)";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

  $_SESSION['flash']['type'] = 'flash_sucsess';
  $_SESSION['flash']['message'] = 'お気に入りに登録しました';
  header("Location:mypage.php?page_id=${page_id}");
}

 $site_title = 'マイページ';
 $css_title = 'mypage';
 require_once('head.php');
  ?>

  <body>
    <?php require_once('header.php'); ?>
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

            <!-- 自分のページのみ投稿フォームを表示 -->
          <?php if ($current_user['id'] === $_GET['page_id']): ?>
            <form class ="post_form" action="#" method="post">
              <textarea name="content" rows="8" cols="80"></textarea><br>
              <input id="post_btn" type="submit" name="post_content" value="投稿">
            </form>
          <?php endif; ?>

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
