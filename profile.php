<div class="profile border_white">
    <p class="user_name"><?php  echo $page_user['name']; ?></p>
    <!-- 自分のページにはフォローボタンを表示しない -->
    <?php if ($current_user['id'] !== $page_user['id']): ?>
      <form action="#" method="post">
        <input class="follow_btn btn border_white" type="submit" name="folo" value="フォロー">
      </form>
    <?php endif; ?>

  <p>id = <?php echo $page_user['id'] ?></p>

  <div class="profile_counts">
    <div class="profile_count <?php if( basename($_SERVER['PHP_SELF']) === 'user_page.php') echo 'active' ?>">
      <a href="user_page.php?page_id=<?php echo $page_user['id'] ?>">
        <div class="count_label">投稿数</div>
        <span class="count_num"><?php echo current(get_count('post',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count <?php if( basename($_SERVER['PHP_SELF']) === 'follows.php') echo 'active' ?>">
      <a href="follows.php?page_id=<?php echo $page_user['id'] ?>">
      <div class="count_label">フォロー</div>
      <span class="count_num"><?php echo current(get_count('follow',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count <?php if( basename($_SERVER['PHP_SELF']) === 'followers.php') echo 'active' ?>">
      <a href="followers.php?page_id=<?php echo $page_user['id'] ?>">
      <div class="count_label">フォロワー</div>
      <span class="count_num"><?php echo current(get_count('follower',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count">
      <a href="#">
      <div class="count_label">お気に入り</div>
      <span class="count_num"><?php echo current(get_count('favorite',$page_user['id'])) ?></span>
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
