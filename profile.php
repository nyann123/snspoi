<div class="profile border_white">
    <p class="user_name"><?php  echo $page_user['name']; ?></p>
    
    <!-- フォローボタン -->
    <!-- 自分のページにはフォローボタンを表示しない -->
    <?php if ($current_user['id'] !== $page_user['id']): ?>
      <form action="#" method="post">
        <input type="hidden" name="follow_user_id" value="<?php echo $page_user['id'] ?>">

        <!-- フォロー中か確認してボタンを変える -->
        <?php if (check_follow($current_user['id'],$page_user['id'])): ?>
          <button class="follow_btn border_white btn following" type="button" name="folo">フォロー中</button>
        <?php else: ?>
          <button class="follow_btn border_white btn" type="button" name="folo">フォロー</button>
        <?php endif; ?>

      </form>
    <?php endif; ?>

  <p>id = <?php echo $page_user['id'] ?></p>

  <div class="profile_counts">
    <div class="profile_count post <?php if( basename($_SERVER['PHP_SELF']) === 'user_page.php') echo 'active' ?>">
      <a href="user_page.php?page_id=<?php echo $page_user['id'] ?>">
        <div class="count_label">投稿数</div>
        <span class="count_num"><?php echo current(get_user_count('post',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follow <?php if( basename($_SERVER['PHP_SELF']) === 'follows.php') echo 'active' ?>">
      <a href="follows.php?page_id=<?php echo $page_user['id'] ?>">
        <div class="count_label">フォロー</div>
        <span class="count_num"><?php echo current(get_user_count('follow',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follower <?php if( basename($_SERVER['PHP_SELF']) === 'followers.php') echo 'active' ?>">
      <a href="followers.php?page_id=<?php echo $page_user['id'] ?>">
        <div class="count_label">フォロワー</div>
        <span class="count_num"><?php echo current(get_user_count('follower',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count favorite <?php if( basename($_SERVER['PHP_SELF']) === 'favorites.php') echo 'active' ?>">
      <a href="favorites.php?page_id=<?php echo $page_user['id'] ?>">
        <div class="count_label">お気に入り</div>
        <span class="count_num"><?php echo current(get_user_count('favorite',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count">
      <a href="#">
        <div class="count_label">test</div>
        <span class="count_num"></span>
      </a>
    </div>
  </div>
</div>
