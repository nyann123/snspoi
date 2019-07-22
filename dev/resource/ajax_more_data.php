<?php
require_once('config.php');

if(isset($_POST)){
  debug('POST送信があります');
  debug('POST内容:'.print_r($_POST,true));

  if(isset($_SESSION['user_id'])){
    $current_user = get_user($_SESSION['user_id']);
  }
  $query = $_POST['query'];
  $page_type = $_POST['page_type'];
  $offset_count = $_POST['offset'];

  // ページに合わせた投稿、ユーザーを取得
  switch ($page_type) {
    case 'main':
    $posts =  get_posts($query,'my_post',$offset_count);
    $type = 'post';
      break;

    case 'favorites':
    $posts = get_posts($query,'favorite',$offset_count);
    $type = 'post';
      break;

    case 'timeline':
    $posts = get_posts($current_user['id'],'timeline',$offset_count);
    $type = 'post';
      break;

    case 'follows':
    $id_type ='follower';
    ${$id_type."user"} = get_users($query,'follows',$offset_count*2);
    $type ='user';
      break;

    case 'followers':
    $id_type = 'follow';
    ${$id_type."user"} = get_users($query,'followers',$offset_count*2);
    $type ='user';
      break;

    case 'search':
    $id_type = '';
    ${$id_type."user"} =  get_users($query,'search',$offset_count*2);
    $type ='user';
    break;

  }

  //取得した投稿もしくはユーザーを変数にいれる
  $data = $posts ?? ${$id_type."user"};

  // 取得した数
  $data_count = count($data);
  //取得したデータをHTMLに加工して返す
    ob_start();
    require($type.'_list.php');
    $data_html = ob_get_contents();
    ob_end_clean();

  echo json_encode(array('data_html' => $data_html, 'data_count' => $data_count));
}
debug('------------------------------');
