$(function(){

  //メッセージを表示
  function show_slide_message(flash_type,flash_message){
    $message = ('#js_show_msg');
    // クラスがまだない場合渡されたクラスを入れる
    if(!$($message).hasClass('flash_error')
    && !$($message).hasClass('flash_sucsess')){
      $($message).addClass(flash_type);
    // すでにクラスがあって同じクラスを渡していた場合は何もしない
  }else if( $($message).hasClass('flash_error') && flash_type === 'flash_error'
    || $($message).hasClass('flash_sucsess') && flash_type === 'flash_sucsess'){
    // 既にクラスがあって別のクラスを渡していた場合は入れ替える
    }else{
      $($message).toggleClass('flash_error');
      $($message).toggleClass('flash_sucsess');
    }
    // 渡されたメッセージを表示させる
    $($message).text(flash_message);
    $($message).slideToggle('slow');
    setTimeout(function(){ $($message).slideToggle('slow'); }, 2000);
  }

  // getパラメータ取得
  function get_param(name, url) {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  //お気に入り登録処理
  $(document).on('click','.favorite_btn',function(e){
    e.stopPropagation();
    let $this = $(this),
        $profile_count = $('.profile_count + .favorite > a > .count_num'),
        post_id = $this.prev().val();

    $.ajax({
        type: 'POST',
        url: 'ajax_post_favorite_process.php',
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
      location.reload();
    });
  });

  // フォロー登録、解除処理
  $('.follow_btn').on('click',function(e){
    e.stopPropagation();
    let $this = $(this),
        $follow_count = $('.profile_count + .follow > a > .count_num'),
        $follower_count = $('.profile_count + .follower > a > .count_num'),
        profile_user_id = $('.profile_user_id').val();
        user_id = $this.prev().val();

    $.ajax({
        type: 'POST',
        url: 'ajax_follow_process.php',
        dataType: 'json',
        data: { follow: true,
                profile_user_id: profile_user_id,
                user_id: user_id}
    }).done(function(phpreturn){
      // php側の処理に合わせてボタンを更新する
      // php側でエラーが発生したらリロードしてエラーメッセージを表示させる
      if(phpreturn === "error"){
        location.reload();
      }else if(phpreturn['action'] ==="登録"){
        $this.toggleClass('following')
        $this.text('フォロー中');
      }else if(phpreturn['action'] ==="解除"){
        $this.removeClass('following');
        $this.removeClass('unfollow')
        $this.text('フォロー');
      }
      // プロフィール内のカウントを更新する
      $follow_count.text(phpreturn['follow_count']);
      $follower_count.text(phpreturn['follower_count']);
    }).fail(function() {
      location.reload();
    });
  });

  //アイコン加工
  $('.icon_upload').on('change',function(e){
   e.stopPropagation();
   let max_file_size = 10485760;

   // ファイルサイズ制限
   if (max_file_size < this.files[0].size){
     show_slide_message('flash_error','ファイルサイズは10M以下にしてください');
     $(this).val('');
   }else{
     // フォームデータを取得
     let formdata = new FormData($('#icon_form').get(0));
     $.ajax({
       type: 'POST',
       url: 'ajax_icon_create.php',
       dataType: 'json',
       data: formdata,
       cache       : false,
       contentType : false,
       processData : false
     }).done(function(data){
       // アイコンを返ってきた加工済みアイコンと入れ替える
       $('.profile_icon > img').attr('src',data);
       $(".icon_save").prop('disabled', false);
     }).fail(function(){
      location.reload();
     });
   }
  });

  //アイコン保存
  $('.icon_save').on('click',function(e){
    e.stopPropagation();
    let icon_data = $('.profile_icon > img').attr('src'),
        user_id = $(this).data('user_id');

    $.ajax({
      type: 'POST',
      url: 'ajax_icon_save.php',
      dataType: 'json',
      data: {icon_save: true,
             icon_data: icon_data,
             user_id: user_id}
    })
    .done(function(){
      location.reload();
    }).fail(function(){
      location.reload();
    });
  });

  //最後までスクロールしたら投稿を取得する
  offset= 0;
  more_posts_flg = 0;
  $(window).on('scroll', function () {
  let doch = $(document).innerHeight(), //ページ全体の高さ
      winh = $(window).innerHeight(), //ウィンドウの高さ
      bottom = doch - winh, //ページ全体の高さ - ウィンドウの高さ = ページの最下部位置
      page_id = get_param('page_id');

  if (bottom <= $(window).scrollTop()) {
    // flgが立っていればoffsetを更新しない
    if(more_posts_flg === 0){
      offset += 10;
    }else{
      more_posts_flg = 0;
    }

    $.ajax({
      type: 'POST',
      url: 'ajax_text.php',
      dataType: 'json',
      data: {more_posts: true,
             offset: offset,
             page_id: page_id}
    }).done(function(data){
      //投稿が返されていれば表示する
      if(data){
        $('.main_items').append(data);
      }else{
        more_posts_flg = 1;
      }
    }).fail(function(){
      more_posts_flg = 1;
    })
  }

  });



});
