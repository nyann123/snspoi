<?php foreach($posts as $post): ?>
    <div class="item_container post_container border_white">

      <!-- アイコン -->
      <div class="icon border_white">
        <a href="user_page.php?page_id=<?= $post['user_id'] ?>&type=main">
          <img src=<?= 'img/'.$post['user_icon_small'] ?> alt="">
        </a>
      </div>

      <div class="post_data">
        <!-- ユーザーによって名前を色替え -->
        <?php if (is_myself($post['user_id'])): ?>
          <a href="user_page.php?page_id=<?= $post['user_id'] ?>&type=main"
            class="post_user_name myself"><?= $post['name'] ?></a>
        <?php else: ?>
          <a href="user_page.php?page_id=<?= $post['user_id'] ?>&type=main"
            class="post_user_name other"><?= $post['name'] ?></a>
        <?php endif; ?>

        <?php $time = new DateTime($post['created_at']) ?>
        <?php $post_date = $time->format('Y-m-d H:i') ?>
        <p class="post_date"><?= $post_date ?></p>
      </div>
      <p class ="post_content"><?= wordwrap($post['post_content'], 60, "<br>\n", true)?></p>

      <!-- お気に入りボタン -->
      <form class="" action="#" method="post">
        <input type="hidden" name="post_id" value="<?= $post['id']?>">
        <button type="button" name="favorite" class="favorite_btn">

        <!-- 登録済みか判定してアイコンを変える -->
        <?php if (check_favolite_duplicate($current_user['id'],$post['id'])): ?>
          <i class="fas fa-star"></i>
        <?php else: ?>
          <i class="far fa-star"></i>
        <?php endif; ?>

        </button>
        <span class="post_count"><?= current(get_post_count($post['id'])) ?></span>
      </form>

      <!-- 投稿削除ボタン -->
      <?php if (is_myself($post['user_id'])): ?>
        <form action="#" method="post">
          <input type="hidden" name="post_id" value="<?= $post['id']?>">
          <input type="hidden" name="user_id" value="<?= $post['user_id']?>">
          <button type="submit" name="delete" value="delete"><i class="far fa-trash-alt"></i></button>
        </form>
      <?php endif ?>

    </div>

<?php endforeach ?>
