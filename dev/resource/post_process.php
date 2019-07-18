<?php
debug('投稿のPOST送信があります');
$post_content = $_POST['content'];

//投稿の長さチェック
valid_post($post_content);

set_flash('error',$error_messages);

if (empty($error_messages)){
  debug('バリデーションOK');

  $date = new DateTime();
  $date->setTimeZone(new DateTimeZone('Asia/Tokyo'));

  try {
    $dbh = dbConnect();
    $sql = "INSERT INTO posts(user_id,post_content,created_at)
            VALUES(:user_id,:post_content,:created_at)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] ,
                         ':post_content' => $post_content,
                         ':created_at' => $date->format('Y-m-d H:i:s')));

    set_flash('sucsess','投稿しました');
    debug('投稿成功');

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    debug('投稿失敗');
    set_flash('error',ERR_MSG1);
  }
}

reload();
