$(function(){

  //================================
  // ヘッダー
  //================================
  $('.show_menu').on('click',function(){
    scroll_position = $(window).scrollTop();
    $('body').addClass('fixed').css({'top': -scroll_position});
    $('.modal').fadeIn();
    $('.slide_menu').addClass('open');
  })

  // メッセージ表示
  if($('#js_show_msg').text().replace(/^[\s　]+|[\s　]+$/g, "").length){
    $('#js_show_msg').slideToggle('slow');
    setTimeout(function(){ $('#js_show_msg').slideToggle('slow'); }, 2000);
  }


});
