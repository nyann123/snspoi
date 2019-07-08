$(function(){

  function move_cousor(id){
      switch (id) {
      case 'email':
        $('.flash_cursor').css({'top':'-20px'});
        break;
      case 'password':
        $('.flash_cursor').css({'top':'35px'});
        break;
    }
  }

  //カーソルを初期位置に
  $('.flash_cursor').css({'top':'-20px'});

  $('input').on('focusin',function(){
    move_cousor( $(this).attr('id') );      //フォームのidを取得して関数に渡す
  });

});
