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
// var_dump($current_user);


//投稿
if(!empty($_POST['post_btn'])){
  debug('投稿があります');
  $post_content = $_POST['content'];

  //投稿の長さチェック
  valid_post_length($post_content);

  if (empty($error_messages)){
    debug('バリデーションＯＫ');

    try {
      $dbh = dbConnect();
      $sql = "insert into posts(user_id,post_content,created_at)
              value(:user_id,:post_content,:created_at)";
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':user_id' => $current_user['id'] , ':post_content' => $post_content , ':created_at' => date('Y-m-d H:i:s')));

      set_flash('sucsess','投稿しました');
      debug('投稿成功');

      header("Location:mypage.php?page_id=${current_user['id']}");
      exit();

    } catch (\Exception $e) {
      echo $e->getMessage() ;
      $_SESSION['flash'] = 'error';
    }
  }
  set_flash('error',$error_messages);

  debug('投稿失敗');

  header("Location:mypage.php?page_id=${current_user['id']}");
}

//投稿削除
if(!empty($_POST['delete_btn'])){
  $dbh = dbConnect();
  $post_id = $_POST['post_id'];
  $sql = "delete
          from posts
          where id = :id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':id' => $post_id));

  set_flash('error','削除しました');
  header("Location:mypage.php?page_id=${page_id}");
}

//お気に入り追加
if(!empty($_POST['favorite_btn'])){
  $dbh = dbConnect();
  $post_id = $_POST['post_id'];
  $sql = "insert into favorite(user_id,post_id)
          value(:user_id,:post_id)";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

  set_flash('sucsess','お気に入りに登録しました');
  header("Location:mypage.php?page_id=${page_id}");
}

 $site_title = 'マイページ';
 $css_file_title = 'mypage';
 require_once('head.php');
  ?>

<body>
  <?php require_once('header.php'); ?>

  <!-- フラッシュメッセージ -->
  <?php if(isset($flash_messages)): ?>
    <p id ="js_show_msg" class="message_slide <?php echo $flash_type ?>">
      <?php echo $flash_messages ?>
    </p>
  <?php endif; ?>


  <div class="container">
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
            <textarea class="text_area" placeholder="投稿する" name="content"></textarea><br>
            <input id="post_btn" type="submit" name="post_btn" value="投稿" disabled>
          </form>
        <?php endif; ?>

        <!-- 投稿がなければ表示する -->
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
                <input type="submit" name="delete_btn" value="削除" method="post">
              </form>
              <form class="" action="#" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                <input type="submit" name="favorite_btn" value="お気に入り" method="post">
              </form>
            </div>

        <?php endforeach ?>


      </div>
    </div>
  </div>
<?php require_once('footer.php'); ?>
