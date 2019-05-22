// メッセージ表示
if($('#js_show_msg').text().replace(/^[\s　]+|[\s　]+$/g, "").length){
  $('#js_show_msg').slideToggle('slow');
  setTimeout(function(){ $('#js_show_msg').slideToggle('slow'); }, 3000);
  }
