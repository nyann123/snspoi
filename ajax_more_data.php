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
    $posts =  get_posts($page_id,'my_post',$offset_count);
    $type = 'post';
      break;

    case 'favorites':
    $posts = get_posts($page_id,'favorite',$offset_count);
    $type = 'post';
      break;

    case 'timeline':
    $posts = get_posts($current_user['id'],'timeline',$offset_count);
    $type = 'post';
      break;

    case 'follows':
    $follow_users = get_related_users($current_user['id'],'follows',$offset_count*2);
    $id_type ='followed';
    $type ='user';
      break;

    case 'followers':
    $follow_users = get_followers($current_user['id'],'followers',$offset_count*2);
    $id_type = 'follow';
    $type ='user';
      break;

  }

  $data = $posts ?? $follow_users;

  // 取得した投稿数
  $data_count = count($data);
  //取得したデータをHTMLに加工して返す
    ob_start();
    require($type.'_list.php');
    $data_html = ob_get_contents();
    ob_end_clean();

  echo json_encode(array('data_html' => $data_html, 'data_count' => $data_count));
}
debug('------------------------------');
