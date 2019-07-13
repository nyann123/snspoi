<?php foreach (${$id_type."user"} as $users): ?>

  <?php $user = !empty($id_type) ? get_user($users["${id_type}"."_id"]) : get_user($users['id']);?>

  <div class="item_container user_container border_white flex">

    <div class="user_data">
      <!-- アイコン -->
      <div class="icon border_white">
        <a href="user_page.php?page_id=<?= $user['id'] ?>&type=main">
          <img class="icon_small" src=<?= $user['user_icon'] ?> alt="">
        </a>
      </div>

      <div class="wrapper">
        <a href="user_page.php?page_id=<?= $user['id'] ?>&type=main" class="user_name">
          <?= h($user['name']) ?>
        </a>

        <div class="user_count flex">
          <div class="count_label"><i class="far fa-comment-dots"></i></div>
          <span class="count_num"><?= current(get_user_count('post',$user['id'])) ?></span>
        </div>
      </div>

    </div>
    <!-- ログイン中のみ -->
    <?php if (!is_guest()): ?>
      <!-- フォローボタン ajaxで処理-->
      <!-- 自分にはフォローボタンを表示しない -->
      <?php if ($current_user['id'] !== $user['id']): ?>
        <form action="#" method="post">
          <input type="hidden" class="profile_user_id" value="<?= $profile_user['id'] ?>">
          <input type="hidden" name="follow_user_id" value="<?= $user['id'] ?>">

          <!-- フォロー中か確認してボタンを変える -->
          <?php if (check_follow($current_user['id'],$user['id'])): ?>
            <button class="follow_btn border_white btn following" type="button" name="follow">フォロー中</button>
          <?php else: ?>
            <button class="follow_btn border_white btn" type="button" name="follow">フォロー</button>
          <?php endif; ?>
        </form>
      <?php endif; ?>
    <?php endif; ?>
    <p class="profile_comment"><?= h($user['profile_comment']) ?></p>
  </div>
<?php endforeach; ?>
