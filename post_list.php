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

      <!-- お気に入りボタン ahaxで処理-->
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

      <!-- 投稿削除 -->
      <?php if (is_myself($post['user_id'])): ?>
        <!-- モーダルウィンドウを開く -->
        <button data-target="#modal<?= $post['id'] ?>" class="delete_btn" type="button"><i class="far fa-trash-alt"></i></button>
        <!-- モーダルウィンドウ -->
        <div class="modal" id="modal<?= $post['id'] ?>">
          <div class="overlay modal_close"></div>
          <div class="delete_confirmation border_white">
            <p>投稿を削除しますか？</p>
            <p><?= wordwrap($post['post_content'], 45, "<br>\n", true) ?></p>
            <form id="test" action="" method="post" class="btn_flex">
              <input type="hidden" name="post_id" value="<?= $post['id']?>">
              <input type="hidden" name="user_id" value="<?= $post['user_id']?>">
              <button class="btn blue hoge" type="submit" name="delete" value="delete">削除</button>
              <button class="btn red modal_close" type="button">キャンセル</button>
            </form>
          </div>
        </div>

      <?php endif ?>

    </div>

<?php endforeach ?>
