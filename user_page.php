<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　マイページ     「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');
debug("ページID：${_GET['page_id']}");
$page_user = get_user($_GET['page_id']);

//ログイン中のユーザー情報を取得
$current_user = get_user($_SESSION['user_id']);
//ユーザーの投稿を取得
$user_posts = get_posts($page_user['id']);

//フォロー機能
if(!empty($_POST['folo'])){
  require_once('follow_process.php');
}

//投稿機能
if(!empty($_POST['post'])){
  require_once('post_process.php');
}

//投稿削除機能
if(!empty($_POST['delete'])){
  require_once('post_delete_process.php');
}

debug('------------------------------');

 $site_title = $page_user['name'];
 $css_file_title = 'user_page';
 require_once('head.php');
  ?>

<body>
  <?php require_once('header.php'); ?>

  <h2 class="site_title"><?php echo $site_title.'のページ' ?></h2>
  <div class="container flex">
      <!-- プロフィール -->
      <?php require_once('profile.php') ?>

        <div class="main_items border_white">
        <!-- 自分のページのみ投稿フォームを表示 -->
        <?php if ($current_user['id'] === $page_user['id']): ?>
          <form class ="post_form border_white" action="#" method="post">
            <textarea class="text_area border_white" placeholder="投稿する" name="content"></textarea><br>
            <input id="post_btn" type="submit" name="post" value="投稿" disabled>
          </form>
        <?php endif; ?>

        <!-- データがなければ表示する -->
        <?php if (empty($user_posts)): ?>
          <p>投稿がありません</p>
        <?php endif; ?>

        <?php foreach($user_posts as $post): ?>
          <!-- <?php print_r($post) ?> -->
            <div class="item_container border_white">
              <div class="post_data">

                <!-- ユーザーによって名前を色替え -->
                <?php if ($current_user['id'] === $post['user_id']): ?>
                  <a href="user_page.php?page_id=<?php echo $post['user_id']?>"
                    class="post_user_name myself"><?php echo $post['name']; ?></a>
                <?php else: ?>
                  <a href="user_page.php?page_id=<?php echo $post['user_id']?>"
                    class="post_user_name other"><?php echo $post['name']; ?></a>
                <?php endif; ?>

                <?php $time = new DateTime($post['created_at']) ?>
                <?php $post_date = $time->format('Y-m-d H:i') ?>
                <p class="post_date"><?php echo $post_date ?></p>
              </div>
              <p class ="post_content"><?php echo wordwrap($post['post_content'], 60, "<br>\n", true)?></p>

              <!-- お気に入りボタン -->
              <form class="" action="#" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                <button type="button" name="favorite" class="favorite_btn">
                <!-- 登録済みか判定してアイコンを変える -->
                <?php if (check_favolite_duplicate($current_user['id'],$post['id'])): ?>
                  <i class="fas fa-star"></i>
                <?php else: ?>
                  <i class="far fa-star"></i>
                <?php endif; ?>
                </button>
                <span class="post_count"><?php echo current(get_post_count($post['id'])) ?></span>
              </form>


              <!-- 投稿削除ボタン -->
              <form class="" action="#" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
                <input type="hidden" name="user_id" value="<?php echo $post['user_id']?>">
                <button type="submit" name="delete" value="delete"><i class="far fa-trash-alt"></i></button>
              </form>

            </div>

        <?php endforeach ?>


      </div>
  </div>
<?php require_once('footer.php'); ?>
