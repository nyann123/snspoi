<?php foreach($posts as $post): ?>

    <div class="item_container post_container border_white">

      <!-- アイコン -->
      <div class="icon border_white">
        <a href="user_page.php?page_id=<?= $post['user_id'] ?>&type=main">
          <img src=<?= 'img/small'.$post['user_icon'] ?> alt="">
        </a>
      </div>

      <div class="post_data">
        <!-- ユーザーによって名前を色替え -->
        <?php if (is_myself($post['user_id'])): ?>
          <a href="user_page.php?page_id=<?= $post['user_id'] ?>&type=main"
            class="post_user_name myself"><?= h($post['name']) ?></a>
        <?php else: ?>
          <a href="user_page.php?page_id=<?= $post['user_id'] ?>&type=main"
            class="post_user_name other"><?= h($post['name']) ?></a>
        <?php endif; ?>

        <?php $time = new DateTime($post['created_at']) ?>
        <?php $post_date = $time->format('Y-m-d H:i') ?>
        <p class="post_date"><?= $post_date ?></p>
      </div>
      <p class ="post_content ellipsis"><?= nl2br(h($post['post_content']))?></p>

      <!-- 改行をカウントして１０行以上なら表示する -->
      <?php if (substr_count($post['post_content'],"\n") +1 > 10):?>
        <button class="show_all">続きを表示する</button>
      <?php endif ?>

      <!-- お気に入りボタン ahaxで処理-->
      <form class="favorite_count" action="#" method="post">
        <input type="hidden" name="post_id" value="<?= $post['id']?>">
        <button type="button" name="favorite" class="favorite_btn">

        <!-- 登録済みか判定してアイコンを変える -->
        <?php if (check_favolite_duplicate($current_user['id'],$post['id'])): ?>
          <i class="fas fa-star"></i>
        <?php else: ?>
          <i class="far fa-star"></i>
        <?php endif; ?>

        </button>
        <span class="post_count"><?= current(get_post_favorite_count($post['id'])) ?></span>
      </form>

      <!-- 投稿削除 -->
      <?php if (is_myself($post['user_id'])): ?>
        <!-- モーダルウィンドウを開く -->
        <button data-target="#modal<?= $post['id'] ?>" class="delete_btn" type="button"><i class="far fa-trash-alt"></i></button>
        <!-- モーダルウィンドウ -->
        <div class="modal" id="modal<?= $post['id'] ?>">
          <div class="delete_confirmation border_white">
            <p class="modal_title" >こちらの投稿を削除しますか？</p>
            <p class="post_content"><?= nl2br(h($post['post_content'])) ?></p>
            <form action="" method="post" class="btn_flex">
              <input type="hidden" name="post_id" value="<?= $post['id']?>">
              <input type="hidden" name="user_id" value="<?= $post['user_id']?>">
              <button class="btn red" type="submit" name="delete" value="delete">削除</button>
              <button class="btn blue modal_close" type="button">キャンセル</button>
            </form>
          </div>
        </div>

      <?php endif ?>

    </div>

<?php endforeach ?>
