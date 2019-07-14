<?php
if (!empty($_POST['search_user'])){
  $hoge = $_POST['search_input'];
  header("Location:user_page.php?type=search&query=${hoge}");
}
?>


<header>
  <div class="container">
    <div class="flex">

      <?php if(basename($_SERVER['PHP_SELF']) === 'user_page.php'): ?>
        <div class="show_prof">
          <i class="fas fa-user"></i>
        </div>
      <?php else: ?>
        <div class="dummy"></div>
      <?php endif ?>
      <!-- ログイン中ならマイページ　していなければログインページへ -->
      <?php if(empty($_SESSION['user_id'])):?>
        <h1><a href="login_form.php">タイトルタイトル</a></h1>
      <?php else: ?>
        <h1><a href="user_page.php?page_id=<?= $_SESSION['user_id'] ?>&type=main">タイトルタイトル</a></h1>
      <?php endif ?>

      <nav>
        <ul>
          <?php if(empty($_SESSION['user_id'])):?>
            <li class="sp_mq_small"><a href="signup_first.php">ユーザー登録</a></li>
            <li class="sp_mq_small"><a href="login_form.php">ログイン</a></li>
          <?php else:?>
            <li class="show_menu">メニュー
              <div class="slide_menu">
                <ul>
                  <li class="sp_mq_put"><a href="user_page.php?type=timeline">タイムライン</a></li>
                  <li class="sp_mq_put"><a href="user_page.php?page_id=<?= $_SESSION['user_id'] ?>&type=main">マイページ</a></li>
                  <li><a href="prof_edit.php">プロフィール編集</a></li>
                  <li><a href="logout_process.php">ログアウト</a></li>
                  <li><a href="withdraw.php">退会</a></li>
                </ul>
              </div>
            </li>
            <li class="tab_mq_hidden"><a href="user_page.php?type=timeline">タイムライン</a></li>
            <li class="tab_mq_hidden"><a href="user_page.php?page_id=<?= $_SESSION['user_id'] ?>&type=main">マイページ</a></li>
            <li class="search">
              <form method="post" action="#" class="search_container">
                <input type="text" name="search_input" placeholder="ユーザー検索">
                <input type="submit" name="search_user" value="&#xf002;">
              </form>
            </li>
            <li class="show_search">&#xf002;</li>
            <li class="close_search">&#xf00d;</li>
          <?php endif ?>
        </ul>
      </nav>
    </div>


  </div>
  <!-- フラッシュメッセージ -->
  <!-- ログインと新規登録ページでは表示しない -->
  <?php if(basename($_SERVER['PHP_SELF']) !== 'login_form.php'
        && basename($_SERVER['PHP_SELF']) !== 'signup_first.php'
        && basename($_SERVER['PHP_SELF']) !== 'signup_second.php'
        && basename($_SERVER['PHP_SELF']) !== 'prof_edit.php'): ?>

      <p id ="js_show_msg" class="message_slide <?php if(isset($flash_type)) echo $flash_type ?>">
        <?php if(isset($flash_messages)) echo $flash_messages ?>
      </p>

  <?php endif ?>

</header>
