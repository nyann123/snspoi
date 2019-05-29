<?php
debug('投稿のPOST送信があります');
$post_content = $_POST['content'];
$prev_page = basename($_SERVER['HTTP_REFERER']);

//投稿の長さチェック
valid_post_length($post_content);

set_flash('error',$error_messages);

if (empty($error_messages)){
  debug('バリデーションOK');

  try {
    $dbh = dbConnect();
    $sql = "INSERT INTO posts(user_id,post_content,created_at)
            VALUES(:user_id,:post_content,:created_at)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_content' => $post_content , ':created_at' => date('Y-m-d H:i:s')));
    if($stmt){
      debug('クエリ成功しました');
      set_flash('sucsess','投稿しました');
      debug('投稿成功');

      header("Location:$prev_page");
      exit();
    }else{
      debug('クエリ失敗しました。');
      set_flash('error',ERR_MSG1);
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}
debug('投稿失敗');

header("Location:$prev_page");
exit();
