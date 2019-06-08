<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　フォロワーページ 「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');
debug("ページID：${_GET['page_id']}");
$page_user = get_user($_GET['page_id']);

//ログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);

//フォロー機能
if(!empty($_POST['folo'])){
  require_once('follow_process.php');
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
    <div class="main_items border_white flex_wrap <?php if(!empty(get_followers($page_user['id']))) echo 'flex' ?>">

      <!-- データがなければ表示する -->
      <?php if (empty(get_followers($page_user['id']))): ?>
        <p>フォローされていません</p>
      <?php endif; ?>

      <?php foreach (get_followers($page_user['id']) as $followed_users): ?>
        <?php $follow_user= get_user($followed_users['follow_id']) ?>
        <div class="item_container user_container border_white flex">

          <a href="user_page.php?page_id=<?php echo $follow_user['id'] ?>" class="user_name">
            <p class ="user_name"><?php echo $follow_user['name'] ?></p>
          </a>
          <form action="#" method="post">
            <input class="follow_btn border_white btn" type="submit" name="folo" value="フォロー">
          </form>

          <div class="user_counts">
            <div class="user_count flex">
                <div class="count_label"><i class="far fa-comment-dots"></i></div>
                <span class="count_num"><?php echo current(get_user_count('post',$follow_user['id'])) ?></span>
            </div>
            <div class="user_count flex">
                <div class="count_label"><i class="far fa-star"></i></div>
                <span class="count_num"><?php echo current(get_user_count('favorite',$follow_user['id'])) ?></span>
            </div>
            <div class="user_count flex">
              <div class="count_label"><i class="fas fa-user"></i></div>
              <span class="count_num"><?php echo current(get_user_count('follow',$follow_user['id'])) ?></span>
            </div>
            <div class="user_count flex">
              <div class="count_label"><i class="fas fa-users"></i></div>
              <span class="count_num"><?php echo current(get_user_count('follower',$follow_user['id'])) ?></span>
            </div>
          </div>

        </div>
      <?php endforeach; ?>

    </div>
  </div>
<?php require_once('footer.php'); ?>