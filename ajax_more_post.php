<?php
require_once('config.php');

$current_user = get_user($_SESSION['user_id']);
$page_id = $_POST['page_id'];
$offset_count = $_POST['offset'];


if(isset($_POST['more_posts'])){
  debug('POST送信があります');
  debug('POST内容:'.print_r($_POST,true));


  $posts = get_more_posts($page_id,$offset_count);
  
  // 投稿が取得できなかったらfalseを返す
  if(!$posts){
    echo json_encode(false);
    exit();
  }

  //投稿をHTMLに加工して返す
  ob_start();
  require('post_list.php');
  $posts_html = ob_get_contents();
  ob_end_clean();

  echo json_encode($posts_html);
}
debug('------------------------------');
