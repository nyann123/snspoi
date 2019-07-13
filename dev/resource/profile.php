<div class="profile border_white">
  <div class="flex">

    <div class="profile_icon border_white">
      <!-- ログイン中のみ -->
      <?php if (!is_guest()): ?>
        <!-- 自分のページでのみアイコン編集できるように -->
        <?php if (is_myself($profile_user['id'])): ?>
          <div class="edit_icon">
            <p>アイコンを変更する</p>
          </div>
         <?php endif ?>
      <?php endif; ?>
      <img src="<?= $profile_user['user_icon'] ?>" alt="">
      <form id="icon_form">
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
        <input class="icon_upload" type="file" name="icon">
      </form>
    </div>

    <p class="user_name"><?= h($profile_user['name']); ?></p>
  </div>

  <!-- ログイン中のみ -->
  <?php if (!is_guest()): ?>
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
    <?php else: ?>
      <button class="btn edit_btn border_white btn" type="button" name="follow">プロフィール編集</button>
      <div class="btn_flex">
        <button class="btn blue profile_save" type="button" data-user_id="<?= $profile_user['id'] ?>">編集完了</button>
        <button class="btn red modal_close" type="button">キャンセル</button>
      </div>

    <?php endif; ?>
  <?php endif; ?>

    <p class="profile_comment"><?= h($profile_user['profile_comment']) ?></p>

  <div class="profile_counts">
    <div class="profile_count post <?php if( $page_type === 'main') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=main">
        <div class="count_label">投稿数</div>
        <span class="count_num"><?= current(get_user_count('post',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count favorite <?php if( $page_type === 'favorites') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=favorites">
        <div class="count_label">お気に入り</div>
        <span class="count_num"><?= current(get_user_count('favorite',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follow <?php if( $page_type === 'follows') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=follows">
        <div class="count_label">フォロー</div>
        <span class="count_num"><?= current(get_user_count('follow',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follower <?php if( $page_type === 'followers') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=followers">
        <div class="count_label">フォロワー</div>
        <span class="count_num"><?= current(get_user_count('follower',$profile_user['id'])) ?></span>
      </a>
    </div>
  </div>
</div>


<!-- =========================== -->
　    <!-- タブレット以下 -->
<!-- =========================== -->

<div class="slide_prof">
  <div class="flex">

    <div class="profile_icon border_white">
      <!-- ログイン中のみ -->
      <?php if (!is_guest()): ?>
        <!-- 自分のページでのみアイコン編集できるように -->
        <?php if (is_myself($profile_user['id'])): ?>
          <div class="edit_icon">
            <p>アイコンを変更する</p>
          </div>
         <?php endif ?>
      <?php endif; ?>
      <img src="<?= $profile_user['user_icon'] ?>" alt="">
    </div>

    <p class="user_name"><?= h($profile_user['name']); ?></p>
  </div>

    <!-- ログイン中のみ -->
    <?php if (!is_guest()): ?>
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
      <?php else: ?>
        <button class="btn edit_btn border_white btn" type="button" name="follow">プロフィール編集</button>
        <div class="btn_flex">
          <button class="btn blue profile_save" type="button" data-user_id="<?= $profile_user['id'] ?>">編集完了</button>
          <button class="btn red end_edit" type="button">キャンセル</button>
        </div>

      <?php endif; ?>
    <?php endif ?>

    <p class="profile_comment"><?= h($profile_user['profile_comment']) ?></p>

  <div class="profile_counts">
    <div class="profile_count post <?php if( $page_type === 'main') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=main">
        <div class="count_label">投稿数</div>
        <span class="count_num"><?= current(get_user_count('post',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count favorite <?php if( $page_type === 'favorites') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=favorites">
        <div class="count_label">お気に入り</div>
        <span class="count_num"><?= current(get_user_count('favorite',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follow <?php if( $page_type === 'follows') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=follows">
        <div class="count_label">フォロー</div>
        <span class="count_num"><?= current(get_user_count('follow',$profile_user['id'])) ?></span>
      </a>
    </div>
    <div class="profile_count follower <?php if( $page_type === 'followers') echo 'active' ?>">
      <a href="user_page.php?page_id=<?= $profile_user['id'] ?>&type=followers">
        <div class="count_label">フォロワー</div>
        <span class="count_num"><?= current(get_user_count('follower',$profile_user['id'])) ?></span>
      </a>
    </div>
  </div>
</div>
