$(function(){
  
  let emailregex = new RegExp(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/);
  let name_flg
  let email_flg

  //名前のバリデーション
  function name_validate(name){
    input_name = $(name).val();
    error_message = $(`${name} + span`);

    if ( input_name === '' ){
      $(name).addClass('error');
      $(error_message).text("なまえをにゅうりょくしてください");
        name_flg = 0;

    }else if ( input_name.length > 10){   //11文字以上をエラーに
      $(name).addClass('error');
      $(error_message).text("なまえがながすぎます");
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
    error_message = $(`${email} + span`);

    if ( input_email === '' ){
      $(email).addClass('error');
      $(error_message).text("めーるあどれすをにゅうりょくしてください");
      email_flg = 0;

    }else if ( !emailregex.test(input_email)){     //正規表現を使ってemail形式以外はエラーに
        $(email).addClass('error');
        $(error_message).text("めーるあどれすがいじょうです");
        email_flg = 0;

    }else{
      $(email).removeClass('error');
      $(error_message).text('');
      email_flg = 1;
    }
  }

  //バリデーションが全て通っていたらボタンを有効にする
  function btn_actiovation(){
    if( name_flg && email_flg ){
      $('#js_btn').prop('disabled',false);
    }else {
      $('#js_btn').prop('disabled',true);
    }
  }

  $(window).on('load',function(){
    name_validate( '#' + $('#name').attr('id') )
    email_validate( '#' + $('#email').attr('id') )
    btn_actiovation();
  })

  $('input').on('input',function(){
    let $selector = "#" + $(this).attr('id');     //HTML指定用にidに#をつける
    switch ($selector) {
      case '#name':
        name_validate($selector);
        break;
      case '#email':
        email_validate($selector);
        break;
    }
    btn_actiovation();
  });

});
