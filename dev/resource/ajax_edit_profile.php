<?php
require_once('config.php');
require_once('auth.php');

if(isset($_POST)){
  debug('POST送信があります');
  debug('POST内容:'.print_r($_POST,true));

  $user_id = $_POST['user_id'];
  $name_data = $_POST['name_data'];
  $comment_data = $_POST['comment_data'];
  $icon_data = $_POST['icon_data'];

  // バリデーション
  valid_name($name_data);
  if(isset($error_messages['name'])){
    $return = array('flash_type' => 'flash_error',
                    'flash_message' => $error_messages['name']);
    unset($error_messages);
    echo json_encode($return);
   exit();
  }
  // バリデーション
  if(valid_length($comment_data,100)){
    $return = array('flash_type' => 'flash_error',
                    'flash_message' => 'コメントは１００文字以内で入力してください');
    echo json_encode($return);
    exit();
  }

  // バリデーションOKならDB更新処理
  try {
    $dbh = dbConnect();
    $sql = "UPDATE users
            SET name = :name_data,
                profile_comment = :comment_data,
                user_icon = :icon_data
            WHERE id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':name_data' => $name_data,
                         ':comment_data' => $comment_data,
                         ':icon_data' => $icon_data,
                         ':user_id' => $user_id));

    debug('アイコン更新成功');
    set_flash('sucsess','プロフィールを更新しました');
    echo json_encode('sucsess');
  } catch (\Exception $e) {
    debug('プロフィール更新失敗');
    set_flash('error',ERR_MSG1);
  }
}
