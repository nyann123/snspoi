<?php foreach ($follow_users as $users): ?>

  <?php $user = get_user($users["${id_type}"."_id"]) ?>
  <div class="item_container user_container border_white flex">

    <!-- アイコン -->
    <div class="icon border_white">
      <a href="user_page.php?page_id=<?= $user['id'] ?>&type=main">
        <img src=<?= 'img/'.$user['user_icon_small'] ?> alt="">
      </a>
    </div>

    <div class="user_data">
      <a href="user_page.php?page_id=<?= $user['id'] ?>&type=main" class="user_name">
        <?= h($user['name']) ?>
      </a>

      <div class="user_count flex">
        <div class="count_label"><i class="far fa-comment-dots"></i></div>
        <span class="count_num"><?= current(get_user_count('post',$user['id'])) ?></span>
      </div>
    </div>


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

  </div>
<?php endforeach; ?>
