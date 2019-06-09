<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　フォロー一覧   「');
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

 $site_title = $page_user['name'];
 $css_file_title = 'user_page';
 require_once('head.php');
  ?>

<body>
  <?php require_once('header.php'); ?>

  <h2 class="site_title"><?php echo $site_title.'のページ' ?></h2>
  <div class="container flex">
    <!-- プロフィール -->
    <?php require_once('profile.php') ?>
    <div class="main_items border_white flex_wrap <?php if(!empty(get_follows($page_user['id']))) echo 'flex' ?>">

      <!-- データがなければ表示する -->
      <?php if (empty(get_follows($page_user['id']))): ?>
        <p>フォローしていません</p>
      <?php endif; ?>

      <?php foreach (get_follows($page_user['id']) as $follow_users): ?>
        <?php $followed_user= get_user($follow_users['followed_id']) ?>
        <div class="item_container user_container border_white flex">

          <a href="user_page.php?page_id=<?php echo $followed_user['id'] ?>" class="user_name">
            <p class ="user_name"><?php echo $followed_user['name'] ?></p>
          </a>

          <form action="#" method="post">
            <input type="hidden" name="follow_user_id" value="<?php echo $followed_user['id'] ?>">
            <input class="follow_btn border_white btn" type="submit" name="folo" value="フォロー">
          </form>

          <div class="user_counts">
            <div class="user_count flex">
                <div class="count_label"><i class="far fa-comment-dots"></i></div>
                <span class="count_num"><?php echo current(get_user_count('post',$followed_user['id'])) ?></span>
            </div>
            <div class="user_count flex">
                <div class="count_label"><i class="far fa-star"></i></div>
                <span class="count_num"><?php echo current(get_user_count('favorite',$followed_user['id'])) ?></span>
            </div>
            <div class="user_count flex">
              <div class="count_label"><i class="fas fa-user"></i></div>
              <span class="count_num"><?php echo current(get_user_count('follow',$followed_user['id'])) ?></span>
            </div>
            <div class="user_count flex">
              <div class="count_label"><i class="fas fa-users"></i></div>
              <span class="count_num"><?php echo current(get_user_count('follower',$followed_user['id'])) ?></span>
            </div>
          </div>

        </div>
      <?php endforeach; ?>

    </div>
  </div>
<?php require_once('footer.php'); ?>
