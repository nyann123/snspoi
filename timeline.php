<?php
require_once('config.php');

debug('「「「「「「「「「「「');
debug('「　タイムライン :未完成   「');
debug('「「「「「「「「「「「');
debugLogStart();

require_once('auth.php');

//sessionからログインユーザー情報を復元
$current_user = get_user($_SESSION['user_id']);
//全投稿を取得
$user_posts = get_all_posts();

//投稿
if(!empty($_POST['post'])){
  require_once('post_process.php');
}

//投稿削除
if(!empty($_POST['delete'])){
  require_once('post_delete_process.php');
}

//お気に入り追加
if(!empty($_POST['favorite'])){
  require_once('post_favorite_process.php');
}

debug('------------------------------');

 $site_title = 'タイムライン';
 $css_file_title = 'mypage';
 require_once('head.php');
  ?>

<body>
 <?php require_once('header.php'); ?>

 <!-- フラッシュメッセージ -->
 <?php if(isset($flash_messages)): ?>
   <p id ="js_show_msg" class="message_slide <?php echo $flash_type ?>">
     <?php echo $flash_messages ?>
   </p>
 <?php endif; ?>

 <h2 class="site_title"><?php echo $site_title ?></h2>
 <div class="container">
   <div class ="mypage">
     <div class="profile">
       ようこそ
       <?php  echo $current_user['name']; ?>
       さん
       <p>id = <?php echo $current_user['id'] ?></p>
     </div>

       <div class="mypage_right">
         <form class ="post_form" action="#" method="post">
           <textarea class="text_area" placeholder="投稿する" name="content"></textarea><br>
           <input id="post_btn" type="submit" name="post" value="投稿" disabled>
         </form>

       <!-- 投稿がなければ表示する -->
       <?php if (empty($user_posts)): ?>
         <p>投稿がありません</p>
       <?php endif; ?>

       <?php foreach($user_posts as $post): ?>
         <!-- <?php var_dump($post)?> -->
           <div class="posts_container">
             <div class="post_data">

               <!-- ユーザーによって名前を色替え -->
               <?php if ($current_user['id'] === $post['user_id']): ?>
                 <a href="mypage.php?page_id=<?php echo $post['user_id']?>"
                   class="post_user_name myself"><?php echo $post['name']; ?></a>
               <?php else: ?>
                 <a href="mypage.php?page_id=<?php echo $post['user_id']?>"
                   class="post_user_name other"><?php echo $post['name']; ?></a>
               <?php endif; ?>

               <?php $time = new DateTime($post['created_at']) ?>
               <?php $post_date = $time->format('Y-m-d H:i') ?>
               <p class="post_date"><?php echo $post_date ?></p>
             </div>
             <p class ="post_content"><?php echo wordwrap($post['post_content'], 60, "<br>\n", true)?></p>

             <form class="" action="#" method="post">
               <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
               <input type="hidden" name="user_id" value="<?php echo $post['user_id']?>">
               <button type="submit" name="delete" value="delete"><i class="far fa-trash-alt"></i></button>
             </form>


             <form class="" action="#" method="post">
               <input type="hidden" name="post_id" value="<?php echo $post['id']?>">
               <?php if (check_favolite_duplicate($current_user['id'],$post['id'])): ?>
                 <button type="submit" name="favorite" value="favorite"><i class="fas fa-star"></i></button>
               <?php else: ?>
                 <button type="submit" name="favorite" value="favorite"><i class="far fa-star"></i></button>
               <?php endif; ?>
             </form>
           </div>

       <?php endforeach ?>


     </div>
   </div>
 </div>
<?php require_once('footer.php'); ?>
