$(function(){

  //================================
  // 投稿全文表示機能
  //================================
  //投稿の高さが一定以上なら続きを表示するボタンを出す
  $('.item_container > .post_content').each(function(){
    let content_height =  $(this).height();
    if(content_height >= 230){
      $(this).after('<button class="show_all">続きを表示する</button>');
    }
  });

  $(document).on('click','.show_all',function(){
    // 省略されている投稿の高さを取得
    omit_height = $(this).parent().height();
    //投稿の省略を解除
    $(this).prev().removeClass('ellipsis');
    // 全文表示された投稿の高さを取得
    all_height = $(this).parent().height();
    //一度高さを戻して
    $(this).parent().height(omit_height);
    //スライドで全文表示させる
    $(this).parent().animate({
      height: all_height
    },"slow","swing");

    //ボタンを消す
    $(this).remove()
  });

  //================================
  // 投稿フォーム
  //================================
  // フォーカス時に入力フォームを拡大して投稿ボタンを出す
  $('.textarea').on('focus',function(){
    $(this).addClass('show_textarea');
    $('#post_btn').show();
  });

  //フォームに入力がなければ入力フォームとボタンを戻す
  $('.textarea').on('focusout',function(){
    if ($('.textarea').val().length === 0){
      $(this).removeClass('show_textarea');
      $(this).css('height','24px');
      $('#post_btn').hide();
    }
  });

  //フォームに入力があるときだけ投稿ボタンを活性化
  $('.textarea').on('input',function(){
    if ($('.textarea').val().length !== 0){
      $('#post_btn').prop('disabled',false);
    }else{
      $('#post_btn').prop('disabled',true);
    }
  })

  // フォームの高さを自動調整(拡大のみ、縮小も実装したい)
  $('.textarea').on('input',function(){
  scroll_height = $(this).get(0).scrollHeight;
  offset_height = $(this).get(0).offsetHeight;

  if( scroll_height > offset_height ){
    $(this).css('height',scroll_height +"px");
  }

})


  //================================
  // 投稿削除
  //================================
  // モーダルウィンドウを開く処理
  $(".delete_btn").on('click',function(){
      let $target_modal = $(this).data("target"),
          $modal_content = $(this).next().find('.post_content')
      //背景をスクロールできないように　&　スクロール場所を維持
      scroll_position = $(window).scrollTop();
      $('body').addClass('fixed').css({'top': -scroll_position});
      // モーダルウィンドウを開く
      $($target_modal).fadeIn();
      //投稿の高さが一定以上ならスクロールできるように
      if( $(this).next().find('.post_content').height() >= 200){
        $modal_content.css('overflow','auto');
      }else{
        $modal_content.css('overflow','');
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
