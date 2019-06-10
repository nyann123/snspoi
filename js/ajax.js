$(function(){
  //お気に入り登録処理
  $('.favorite_btn').on('click',function(e){
    let $this = $(this),
        $profile_count = $('.profile_count + .favorite > a > .count_num'),
        post_id = $this.prev().val();

    e.stopPropagation();
    $.ajax({
        type: 'POST',
        url: 'post_favorite_process.php',
        dataType: 'json',
        data: { favorite: true,
                post_id:post_id}
    }).done(function(phpreturn){
      // php側でエラーが発生したらリロードしてエラーメッセージを表示させる
      if(phpreturn ==="error"){
        location.reload();
      }else{
        // プロフィール内のカウントを更新する
        $profile_count.text(phpreturn['profile_count']);
        // 投稿内のカウントを更新する
        $this.next('.post_count').text(phpreturn['post_count']);
        // アイコンを切り替える
        $this.children('i').toggleClass('fas');
        $this.children('i').toggleClass('far');
      }
    }).fail(function(msg) {
      console.log('ajax error');
    });
  });

  // フォロー登録、解除処理
  $('.follow_btn').on('click',function(e){
    let $this = $(this),
        $profile_count = $('.profile_count + .follow > a > .count_num'),
        $user_count = $this.parent().next().children('.user_count + .follower').children('.count_num');
        user_id = $this.prev().val();

    e.stopPropagation();
    $.ajax({
        type: 'POST',
        url: 'follow_process.php',
        dataType: 'json',
        data: { folo: true,
                user_id:user_id}
    }).done(function(phpreturn){
      // php側の処理に合わせてボタンを更新する
      // php側でエラーが発生したらリロードしてエラーメッセージを表示させる
      if(phpreturn ==="error"){
        location.reload();
      }else if(phpreturn['action'] ==="登録"){
        $this.toggleClass('following')
        $this.text('フォロー中');
      }else if(phpreturn['action'] ==="解除"){
        $this.toggleClass('unfollow')
        $this.text('フォロー');
      }
      // プロフィール内のカウントを更新する
      $profile_count.text(phpreturn['profile_count']);
      // 相手のカウントを更新する
      $user_count.text(phpreturn['user_count']);
    }).fail(function() {
      console.log('ajax error');
    });
  });

});
