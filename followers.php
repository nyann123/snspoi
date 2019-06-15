<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　フォロワー一覧 「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');
debug("ページID：${_GET['page_id']}");
$page_user = get_user($_GET['page_id']);

//ログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);

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
    <?php require_once('profile.php') ?>
    <div class="main_items border_white flex_wrap <?php if(!empty(get_followers($page_user['id']))) echo 'flex' ?>">

      <!-- データがなければ表示する -->
      <?php if (empty(get_followers($page_user['id']))): ?>
        <p>フォローされていません</p>
      <?php endif; ?>

      <?php foreach (get_followers($page_user['id']) as $followed_users): ?>
        <?php $follow_user= get_user($followed_users['follow_id']) ?>
        <div class="item_container user_container border_white flex">

          <a href="user_page.php?page_id=<?= $follow_user['id'] ?>" class="user_name">
            <?= $follow_user['name'] ?>
          </a>

          <!-- フォローボタン -->
          <!-- 自分にはフォローボタンを表示しない -->
          <?php if ($current_user['id'] !== $follow_user['id']): ?>
            <form action="#" method="post">
              <input type="hidden" name="follow_user_id" value="<?= $follow_user['id'] ?>">

              <!-- フォロー中か確認してボタンを変える -->
              <?php if (check_follow($current_user['id'],$follow_user['id'])): ?>
                <button class="follow_btn border_white btn following" type="button" name="follow">フォロー中</button>
              <?php else: ?>
                <button class="follow_btn border_white btn" type="button" name="follow">フォロー</button>
              <?php endif; ?>

            </form>
          <?php endif; ?>

          <div class="user_counts">
            <div class="user_count post flex">
                <div class="count_label"><i class="far fa-comment-dots"></i></div>
                <span class="count_num"><?= current(get_user_count('post',$follow_user['id'])) ?></span>
            </div>
            <div class="user_count favorite flex">
                <div class="count_label"><i class="far fa-star"></i></div>
                <span class="count_num"><?= current(get_user_count('favorite',$follow_user['id'])) ?></span>
            </div>
            <div class="user_count follow flex">
              <div class="count_label"><i class="fas fa-user"></i></div>
              <span class="count_num"><?= current(get_user_count('follow',$follow_user['id'])) ?></span>
            </div>
            <div class="user_count follower flex">
              <div class="count_label"><i class="fas fa-users"></i></div>
              <span class="count_num"><?= current(get_user_count('follower',$follow_user['id'])) ?></span>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php require_once('footer.php'); ?>
