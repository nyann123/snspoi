<?php
require_once('config.php');
debugLogStart();
debug(print_r($_POST,true));

if(!empty($_POST['icon_save'])){
  debug('アイコン更新のPOST送信があります');
  $user_id = $_POST['user_id'];

  //ファイル名のみを切り出す
  $icon_data = substr($_POST['icon_data'],4);
  $small_icon_data = 'small'.$icon_data;
  debug($icon_data);
  debug($small_icon_data);

  try {
    $dbh = dbConnect();
    $sql = "UPDATE users
            SET user_icon = :icon_data,
                user_icon_small = :small_icon_data
            WHERE id = :user_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':icon_data' => $icon_data,
                         ':small_icon_data' => $small_icon_data,
                         ':user_id' => $user_id));
    if (query_result($stmt)) {
      debug('アイコン更新成功');
      set_flash('sucsess','アイコンを更新しました');
    }
  } catch (\Exception $e) {
    debug('アイコン更新失敗');
    set_flash('error',ERR_MSG1);
  }


}
