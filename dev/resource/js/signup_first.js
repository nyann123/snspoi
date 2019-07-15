$(function(){

  var emailregex = new RegExp(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/);
  var email_flg

  //カーソルを初期位置に
  $('.flash_cursor').css({'top':'5px'});

  // 渡されたidで選択中のフォームを判定してカーソルを移動させる
  function move_cousor(id){
      switch (id) {
      case 'email':
        $('.flash_cursor').css({'top':'5px'});
        break;
      case 'js_btn':
        $('.flash_cursor').css({'top':'80px'});
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

  //バリデーションが全て通っていたらボタンを有効にする
  function btn_actiovation(){
    if(email_flg){
      $('#js_btn').prop('disabled',false);
      move_cousor('js_btn');
    }else {
      $('#js_btn').prop('disabled',true);
      move_cousor( $(':focus').attr('id') );
    }
  }

  $('input').on('focusin',function(){
    move_cousor( $(this).attr('id') );      //選択中のフォームのidを取得して関数に渡す
  });

  $('input').on('input',function(){
    var $selector = "#" + $(this).attr('id');     //HTML指定用にidに#をつける
    switch ($selector) {
      case '#email':
        email_validate($selector);
        break;
    }
    btn_actiovation();
  })

});
