<?php
debug('投稿削除のPOST送信があります');
$post_id = $_POST['post_id'];
$user_id = $_POST['user_id'];

//ログイン中のユーザーの投稿であれば削除処理
if (is_myself($user_id)) {
  try {
    $dbh = dbConnect();
    $sql = "DELETE
            FROM posts
            WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $post_id));

    debug('削除成功');
    set_flash('error','削除しました');
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}else{
  debug('削除失敗');
  set_flash('error','他人の投稿は削除できません');
}

reload();
