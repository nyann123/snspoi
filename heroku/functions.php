<?php
//================================
// バリデーション関数
//================================
//メールアドレスの重複チェック
function check_email_duplicate($email){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM users
          WHERE email = :email AND delete_flg = 0 LIMIT 1";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':email' => $email));
  $user = $stmt->fetch();
  return $user;
}

//お気に入りの重複チェック
function check_favolite_duplicate($user_id,$post_id){
  $dbh = dbConnect();
  $sql = "SELECT *
          FROM favorite
          WHERE user_id = :user_id AND post_id = :post_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id ,
                       ':post_id' => $post_id));
  $favorite = $stmt->fetch();
  return $favorite;
}

// 長さチェック
function valid_length($str,$length){
  $result = mb_strlen($str) <= $length ? true: false;
  return !$result;
}

// 名前のバリデーション
function valid_name($name){
  global $error_messages;
  if ( empty($name) ){
    $error_messages['name'] = 'なまえを入力してください';
  }elseif(valid_length($name,8) ){
    $error_messages['name'] = 'なまえは8文字以内で入力してください';
  }
}
// メールアドレスのバリデーション
function valid_email($email){
  global $error_messages;
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
    $error_messages['email'] = 'Emailの形式で入力してください';
  }elseif ( check_email_duplicate($email) ){
    $error_messages['email'] = 'すでに登録済みのメールアドレスです';
  }
}
// パスワードのバリデーション
function valid_password($pass){
  global $error_messages;
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,100}+\z/i', $pass)) {
    $error_messages['pass'] = 'パスワードは半角英数字をそれぞれ1文字以上含んだ6文字以上で設定してください。';
  }
}
// 投稿内容のバリデーション
function valid_post($post){
  global $error_messages;
  if (empty($post)){
    $error_messages = '投稿の内容がありません';
  }else if(valid_length($post,300)){
    $error_messages = '内容が長すぎます';
  }
}

//================================
// データベース
//================================
// ユーザー情報を取得する
function get_user($user_id){
    try {
    $dbh = dbConnect();
    $sql = "SELECT id,name,user_icon,profile_comment
            FROM users
            WHERE id = :id AND delete_flg = 0 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $user_id));
    return $stmt->fetch();
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}

function get_edit_user($user_id){
    try {
    $dbh = dbConnect();
    $sql = "SELECT id,name,email,user_icon,profile_comment
            FROM users
            WHERE id = :id AND delete_flg = 0 ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':id' => $user_id));
    return $stmt->fetch();
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}

function get_users($query, $type, $offset_count=0){
  try {
    $dbh = dbConnect();

    switch ($type) {
      case 'follows':
      $sql = "SELECT follower_id
              FROM relation
              WHERE :follow_id = follow_id AND delete_flg = 0
              LIMIT 20 offset :offset_count";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':follow_id', $query);
        break;

      case 'followers':
      $sql = "SELECT follow_id
              FROM relation
              WHERE :follower_id = follower_id AND delete_flg = 0
              LIMIT 20 offset :offset_count";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':follower_id', $query);
        break;

      case 'search':
      $sql = "SELECT id
              FROM users
              WHERE name LIKE CONCAT('%',:input,'%') AND delete_flg = 0
              LIMIT 20 offset :offset_count";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':input', $query);
        break;
    }

    $stmt->bindValue(':offset_count', $offset_count, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}

// delete_flgを変更する
function change_delete_flg($user,$flg){
  try {
    $dbh = dbConnect();
    $dbh->beginTransaction();

    //usersテーブル
    $sql1 = 'UPDATE users SET delete_flg = :flg WHERE id = :id';
    $stmt1 = $dbh->prepare($sql1);
    $stmt1->execute(array(':flg' => $flg , ':id' => $user['id']));
    //postsテーブル
    $sql2 = 'UPDATE posts SET delete_flg = :flg WHERE user_id = :id';
    $stmt2 = $dbh->prepare($sql2);
    $stmt2->execute(array(':flg' => $flg , ':id' => $user['id']));
    //favoriteテーブル
    $sql3 = 'UPDATE favorite SET delete_flg = :flg WHERE user_id = :id';
    $stmt3 = $dbh->prepare($sql3);
    $stmt3->execute(array(':flg' => $flg , ':id' => $user['id']));
    //relationテーブル(フォロー)
    $sql4 = 'UPDATE relation SET delete_flg = :flg WHERE follow_id = :id';
    $stmt4 = $dbh->prepare($sql4);
    $stmt4->execute(array(':flg' => $flg , ':id' => $user['id']));
    //relationテーブル(フォロワー)
    $sql5 = 'UPDATE relation SET delete_flg = :flg WHERE follower_id = :id';
    $stmt5 = $dbh->prepare($sql5);
    $stmt5->execute(array(':flg' => $flg , ':id' => $user['id']));

    $dbh->commit();
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
    $dbh->rollback();
    reload();
  }
}

//既にフォローされているか確認する
function check_follow($follow_user,$follower_user){
  $dbh = dbConnect();
  $sql = "SELECT follow_id,follower_id
          FROM relation
          WHERE :follow_id =follow_id AND :follower_id = follower_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':follow_id' => $follow_user,
                       ':followed_id' => $follower_user));
  return  $stmt->fetch();
}

//ユーザーの各種カウントを取得する
function get_user_count($object,$user_id){
  $dbh = dbConnect();

  switch ($object) {
    case 'post':
    $sql ="SELECT COUNT(post_content)
          FROM posts
          WHERE user_id = :id AND delete_flg = 0";
      break;

    case 'follow':
    $sql ="SELECT COUNT(follower_id)
          FROM relation
          WHERE follow_id = :id AND delete_flg = 0";
      break;

    case 'follower':
    $sql ="SELECT COUNT(follow_id)
          FROM relation
          WHERE follower_id = :id AND delete_flg = 0";
      break;

    case 'favorite':
    $sql ="SELECT COUNT(post_id)
          FROM favorite
          WHERE user_id = :id AND delete_flg = 0";
  }

  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':id' => $user_id));
  return $stmt->fetch();
}

//投稿のお気に入り数を取得する
function get_post_favorite_count($post_id){
  $dbh = dbConnect();
  $sql = "SELECT COUNT(user_id)
          FROM favorite
          WHERE post_id = :post_id";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':post_id' => $post_id));
  return $stmt->fetch();
}

// ユーザーの投稿を取得する
function get_posts($page_id,$type,$offset_count=0){
  global $current_user;
  try {
    $dbh = dbConnect();
    // ページに合わせてSQLを変える
    switch ($type) {
      //自分の投稿を取得する
      case 'my_post':
      $sql = "SELECT u.name,u.user_icon,p.id,p.user_id,p.post_content,p.created_at
              FROM users u INNER JOIN posts p ON u.id = p.user_id
              WHERE p.user_id = :id AND p.delete_flg = 0
              ORDER BY p.created_at DESC
              LIMIT 10 OFFSET :offset_count";
      break;

      //お気に入り登録した投稿を取得する
      case 'favorite':
      $sql = "SELECT u.name,u.user_icon,p.id,p.user_id,p.post_content,p.created_at
              FROM posts p INNER JOIN favorite f ON p.id = f.post_id
              INNER JOIN users u ON u.id = p.user_id
              WHERE f.user_id = :id AND p.delete_flg = 0
              ORDER BY f.id DESC
              LIMIT 10 OFFSET :offset_count";
        break;

      // 自分とフォロー中のユーザー投稿取得
      case 'timeline':
      $sql = "SELECT u.name,u.user_icon,p.id,p.user_id,p.post_content,p.created_at
              FROM users u INNER JOIN posts p ON u.id = p.user_id
              WHERE  p.user_id = :id AND p.delete_flg = 0
              UNION
              SELECT u.name,u.user_icon,p.id,p.user_id,p.post_content,p.created_at
              FROM users u INNER JOIN posts p ON u.id = p.user_id
              INNER JOIN relation ON relation.follower_id = p.user_id
              WHERE relation.follow_id = :id AND p.delete_flg = 0
              ORDER BY created_at DESC
              LIMIT 10 OFFSET :offset_count";
      break;
    }

    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':id', $page_id);
    $stmt->bindValue(':offset_count', $offset_count, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  } catch (\Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    set_flash('error',ERR_MSG1);
  }
}

//ユーザー検索
function search_user($input){
  $dbh = dbConnect();
  $sql = "SELECT id
          FROM users
          WHERE name LIKE CONCAT('%',:input,'%')";
  $stmt = $dbh->prepare($sql);
  $stmt->execute(array(':input' => $input));
  return $stmt->fetchAll();
}

//================================
// その他
//================================
//ユーザーをログインさせる
function login($user_id,$pass_save){
  //ログイン有効期限（デフォルトを１時間とする）
  $sesLimit = 60*60;
  $_SESSION['login_date'] = time();
  // ログイン保持にチェックがある場合
  if($pass_save){
        $_SESSION['login_limit'] = $sesLimit * 24 * 30;
  }else{
        $_SESSION['login_limit'] = $sesLimit;
  }
  $_SESSION['user_id'] = $user_id;
  }
//ログイン中ユーザーのアクセス制限
function check_logged_in(){
  if (isset($_SESSION['user_id'])){
        header("Location:user_page.php?page_id=${_SESSION['user_id']}&type=main");
    exit();
  }
}

function h($str){
  return htmlspecialchars($str,ENT_QUOTES);
}

// 行数を取得する
function get_line_count($str){
  return substr_count($str,"\n") + 1;
}

//ログイン中のユーザーであるか確認
function is_myself($user){
  global $current_user;
  $result = $current_user['id'] === $user ? true : false;
  return $result;
}

function is_guest(){
  global $current_user;
  $result = $current_user === 'guest' ? true : false;
  return $result;
}

function set_old_form_data($str){
  global ${'old'.$str};
  if(isset($_SESSION[$str])){
    ${'old'.$str} = $_SESSION[$str];
  }
  unset($_SESSION[$str]);
}

function get_form_data($str){
  global $current_user;
  global ${'old'.$str};
  //入力フォームにエラーがあれば送信したデータを表示
  //なければDBのデーターを表示
  if(isset(${'old'.$str}) && ${'old'.$str} !== $current_user[$str]){
    return ${'old'.$str};
  }else{
    return $current_user[$str];
  }
}

// フラッシュメッセージをセットする
function set_flash($type,$message){
  $_SESSION['flash']['type'] = "flash_${type}";
  $_SESSION['flash']['message'] = $message;
}

function reload(){
  header('Location:'.$_SERVER['REQUEST_URI']);
  exit();
}
