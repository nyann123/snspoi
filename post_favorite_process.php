<?php
debug('お気に入り追加のPOST送信があります');
$post_id = $_POST['post_id'];
$prev_page = basename($_SERVER['HTTP_REFERER']);

//既に登録されていたら削除
if(check_favolite_duplicate($current_user['id'],$post_id)){
  try{
    $dbh = dbConnect();
    $sql = "DELETE
            FROM favorite
            WHERE :user_id = user_id AND :post_id = post_id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

    if($stmt){
      debug('クエリ成功しました');
      debug('お気に入り解除成功');
      set_flash('error','お気に入り解除しました');

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
  debug('お気に入り解除失敗');

  header("Location:$prev_page");
  exit();

//登録されていなければ登録
}else{
  try{
    $dbh = dbConnect();
    $sql = "INSERT INTO favorite(user_id,post_id)
            VALUES(:user_id,:post_id)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

    if($stmt){
      debug('クエリ成功しました');
      debug('お気に入り登録成功');
      set_flash('sucsess','お気に入りに登録しました');

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
