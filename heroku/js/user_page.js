$(function(){function n(e,o){$message="#js_show_msg",$($message).hasClass("flash_error")||$($message).hasClass("flash_sucsess")?$($message).hasClass("flash_error")&&"flash_error"===e||$($message).hasClass("flash_sucsess")&&"flash_sucsess"===e||($($message).toggleClass("flash_error"),$($message).toggleClass("flash_sucsess")):$($message).addClass(e),$($message).text(o),$($message).slideToggle("slow"),setTimeout(function(){$($message).slideToggle("slow")},2e3)}function a(e,o){o=o||window.location.href,e=e.replace(/[\[\]]/g,"\\$&");var t=new RegExp("[?&]"+e+"(=([^&#]*)|&|#|$)").exec(o);return t?!!t[2]&&decodeURIComponent(t[2].replace(/\+/g," ")):null}$(".slide_menu").show(),$(document).on("click",".show_all",function(){var e=$(this).parent().height();$(this).prev().removeClass("ellipsis");var o=$(this).parent().height();$(this).parent().height(e),$(this).parent().animate({height:o},"slow","swing"),$(this).remove()}),$(".textarea").on("focus",function(){$(this).addClass("show_textarea"),$("#post_btn").show(),$(".counter").show()}),$(".textarea").on("focusout",function(){0===$(".textarea").val().length&&($(this).removeClass("show_textarea"),$(this).css("height","24px"),$("#post_btn").hide(),$(".counter").hide())}),$(".textarea").on("input",function(){0!==$(this).val().length&&$(this).val().length<=300?$("#post_btn").prop("disabled",!1):$("#post_btn").prop("disabled",!0)}),$(".textarea").on("input",function(){var e=$(this).get(0).scrollHeight;$(this).get(0).offsetHeight<e&&$(this).css("height",e+"px")}),$(".textarea").on("input",function(){var e=$(this).val().length;$(".show_count").text(e),300<e?$(".show_count").css("color","#FF7763"):$(".show_count").css("color","#FFF")}),$(document).on("click",".delete_btn",function(){var e=$(this).data("target"),o=$(this).next().find(".post_content"),t=function(e){return e.match(/\n/g)?e.match(/\n/g).length+1:1}(o.text());return scroll_position=$(window).scrollTop(),$("body").addClass("fixed").css({top:-scroll_position}),$(".modal").fadeIn(),$(e).fadeIn(),10<t?o.css("overflow","auto"):o.css("overflow",""),!1}),$(".show_prof").on("click",function(){scroll_position=$(window).scrollTop(),$("body").addClass("fixed").css({top:-scroll_position}),$(".modal").fadeIn(),$(".slide_prof").addClass("open")}),$(document).on("mouseenter",".editing .profile_icon > img",function(){$(".edit_icon").css("display","block")}),$(document).on("mouseenter",".editing .profile_icon",function(){$(".edit_icon").css("display","block")}),$(document).on("mouseleave",".editing .edit_icon",function(){$(".edit_icon").css("display","none")}),$(".edit_icon").on("click",function(){$(".icon_upload").click()}),$(document).on("input",".edit_comment",function(){var e=$(this).get(0).scrollHeight;$(this).get(0).offsetHeight<e&&$(this).css("height",e+"px")});var e=$(".profile .user_name").text(),o=$(".profile .profile_comment").text(),t=$(".profile img").attr("src");$(".profile .edit_btn").on("click",function(){return scroll_position=$(window).scrollTop(),$("body").addClass("fixed").css({top:-scroll_position}),$(".profile").addClass("editing"),$(".modal").removeClass("modal_close"),$(".modal").fadeIn(),$(".profile .user_name").replaceWith('<input class="edit_name border_white" type="text" value="'+e+'">'),$(".edit_icon").css("display","block"),$(".profile .profile_comment").replaceWith('<textarea class="edit_comment border_white" type="text">'+o),$(this).toggle(),$(".profile .btn_flex").css("display","flex"),!1}),$(".slide_prof .edit_btn").on("click",function(){return scroll_position=$(window).scrollTop(),$("body").addClass("fixed").css({top:-scroll_position}),$(".slide_prof").addClass("editing"),$(".modal").removeClass("modal_close"),$(".slide_prof .user_name").replaceWith('<input class="edit_name border_white" type="text" value="'+e+'">'),$(".edit_icon").css("display","block"),$(".slide_prof .profile_comment").replaceWith('<textarea class="edit_comment border_white" type="text">'+o),$(this).toggle(),$(".slide_prof .btn_flex").css("display","flex"),!1}),$(document).on("click",".end_edit",function(){$(".slide_prof .edit_name").replaceWith('<p class="user_name">'+e+"</p>"),$(".slide_prof .edit_comment").replaceWith('<p class="profile_comment">'+o+"</p>"),$(".slide_prof img").attr("src",t),$(".profile img").attr("src",t),$(".icon_upload").val(""),$(".edit_icon").css("display","none"),$(".slide_prof").removeClass("editing"),$(".modal").addClass("modal_close"),$(".edit_btn").css("display","inline"),$(".slide_prof .btn_flex").css("display","none"),$(".profile").removeClass("editing")}),$(document).on("click",".modal_close",function(){return $("body").removeClass("fixed").css({top:0}),window.scrollTo(0,scroll_position),$(".modal").fadeOut(),$(".delete_confirmation").fadeOut(),$(".slide_prof").removeClass("open"),$(".slide_menu").removeClass("open"),$(".profile").hasClass("editing")&&($(".profile .edit_name").replaceWith('<p class="user_name">'+e+"</p>"),$(".profile .edit_comment").replaceWith('<p class="profile_comment">'+o+"</p>"),$(".slide_prof img").attr("src",t),$(".profile img").attr("src",t),$(".icon_upload").val(""),$(".edit_icon").css("display","none"),$(".modal").addClass("modal_close"),$(".edit_btn").css("display","inline"),$(".profile .btn_flex").css("display","none"),$(".profile").removeClass("editing")),!1});var s=0;$(document).on("mouseenter",".following",function(){$(this).text("解除"),$(this).toggleClass("following"),$(this).toggleClass("unfollow"),s=1}),$(document).on("mouseleave",".unfollow",function(){1===s&&($(this).text("フォロー中"),$(this).toggleClass("following"),$(this).toggleClass("unfollow"),s=0)}),$(document).on("click",".gotop",function(){return $("body, html").animate({scrollTop:0},500),!1}),$(document).on("click",".favorite_btn",function(e){e.stopPropagation();var o=$(this),t=$(".profile_count + .favorite > a > .count_num"),s=a("page_id"),i=o.prev().val();$.ajax({type:"POST",url:"ajax_post_favorite_process.php",dataType:"json",data:{page_id:s,post_id:i}}).done(function(e){"error"===e?location.reload():(t.text(e.profile_count),o.next(".post_count").text(e.post_count),o.children("i").toggleClass("fas"),o.children("i").toggleClass("far"))}).fail(function(){location.reload()})}),$(document).on("click",".follow_btn",function(e){e.stopPropagation();var o=$(this),t=$(".profile_count + .follow > a > .count_num"),s=$(".profile_count + .follower > a > .count_num"),i=$(".profile_user_id").val();user_id=o.prev().val(),$.ajax({type:"POST",url:"ajax_follow_process.php",dataType:"json",data:{profile_user_id:i,user_id:user_id}}).done(function(e){"error"===e?location.reload():"登録"===e.action?(o.toggleClass("following"),o.text("フォロー中")):"解除"===e.action&&(o.removeClass("following"),o.removeClass("unfollow"),o.text("フォロー")),t.text(e.follow_count),s.text(e.follower_count)}).fail(function(){location.reload()})}),$(".icon_upload").on("change",function(e){e.stopPropagation();if(10485760<this.files[0].size)n("flash_error","ファイルサイズは10M以下にしてください"),$(this).val("");else if(this.files[0].name.match(/.(jpg|jpeg|png)$/i)){$(".profile_icon > img").attr("src","img/loading.gif"),$(".profile_save").prop("disabled",!0);var o=new FormData($("#icon_form").get(0));$.ajax({type:"POST",url:"ajax_icon_create.php",dataType:"json",data:o,cache:!1,contentType:!1,processData:!1}).done(function(e){$(".profile_icon > img").attr("src",e),$(".edit_icon").css("display","none"),$(".profile_save").prop("disabled",!1)}).fail(function(){location.reload()})}else n("flash_error","対応していない拡張子です")}),$(".profile_save").on("click",function(e){e.stopPropagation();var o=$(".profile .edit_name").val()||$(".slide_prof .edit_name").val()||"",t=$(".profile .edit_comment").val()||$(".slide_prof .edit_comment").val()||"",s=$(".profile_icon > img").attr("src"),i=$(this).data("user_id");$.ajax({type:"POST",url:"ajax_edit_profile.php",dataType:"json",data:{name_data:o,comment_data:t,icon_data:s,user_id:i}}).done(function(e){e.flash_message?n(e.flash_type,e.flash_message):location.reload()}).fail(function(){location.reload()})});var i=10;$(window).on("scroll",function(){var e=$(document).innerHeight()-$(window).innerHeight(),o=a("page_id")||a("query")||"";page_type=a("type"),end_post_flg=0,.9*e<=$(window).scrollTop()&&0===flg&&(1,$.ajax({type:"POST",url:"ajax_more_data.php",dataType:"json",data:{offset:i,query:o,page_type:page_type}}).done(function(e){i+=e.data_count,e.data_html?$(".main_items").append(e.data_html):end_post_flg=1,1!==end_post_flg||$(".item_container:last").next().hasClass("gotop")||$(".main_items").append("<button type='button' class='gotop'>トップへ戻る <i class='fas fa-caret-up'></i></button>"),0}).fail(function(){}))})});