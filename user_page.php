<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　マイページ     「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');
debug("ページID：${_GET['page_id']}");
debug("ページID：${_GET['type']}");

$page_user = get_user($_GET['page_id']);
$type = $_GET['type'];

//ログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);
debug(print_r($current_user,true));


// getパラメータに合わせて必要なデータを用意する
switch ($type) {
  case 'main':
    $posts = get_posts($page_user['id']);
  break;

  case 'favorites':
    $posts = get_favorite_posts($page_user['id']);
  break;

  case 'follows':
    $follow_users = get_follows($page_user['id']);
    $id_type = 'followed';
  break;

  case 'followers':
    $follow_users = get_followers($page_user['id']);
    $id_type = 'follow';
  break;
}

//投稿機能
if(!empty($_POST['post'])){
  require_once('post_process.php');
}

//投稿削除機能
if(!empty($_POST['delete'])){
  require_once('post_delete_process.php');
}

debug($type);
debug('------------------------------');

 $site_title = $page_user['name'];
 $css_file_title = 'user_page';
 require_once('head.php');
  ?>

<body>
  <?php require_once('header.php'); ?>

  <h2 class="site_title"><?= $site_title.'のページ' ?></h2>
  <div class="container flex">

      <!-- プロフィール -->
      <?php $profile_user = $page_user; ?>
      <?php require_once('profile.php') ?>

        <div class="main_items border_white">

        <!-- 自分のページのみ投稿フォームを表示 -->
        <?php if (is_myself($page_user['id']) && $type === 'main'): ?>
          <form class ="post_form border_white" action="#" method="post">
            <textarea class="text_area border_white" placeholder="投稿する" name="content"></textarea><br>
            <input id="post_btn" type="submit" name="post" value="投稿" disabled>
          </form>
        <?php endif; ?>

        <!-- それぞれデータがなければ表示する -->
        <?php if (empty($posts) && $type === 'main'): ?>
          <p>投稿がありません</p>
        <?php endif; ?>

        <?php if (empty($posts) && $type === 'favorites'): ?>
          <p>お気に入りが登録されていません</p>
        <?php endif; ?>

        <?php if (empty(get_follows($page_user['id'])) && $type === 'follows'): ?>
          <p>ユーザーがいません</p>
        <?php endif; ?>

        <?php if (empty(get_followers($page_user['id'])) && $type === 'followers'): ?>
          <p>ユーザーがいません</p>
        <?php endif; ?>

        <!-- getパラメータに合わせたページを表示 -->
        <?php if($type === 'main' || $type === 'favorites') require_once('post_list.php') ?>
        <?php if($type === 'follows' || $type === 'followers') require_once('user_list.php') ?>

      </div>
  </div>
<?php require_once('footer.php'); ?>
