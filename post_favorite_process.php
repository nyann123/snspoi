<?php
debug('お気に入り追加のPOST送信があります');
$post_id = $_POST['post_id'];
$prev_page = basename($_SERVER['HTTP_REFERER']);

//既に登録されているか確認
if(check_favolite_duplicate($current_user['id'],$post_id)){
  $action = '解除';
  $flash_type = 'error';
  $sql = "DELETE
          FROM favorite
          WHERE :user_id = user_id AND :post_id = post_id";
}else{
  $action = '登録';
  $flash_type = 'sucsess';
  $sql = "INSERT INTO favorite(user_id,post_id)
          VALUES(:user_id,:post_id)";
}
try{
  $dbh = dbConnect();
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

  if(query_result($stmt)){
    debug("お気に入り${action}成功");
    set_flash("$flash_type","お気に入り${action}しました");

    header("Location:$prev_page");
    exit();
  }
} catch (\Exception $e) {
  error_log('エラー発生:' . $e->getMessage());
  set_flash('error',ERR_MSG1);
}
debug("お気に入り${action}失敗");

header("Location:$prev_page");
exit();
