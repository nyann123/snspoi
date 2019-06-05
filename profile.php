<div class="profile">

  <?php  echo $page_user['name']; ?>

  <p>id = <?php echo $page_user['id'] ?></p>
  <!-- 自分のページにはフォローボタンを表示しない -->
  <?php if ($current_user['id'] !== $page_user['id']): ?>
    <form class="" action="#" method="post">
      <input type="submit" name="folo" value="フォロー">
    </form>
  <?php endif; ?>

  <div class="profile_counts">
    <div class="count <?php if( basename($_SERVER['PHP_SELF']) === 'user_page.php') echo 'active' ?>">
      <a href="#">
        <div class="count_label">投稿数</div>
        <span class="count_num"><?php echo current(get_count('post',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="count ">
      <a href="#">
      <div class="count_label">フォロー</div>
      <span class="count_num"><?php echo current(get_count('follow',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="count">
      <a href="#">
      <div class="count_label">フォロワー</div>
      <span class="count_num"><?php echo current(get_count('follower',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="count">
      <a href="#">
      <div class="count_label">お気に入り</div>
      <span class="count_num"><?php echo current(get_count('favorite',$page_user['id'])) ?></span>
      </a>
    </div>
    <div class="count">
      <a href="#">
      <div class="count_label">test</div>
      <span class="count_num"></span>
      </a>
    </div>
  </div>
</div>
