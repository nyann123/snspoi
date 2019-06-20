$(function(){

  //================================
  // ヘッダー
  //================================

  function toggle_cursor(classname){
    $(`${'.'+classname} > span > i`).toggleClass('fa-caret-down');
    $(`${'.'+classname} > span > i`).toggleClass('fa-caret-up');
  }

  // メニュー
  $('.toggle_menu.menu').on('click',function(){
    if ( $('.timeline_child').hasClass('open')){
      $('.timeline_child').toggleClass('open');
      toggle_cursor('timeline');
    }
    $('.menu_child').toggleClass('open');
    toggle_cursor('menu');
  });

  //タイムライン
  $('.toggle_menu.timeline').on('click',function(){
    if ( $('.menu_child').hasClass('open')){
      $('.menu_child').toggleClass('open');
      toggle_cursor('menu');
    }
    $('.timeline_child').toggleClass('open');
      toggle_cursor('timeline');
  });

  // メッセージ表示
  if($('#js_show_msg').text().replace(/^[\s　]+|[\s　]+$/g, "").length){
    $('#js_show_msg').slideToggle('slow');
    setTimeout(function(){ $('#js_show_msg').slideToggle('slow'); }, 2000);
    }


  //================================
  // 投稿フォーム
  //================================
  // フォーカス時に入力フォームを拡大して投稿ボタンを出す
  $('.text_area').on('focus',function(){
    $('.text_area').addClass('show_text_area');
    $('#post_btn').show();
  });

  //フォームに入力がなければ入力フォームとボタンを戻す
  $('.text_area').on('focusout',function(){
    if ($('.text_area').val().length === 0){
      $('.text_area').toggleClass('show_text_area');
      $('#post_btn').hide();
    }
  });

  //フォームに入力があるときだけ投稿ボタンを活性化
  $('.text_area').on('input',function(){
    if ($('.text_area').val().length !== 0){
      $('#post_btn').prop('disabled',false);
    }else{
      $('#post_btn').prop('disabled',true);
    }
  })

  //================================
  // 投稿削除
  //================================
  // モーダルウィンドウを開く
  $(".delete_btn").on('click',function(){
      let target_modal = $(this).data("target");
          $(target_modal).fadeIn();

      return false;
  });

  // モーダルウィンドウを閉じる
  $(".modal_close").on('click',function(){
      $(this).parents(".modal").fadeOut();
      return false;
  });

  //================================
  // フォローボタン
  //================================
  let first_flg = 0;

  $(document).on('mouseenter','.following',function(){
    $(this).text('解除');
    $(this).toggleClass('following');
    $(this).toggleClass('unfollow');
    first_flg = 1;
  });

  $(document).on('mouseleave','.unfollow',function(){
    if (first_flg === 1) {
      $(this).text('フォロー中');
      $(this).toggleClass('following');
      $(this).toggleClass('unfollow');
      first_flg = 0;
    }
  });

  //================================
  // アイコン変更
  //================================

  $('.profile_icon > img').on('mouseenter',function(){
    $('.edit_icon').css('display','block');
  })

  //画像が無くなった時も操作できるように
  $('.profile_icon').on('mouseenter',function(){
    $('.edit_icon').css('display','block');
  })

  $('.edit_icon').on('mouseleave',function(){
    $('.edit_icon').css('display','none');
  })

  $('.icon_upload_btn').on('click',function(){
    $('.icon_upload').click();
  })

  $('.edit_icon').on('click',function(){
    $('.edit_icon_menu').toggleClass('open');
  });

});
