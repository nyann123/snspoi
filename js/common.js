//================================
// ヘッダー
//================================
// メニューボタン
$('#toggle_menu').on('click',function(){
  $('#child').toggleClass('close');
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
