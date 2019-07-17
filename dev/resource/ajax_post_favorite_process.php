<?php
require_once('config.php');
require_once('auth.php');


if(isset($_POST)){
  debug('POST送信があります');
  debug('POST内容:'.print_r($_POST,true));

  $current_user = get_user($_SESSION['user_id']);
  $page_id = $_POST['page_id'];
  $post_id = $_POST['post_id'];

  $profile_user_id = $_POST['page_id'] ?: $current_user['id'];

  //既に登録されているか確認
  if(check_favolite_duplicate($current_user['id'],$post_id)){
    debug('既に登録されています');
    $action = '解除';
    $sql = "DELETE
            FROM favorite
            WHERE :user_id = user_id AND :post_id = post_id";
  }else{
    debug('登録されていません');
    $action = '登録';
    $sql = "INSERT INTO favorite(user_id,post_id)
            VALUES(:user_id,:post_id)";
  }
  try{
    $dbh = dbConnect();
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

    debug("お気に入り${action}成功");
    $return = array('profile_count' => current(get_user_count('favorite',$profile_user_id)),
                    'post_count' => current(get_post_favorite_count($post_id)));
    echo json_encode($return);

  } catch (\Exception $e) {
    debug("お気に入り${action}失敗");
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
    echo json_encode("error");
  }
}
debug('------------------------------');
