$(function(){
    let $btn = $('.favorite_btn'),
        $profile_count = $('.profile_count + .favorite > a > .count_num'),
        post_id; //投稿のID

    $btn.on('click',function(e){
    e.stopPropagation();
    $this = $(this);
    post_id = $this.prev().val(); //投稿のid
    $.ajax({
        type: 'POST',
        url: 'post_favorite_process.php',
        dataType: 'json',
        data: { favorite: true,
                post_id:post_id}
    }).done(function(phpreturn){
      // php側でエラーが発生したらリロードしてエラーメッセージを表示させる
      if(phpreturn ==="error"){
        location.reload();
      }else{
        // プロフィール内のカウントを更新する
        $profile_count.text(phpreturn['user_count']);
        // 投稿内のカウントを更新する
        $this.next('.post_count').text(phpreturn['post_count']);
        // アイコンを切り替える
        $this.children('i').toggleClass('fas');
        $this.children('i').toggleClass('far');
      }
    }).fail(function(msg) {
        console.log('ajax error');
    });
  });
});
