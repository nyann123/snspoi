$(function(){

  var emailregex = new RegExp(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/);
  var name_flg
  var email_flg
  var password_flg

  //カーソルを初期位置に
  $('.flash_cursor').css({'top':'5px'});

  // 渡されたidで選択中のフォームを判定してカーソルを移動させる
  function move_cousor(id){
      switch (id) {
      case 'name':
        $('.flash_cursor').css({'top':'5px'});
        break;
      case 'email':
        $('.flash_cursor').css({'top':'105px'});
        break;
      case 'password':
        $('.flash_cursor').css({'top':'205px'});
        break;
      case 'js_btn':
        $('.flash_cursor').css({'top':'275px'});
    }
  }

  //名前のバリデーション
  function name_validate(name){
    input_name = $(name).val();
    error_message = $(name + '+ span');

    if ( input_name === '' ){
      $(name).addClass('error');
      $(error_message).text("名前を入力してください");
        name_flg = 0;

    }else if ( input_name.length > 8){   //9文字以上をエラーに
      $(name).addClass('error');
      $(error_message).text("名前が長すぎます");
      name_flg = 0;

    }else{
      $(name).removeClass('error');
      $(error_message).text('');
      name_flg = 1;
    }
  }

  //メールのバリデーション
  function email_validate(email){
    input_email = $(email).val();
    error_message = $(email +'+ span');

    if ( input_email === '' ){
      $(email).addClass('error');
      $(error_message).text("メールアドレスを入力してください");
      email_flg = 0;

    }else if ( !emailregex.test(input_email)){     //正規表現を使ってemail形式以外はエラーに
        $(email).addClass('error');
        $(error_message).text("メールアドレスが異常です");
        email_flg = 0;

    }else{
      $(email).removeClass('error');
      $(error_message).text('');
      email_flg = 1;
    }
  }

  //パスワードのバリデーション
  function password_validate(password){
    input_pass = $(password).val();
    error_message = $(password + '+ span');

    if ( input_pass === ''){
      $(password).addClass('error');
      $(error_message).text("パスワードを入力してください");
      password_flg = 0;

    }else if ( input_pass.length < 6 ){
      $(password).addClass('error');
      $(error_message).text("パスワードが短すぎます");
      password_flg = 0;

    }else{
      $(password).removeClass('error');
      $(error_message).text('');
      password_flg = 1;
    }
  }

  //バリデーションが全て通っていたらボタンを有効にする
  function btn_actiovation(){
    if( name_flg && email_flg && password_flg ){
      $('#js_btn').prop('disabled',false);
      move_cousor('js_btn');
    }else {
      $('#js_btn').prop('disabled',true);
      move_cousor( $(':focus').attr('id') );
    }
  }



  $(window).on('load',function(){
    if( $('#name').val() && $('#email').val() && $('#password').val() ){
      name_validate( '#' + $('#name').attr('id') )
      email_validate( '#' + $('#email').attr('id') )
      password_validate( '#' + $('#password').attr('id') )
      btn_actiovation();
    }
  })

  $('input').on('focusin',function(){
    move_cousor( $(this).attr('id') );      //選択中のフォームのidを取得して関数に渡す
  });

  $('input').on('input',function(){
    var $selector = "#" + $(this).attr('id');     //HTML指定用にidに#をつける
    switch ($selector) {
      case '#name':
        name_validate($selector);
        break;
      case '#email':
        email_validate($selector);
        break;
      case '#password':
        password_validate($selector);
        break;
    }
    btn_actiovation();
  })

});
