<?php
require_once('config.php');

if(isset($_POST['more_posts'])){
  debug('POST送信があります');
  debug('POST内容:'.print_r($_POST,true));

  $current_user = get_user($_SESSION['user_id']);
  $page_id = $_POST['page_id'];
  $page_type = $_POST['page_type'];
  $offset_count = $_POST['offset'];


  switch ($page_type) {
    case 'main':
    $posts = get_posts($page_id,'my_post',$offset_count);
      break;
    case 'favorites':
    $posts = get_posts($page_id,'favorite',$offset_count);
      break;
    default:
      // code...
      break;
  }
  $posts_count = count($posts);
  //投稿をHTMLに加工して返す
  ob_start();
  require('post_list.php');
  $posts_html = ob_get_contents();
  ob_end_clean();

  echo json_encode(array('posts_html' => $posts_html, 'posts_count' => $posts_count));
}
debug('------------------------------');
