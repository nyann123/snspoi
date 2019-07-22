<?php
require_once('config.php');
require_once('auth.php');

if(isset($_POST)){
  debugLogStart();
  debug('POST送信があります');
  debug('POST内容:'.print_r($_POST,true));

  $current_user = get_user($_SESSION['user_id']);

  $user_id = $_POST['user_id'];
  $profile_user_id = $_POST['profile_user_id'] ?? $user_id;

  // 自分をフォローできないように
  if ( !is_myself($user_id)){
    // すでに登録されているか確認して登録、削除のSQL切り替え
    if(check_follow($current_user['id'],$user_id)){
      $action = '解除';
      $flash_type = 'error';
      $sql ="DELETE
              FROM relation
              WHERE :follow_id = follow_id AND :follower_id = follower_id";
    }else{
      $action = '登録';
      $flash_type = 'sucsess';
      $sql ="INSERT INTO relation(follow_id,follower_id)
              VALUES(:follow_id,:follower_id)";
    }
    try {
      $dbh = dbConnect();
      $stmt = $dbh->prepare($sql);
      $stmt->execute(array(':follow_id' => $current_user['id'] , ':follower_id' => $user_id));

      debug('user'.$current_user['id'].' → user'.$user_id);
      debug("フォロー${action}成功");

      $return = array('action' => $action,
                      'follow_count' => current(get_user_count('follow',$profile_user_id)),
                      'follower_count' => current(get_user_count('follower',$profile_user_id)));
      echo json_encode($return);

    } catch (\Exception $e) {
      debug("フォロー${action}失敗");
      error_log('エラー発生:' . $e->getMessage());
      set_flash('error',ERR_MSG1);
      echo json_encode("error");
    }
  }
}

debug('------------------------------');
