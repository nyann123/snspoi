function move_cousor(id){
    switch (id) {
    case 'email':
      $('.flash_cursor').css({'top':'-20px'});
      break;
    case 'password':
      $('.flash_cursor').css({'top':'45px'});
      break;
    case 'login_btn':
      $('.flash_cursor').css({'top':'105px'});
  }
}


$('input').on('focusin',function(){
  move_cousor( $(this).attr('id') );      //選択中のフォームのidを取得して関数に渡す
});
