<header>
  <div class="container">
    <!-- ログイン中ならマイページ　していなければログインページへ -->
  <?php if(empty($_SESSION['user_id'])):?>
    <h1><a href="login_form.php">タイトルタイトル</a></h1>
  <?php else: ?>
    <h1><a href="user_page.php?page_id=<?= $_SESSION['user_id'] ?>">タイトルタイトル</a></h1>
  <?php endif ?>

    <nav id="top-nav">
      <ul>
        <?php if(empty($_SESSION['user_id'])):?>
          <li><a href="signup_form.php">ユーザー登録</a></li>
          <li><a href="login_form.php">ログイン</a></li>
        <?php else:?>
          <li class="toggle_menu menu">メニュー <span><i class="fas fa-caret-down"></i></span>
            <ul class="child menu_child">
              <li><a href="prof_edit.php">プロフィール編集</a></li>
              <li><a href="logout_process.php">ログアウト</a></li>
              <li><a href="withdraw.php">退会</a></li>
              <li>test</li>
            </ul>
          </li>
          <li class="toggle_menu timeline">タイムライン <span><i class="fas fa-caret-down"></i></span>
            <ul class="child timeline_child">
              <li><a href="timeline.php">test</a></li>
              <li><a href="#">test</a></li>
              <li><a href="#">test</a></li>
              <li>test</li>
            </ul>
          </li>
          <li><a href="user_page.php?page_id=<?= $_SESSION['user_id'] ?>">マイページ</a></li>
        <?php endif ?>
      </ul>
    </nav>
  </div>

  <!-- フラッシュメッセージ -->
  <!-- ログインと新規登録ページでは表示しない -->
  <?php if(basename($_SERVER['PHP_SELF']) !== 'login_form.php'
        && basename($_SERVER['PHP_SELF']) !== 'signup_form.php'
        && basename($_SERVER['PHP_SELF']) !== 'prof_edit.php' ): ?>

      <p id ="js_show_msg" class="message_slide <?php if(isset($flash_type)) echo $flash_type ?>">
        <?php if(isset($flash_messages)) echo $flash_messages ?>
      </p>

  <?php endif ?>

</header>
