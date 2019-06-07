<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　マイページ     「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');
debug("ページID：${_GET['page_id']}");
$page_user = get_user($_GET['page_id']);

//ログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);
// // お気に入り登録した投稿を取得
$user_posts = get_favorite_posts($page_user['id']);

//フォロー機能
if(!empty($_POST['folo'])){
  require_once('follow_process.php');
}

//お気に入り追加機能
if(!empty($_POST['favorite'])){
  require_once('post_favorite_process.php');
}

debug('------------------------------');

 $site_title = 'マイページ';
 $css_file_title = 'user_page';
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

  <h2 class="site_title"><?php echo $site_title ?></h2>
  <div class="container flex">
      <!-- プロフィール -->
      <?php require_once('profile.php') ?>

        <div class="main_items border_white">

        <!-- データがなければ表示する -->
        <?php if (empty($user_posts)): ?>
          <p>お気に入りが登録されていません</p>
        <?php endif; ?>

        <?php foreach($user_posts as $post): ?>
          <!-- <?php print_r($post) ?> -->
            <div class="item_container border_white">
              <div class="post_data">

                <!-- ユーザーによって名前を色替え -->
                <?php if ($current_user['id'] === $post['user_id']): ?>
                  <a href="user_page.php?page_id=<?php echo $post['user_id']?>"
                    class="post_user_name myself"><?php echo $post['name']; ?></a>
                <?php else: ?>
                  <a href="user_page.php?page_id=<?php echo $post['user_id']?>"
                    class="post_user_name other"><?php echo $post['name']; ?></a>
                <?php endif; ?>

                <?php $time = new DateTime($post['created_at']) ?>
                <?php $post_date = $time->format('Y-m-d H:i') ?>
                <p class="post_date"><?php echo $post_date ?></p>
              </div>
              <p class ="post_content"><?php echo wordwrap($post['post_content'], 60, "<br>\n", true)?></p>

              <form class="" action="#" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                <input type="hidden" name="user_id" value="<?php echo $post['user_id']?>">
                <button type="submit" name="delete" value="delete"><i class="far fa-trash-alt"></i></button>
              </form>

              <form class="" action="#" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                <?php if (check_favolite_duplicate($current_user['id'],$post['id'])): ?>
                  <button type="submit" name="favorite" value="favorite"><i class="fas fa-star"></i></button>
                <?php else: ?>
                  <button type="submit" name="favorite" value="favorite"><i class="far fa-star"></i></button>
                <?php endif; ?>
              </form>


            </div>

        <?php endforeach ?>


      </div>
  </div>
<?php require_once('footer.php'); ?>
