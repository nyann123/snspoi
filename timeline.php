<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　タイムライン :未完成   「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

//ログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);
//全投稿を取得
$posts = get_all_posts();

//投稿
if(!empty($_POST['post'])){
  require_once('post_process.php');
}

//投稿削除
if(!empty($_POST['delete'])){
  require_once('post_delete_process.php');
}


debug('------------------------------');

 $site_title = 'タイムライン';
 $css_file_title = 'user_page';
 require_once('head.php');
  ?>

  <body>
    <?php require_once('header.php'); ?>

    <h2 class="site_title"><?= $site_title ?></h2>
    <div class="container flex">

        <!-- プロフィール -->
        <?php $profile_user = $current_user; ?>
        <?php require_once('profile.php') ?>

          <div class="main_items border_white">

          <!-- 投稿フォーム -->
            <form class ="post_form border_white" action="#" method="post">
              <textarea class="textarea border_white" placeholder="投稿する" name="content"></textarea><br>
              <input id="post_btn" type="submit" name="post" value="投稿" disabled>
            </form>

          <!-- データがなければ表示する -->
          <?php if (empty($posts)): ?>
            <p>投稿がありません</p>
          <?php endif; ?>

        <?php require_once('post_list.php') ?>


        </div>
    </div>
  <?php require_once('footer.php'); ?>
