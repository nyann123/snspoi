//================================
// ヘッダー
//================================

function toggle_updown(classname){
  $(`${'.'+classname} > span > i`).toggleClass('fa-caret-down');
  $(`${'.'+classname} > span > i`).toggleClass('fa-caret-up');
}

// メニュー
$('.toggle_menu.menu').on('click',function(){
  if ( $('.timeline_child').hasClass('open')){
    $('.timeline_child').toggleClass('open');
    toggle_updown('timeline');
  }
  $('.menu_child').toggleClass('open');
  toggle_updown('menu');
});

//タイムライン
$('.toggle_menu.timeline').on('click',function(){
  if ( $('.menu_child').hasClass('open')){
    $('.menu_child').toggleClass('open');
    toggle_updown('menu');
  }
  $('.timeline_child').toggleClass('open');
    toggle_updown('timeline');
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
