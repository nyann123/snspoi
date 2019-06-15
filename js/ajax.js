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
                post_id: post_id}
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
    }).fail(function() {
      console.log('ajax error');
      location.reload();
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
        data: { follow: true,
                user_id: user_id}
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
      location.reload();
    });
  });


 $('.icon_upload').on('change',function(e){
      // フォームデータを取得
      let formdata = new FormData($('#icon_form').get(0));

      e.stopPropagation();
      $.ajax({
        type: 'POST',
        url: 'icon_create.php',
        dataType: 'json',
        data: formdata,
        dataType    : "json",
        cache       : false,
        contentType : false,
        processData : false
      }).done(function(data){
        $('.profile_icon > img').attr('src',data);
        $(".icon_save").prop('disabled', false);
      }).fail(function(){
        console.log('error');
      });
  });


  $('.icon_save').on('click',function(e){
    let icon_data = $('.profile_icon > img').attr('src'),
        user_id = $(this).data('user_id');

    e.stopPropagation();
    $.ajax({
      type: 'POST',
      url: 'icon_save.php',
      dataType: 'json',
      data: {icon_save: true,
             icon_data: icon_data,
             user_id: user_id}
    })
    .done(function(){
      console.log('sucsess');
      location.reload();
    }).fail(function(){
      console.log('error');
      location.reload();

    });
  });

});
