<?php
$prev_page = basename($_SERVER['HTTP_REFERER']);

// すでに登録されているか確認
if(check_follow($current_user['id'],$page_user)){
  $action = '解除';
  $flash_type = 'error';
  $sql1 ="DELETE
          FROM follows
          WHERE :follow_id = follow_id AND :followed_id = followed_id";
  $sql2 ="DELETE
          FROM followers
          WHERE :follow_id = follow_id AND :followed_id = followed_id";
}else{
  $action = '';
  $flash_type = 'sucsess';
  $sql1 ="INSERT INTO follows(follow_id,followed_id)
          VALUES(:follow_id,:followed_id)";
  $sql2 ="INSERT INTO followers(follow_id,followed_id)
          VALUES(:follow_id,:followed_id)";

}
try {
  $dbh = dbConnect();
  $dbh->beginTransaction();
  //followsテーブル
  $stmt1 = $dbh->prepare($sql1);
  $stmt1->execute(array(':follow_id' => $current_user['id'] , ':followed_id' => $page_user));
  //followersテーブル
  $stmt2 = $dbh->prepare($sql2);
  $stmt2->execute(array(':follow_id' => $current_user['id'] , ':followed_id' => $page_user));
  // 全てのクエリが成功していたら結果を保存する
  if (query_result($stmt1) && query_result($stmt2)) {
    $dbh->commit();
    debug("フォロー${action}成功");
    set_flash("$flash_type","フォロー${action}しました");

    header("Location:$prev_page");
    exit();
  }
} catch (\Exception $e) {
  $dbh->rollback();
  error_log('エラー発生:' . $e->getMessage());
  set_flash('error',ERR_MSG1);
}
debug("フォロー${action}失敗");

header("Location:$prev_page");
exit();
