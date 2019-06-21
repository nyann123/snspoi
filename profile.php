<div class="profile border_white">
  <div class="flex <?php if (is_myself($profile_user['id'])) echo 'space' ?>">

    <div class="profile_icon border_white">
      <!-- 自分のページでのみアイコン編集できるように -->
    <?php if (is_myself($profile_user['id'])): ?>
      <div class="edit_icon">
        <p>アイコンを変更する</p>
      </div>
     <?php endif ?>
      <img src="<?= "img/".$profile_user['user_icon'] ?>" alt="">
    </div>

    <!-- 自分のページでのみアイコン編集できるように -->
    <?php if (is_myself($profile_user['id'])): ?>
      <ul class="edit_icon_menu">
        <li>
           <form id="icon_form">
             <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
             <input class="icon_upload" type="file" name="icon">
             <button type="button" class = "icon_upload_btn">アップロード</button>
           </form>
         </li>
        <li>
          <button type="button" class="icon_save" data-user_id="<?= $current_user['id'] ?>" disabled>保存</button>
        </li>
      </ul>
    <?php endif ?>

    <p class="user_name"><?= h($profile_user['name']); ?></p>
  </div>

    <!-- フォローボタン ajaxで処理-->
    <!-- 自分のページにはフォローボタンを表示しない -->
    <?php if ($current_user['id'] !== $profile_user['id']): ?>
      <form class="follow_btn_form" action="#" method="post">
        <input type="hidden" name="follow_user_id" value="<?= $profile_user['id'] ?>">

        <!-- フォロー中か確認してボタンを変える -->
        <?php if (check_follow($current_user['id'],$profile_user['id'])): ?>
          <button class="follow_btn border_white btn following" type="button" name="follow">フォロー中</button>
        <?php else: ?>
          <button class="follow_btn border_white btn" type="button" name="follow">フォロー</button>
        <?php endif; ?>

      </form>
    <?php endif; ?>

  <div class="profile_counts">
    <div class="profile_count post <?php if( $type === 'main') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=main">
        <div class="count_label">投稿数</div>
        <span class="count_num"><?= current(get_user_count('post',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follow <?php if( $type === 'follows') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=follows">
        <div class="count_label">フォロー</div>
        <span class="count_num"><?= current(get_user_count('follow',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follower <?php if( $type === 'followers') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=followers">
        <div class="count_label">フォロワー</div>
        <span class="count_num"><?= current(get_user_count('follower',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count favorite <?php if( $type === 'favorites') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=favorites">
        <div class="count_label">お気に入り</div>
        <span class="count_num"><?= current(get_user_count('favorite',$profile_user['id'])) ?></span>
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
