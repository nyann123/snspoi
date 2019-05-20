<header>
  <div class="container">

  <?php if(empty($_SESSION['user_id'])):?>
    <h1><a href="login_form.php">タイトルタイトル</a></h1>
  <?php else: ?>
    <h1><a href="mypage.php?page_id=<?php echo $_SESSION['user_id'] ?>">タイトルタイトル</a></h1>
  <?php endif ?>
    <nav id="top-nav">
      <ul>
        <?php if(empty($_SESSION['user_id'])):?>
          <li><a href="signup.php" class="btn btn-primary">ユーザー登録</a></li>
          <li><a href="login_form.php">ログイン</a></li>
        <?php else:?>
          <li><a href="mypage.php?page_id=<?php echo $_SESSION['user_id'] ?>">マイページ</a></li>
          <li><a href="logout_process.php">ログアウト</a></li>
        <?php endif ?>
      </ul>
    </nav>
  </div>
</header>
