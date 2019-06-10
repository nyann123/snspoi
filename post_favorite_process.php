<?php
require_once('config.php');
$current_user = get_user($_SESSION['user_id']);

if(!empty($_POST['favorite'])){
  debug('お気に入りのPOST送信があります');
  $post_id = $_POST['post_id'];

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

    if(query_result($stmt)){
      debug("お気に入り${action}成功");

      $return = array('profile_count' => current(get_user_count('favorite',$current_user['id'])),
                      'post_count' => current(get_post_count($post_id)));
      echo json_encode($return);

    }
  } catch (\Exception $e) {
    debug("お気に入り${action}失敗");
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
    echo json_encode("error");
  }
}
debug('------------------------------');
