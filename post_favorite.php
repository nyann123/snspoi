<?php
debug('お気に入り追加のPOST送信があります');
$post_id = $_POST['post_id'];
var_dump($_POST);

//お気に入りの重複チェック
if(check_favolite_duplicate($current_user['id'],$post_id)){
  debug('登録済みです');
  set_flash('error','既に登録されています');

  header("Location:mypage.php?page_id=${page_id}");
  exit();
}else{
  $dbh = dbConnect();
  $sql = "insert into favorite(user_id,post_id)
          value(:user_id,:post_id)";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $current_user['id'] , ':post_id' => $post_id));

  if($stmt){
    debug('クエリ成功しました');
    set_flash('sucsess','お気に入りに登録しました');
    debug('お気に入り登録成功');

    header("Location:mypage.php?page_id=${page_id}");
    exit();
  }else{
    debug('クエリ失敗しました。');
    set_flash('error',ERR_MSG1);
    debug('お気に入り登録失敗');
  }
}
