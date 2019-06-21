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


});
