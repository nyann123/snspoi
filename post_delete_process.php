<?php
debug('投稿削除のPOST送信があります');
$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];

//自分の投稿なら削除
if ($user_id === $current_user['id']) {
  $dbh = dbConnect();
  $sql = "DELETE
          FROM posts
          WHERE id = :id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':id' => $post_id));

  if($stmt){
    debug('クエリ成功しました');
    set_flash('error','削除しました');
    debug('削除成功');

    header("Location:mypage.php?page_id=${current_user['id']}");
    exit();
  }else{
    debug('クエリ失敗しました。');
    set_flash('error',ERR_MSG1);
    debug('削除失敗');
  }
}
