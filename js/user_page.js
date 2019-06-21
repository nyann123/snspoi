$(function(){

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
  // モーダルウィンドウを開く処理
  $(".delete_btn").on('click',function(){
      let target_modal = $(this).data("target"),
          modal_content =$('.delete_confirmation > .post_content');
      //背景をスクロールできないように　&　スクロール場所を維持
      scroll_position = $(window).scrollTop();
      $('body').addClass('fixed').css({'top': -scroll_position});
      // モーダルウィンドウを開く
      $(target_modal).fadeIn();
      //高さが一定以上ならスクロールできるように
      if( $(modal_content).height() >= 200){
        $(modal_content).css('overflow','auto');
      }else{
        $(modal_content).css('overflow','');
      }
        return false;
  });

  // モーダルウィンドウを閉じる処理
  $(".modal_close").on('click',function(){
    // スクロール場所を維持
    $('body').removeClass('fixed').css({'top': 0});
    window.scrollTo( 0 , scroll_position );
    // モーダルウィンドウを閉じる
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
