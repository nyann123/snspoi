<?php
debug('お気に入り追加のPOST送信があります');
$post_id = $_POST['post_id'];
$prev_page = basename($_SERVER['HTTP_REFERER']);

//お気に入りの重複チェック
if(check_favolite_duplicate($current_user['id'],$post_id)){
  debug('登録済みです');
  set_flash('error','既に登録されています');

  header("Location:$prev_page");
  exit();
}else{
  try{
    $dbh = dbConnect();
    $sql = "insert into favorite(user_id,post_id)
            value(:user_id,:post_id)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

    if($stmt){
      debug('クエリ成功しました');
      set_flash('sucsess','お気に入りに登録しました');
      debug('お気に入り登録成功');

      header("Location:$prev_page");
      exit();
    }else{
      debug('クエリ失敗しました。');
      set_flash('error',ERR_MSG1);
    }
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
  debug('お気に入り登録失敗');

  header("Location:$prev_page");
  exit();
}
