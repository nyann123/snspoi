<?php
$post_content = $_POST['content'];
$prev_page = basename($_SERVER['HTTP_REFERER']);

//投稿の長さチェック
valid_post($post_content);
//メッセージをsessionに格納（エラーが発生したら定数で上書きされる）
set_flash('error',$error_messages);

if (empty($error_messages)){
  
  try {
    $dbh = dbConnect();
    $sql = "INSERT INTO posts(user_id,post_content)
            VALUES(:user_id,:post_content)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':user_id' => $current_user['id'] , ':post_content' => $post_content));
    if(query_result($stmt)){
      set_flash('sucsess','投稿しました');
      
      header("Location:$prev_page");
      exit();
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}

header("Location:$prev_page");
exit();
