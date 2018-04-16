var web_base_url = $('#baseurl').val();

$('input.tags').on('change', function(evt) {
   if($('input.tags:checked').length > 3) {
       this.checked = false;
       alert("您只能选择 "+3+" 个标签");
   }
});

function uncheckAll(divid) {
    $('#' + divid + ' :checkbox').prop('checked', false);
}
function checkAll(divid) {
    $('#' + divid + ' :checkbox').prop('checked', true);
}

function vote_post(post_id, method){ //method = upvote,downvote,fold,funny
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'GET',
      url: web_base_url + '/posts/' + post_id + '/' + method,
      data: {
          },
      success: function(data) {
         if (data != "notwork"){
            if (method == 'funny'){
               data ='搞笑'+data;
            }
            if (method == 'fold'){
               data = '折叠'+data;
            }
            $('#'+post_id+method).html(data);
         }
      }
   });
};

function replytopost(post_id, post_trim){
   document.getElementById("reply_to_post_id").value = post_id;
   document.getElementById("reply_to_post").classList.remove('hidden');
   var post_url = web_base_url + "/thread-posts/" + post_id;
   document.getElementById("reply_to_post_info").innerHTML = '回复 '+ '：<a href=\"' + post_url +'\">' + post_trim + '</a>';
};
function cancelreplytopost(){
   document.getElementById("reply_to_post_id").value = 0;
   document.getElementById("reply_to_post").classList.add('hidden');
   document.getElementById("reply_to_post_info").innerHTML = "";
};

function wordscount(item){
   var post = document.getElementById(item).value;
   var str = post.replace(/[\[\]\*\#\_\-\s\n\t\r]/g,"");
   alert("字数统计：" + str.length);
};

function expandpost(id){
   var x = document.getElementById('full'+id);
   var y = document.getElementById('abbreviated'+id);
   var z = document.getElementById('expand'+id);
   if (x.classList.contains('hidden')) {//这是一个缩过的，需要展开
      x.classList.remove('hidden');
      y.classList.add('hidden');
      z.innerHTML = '收起';
      z.classList.remove('pull-right');
      z.classList.add('text-center');
   } else {
      y.classList.remove('hidden');
      x.classList.add('hidden');
      z.innerHTML = '展开';
      z.classList.add('pull-right');
      z.classList.remove('text-center');
   }
};

function toggleCancelButtons(){
   $( ".cancel-button" ).toggleClass('hidden');
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/collections/clearupdates',
      data: {
          },
      success: function(data) {
         if (data != "notwork"){
            console.log('cleared updates');
         }
      }
   });
};

function cancelCollectionThread(thread_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/collections/cancel',
      data: {
         'thread_id': thread_id
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.thread'+thread_id ).addClass('hidden');
         }
      }
   });
};
function ToggleKeepUpdateThread(thread_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/collections/togglekeepupdate',
      data: {
         'thread_id': thread_id
          },
      success: function(data) {
         if (data != "notwork"){
            //console.log(data);
            if (data.keep_updated){
               $( '#togglekeepupdatethread'+thread_id ).html("不再提醒");
            }else{
               $( '#togglekeepupdatethread'+thread_id ).html("接收提醒");
            }

         }
      }
   });
};
function ToggleKeepUpdateUser(user_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/followers/togglekeepupdate',
      data: {
         'user_id': user_id
          },
      success: function(data) {
         if (data != "notwork"){
            console.log(data);
            if (data.keep_updated){
               $( '.togglekeepupdateuser'+user_id ).html("不再提醒");
            }else{
               $( '.togglekeepupdateuser'+user_id ).html("接收提醒");
            }

         }
      }
   });
}

function cancelfollow(user_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: "POST",
      url: web_base_url + '/users/followers/' +user_id,
      data: {
         '_method': "DELETE",
         'id': user_id,
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.follow'+user_id ).removeClass('hidden');
            $( '.cancelfollow'+user_id ).addClass('hidden');
            $( '.followuser'+user_id ).addClass('hidden');
         }
      }
   });
};

function follow(user_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/users/followers/' +user_id,
      data: {
         'id': user_id
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.follow'+user_id ).addClass('hidden');
            $( '.cancelfollow'+user_id ).removeClass('hidden');
         }
      }
   });
};
function deletepost(post_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/posts/' +post_id,
      data: {
         '_method': "DELETE",
         'id': post_id
          },
      success: function(data) {
         console.log(data);
         $( '#post'+post_id ).addClass('hidden');
      }
   });
}
function deletepostcomment(postcomment_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'POST',
      url: web_base_url + '/postcomments/' +postcomment_id,
      data: {
         '_method': "DELETE",
         'id': postcomment_id
          },
      success: function(data) {
         $( '#postcomment'+postcomment_id ).addClass('hidden');
      }
   });
}

function destroystatus(status_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: "POST",
      url: web_base_url + '/statuses/' +status_id,
      data: {
         '_method': "DELETE",
         'id': status_id,
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.status'+status_id ).addClass('hidden');
         }
      }
   });
};

$(document).ready(function(){
    $( 'textarea' ).click(function() {
        console.log('goingto initiate cache');
        $.ajaxSetup({
          headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
       });
       $.ajax({
          type: "GET",
          url: web_base_url + '/cache/initcache',
          data: {
              },
          success: function(data) {
             if (data != "notwork"){
                console.log(data);
             }
          }
       });
    });
});


$('textarea').keyup(_.debounce(function() {
   console.log('save');
   item_value = $(this).val();
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: "POST",
      url: web_base_url + '/cache/save',
      data: {
         'item_value': item_value,
          },
      success: function(data) {
         if (data != "notwork"){
            console.log(item_value);
         }
      }
   });
}, 1000));

function retrievecache(itemname){
   console.log('going to retrieve cache');
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: "GET",
      url: web_base_url + '/cache/retrieve',
      data: {
          },
      success: function(data) {
         if (data != "notwork"){
            console.log('cache retrieved');
            //console.log(data);
            $('#'+itemname).val(data);
         }else{
            console.log('no cache retrieved');
         }
      }
   });
};
$(document).ready(function(){
  $('.dropdown-submenu a.dropdown-test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});

function cancellink(user_id){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: "POST",
      url: web_base_url + '/linkedaccounts/destroy/' +user_id,
      data: {
         '_method': "DELETE",
         'id': user_id,
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.linkedaccount'+user_id ).addClass('hidden');
         }
      }
   });
};

function receivemessagesfromstrangers(){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'GET',
      url: web_base_url + '/messages/receivemessagesfromstrangers',
      data: {
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.receivemessagesfromstrangers').addClass('hidden');
            $( '.cancelreceivemessagesfromstrangers').removeClass('hidden');
         }
      }
   });
};
function cancelreceivemessagesfromstrangers(){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'GET',
      url: web_base_url + '/messages/cancelreceivemessagesfromstrangers',
      data: {
          },
      success: function(data) {
         if (data != "notwork"){
            $( '.receivemessagesfromstrangers').removeClass('hidden');
            $( '.cancelreceivemessagesfromstrangers').addClass('hidden');
         }
      }
   });
};

function receiveupvotereminders(){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'GET',
      url: web_base_url + '/messages/receiveupvotereminders',
      data: {
          },
      success: function(data) {
         if (data == "works"){
            $( '.receiveupvotereminders').addClass('hidden');
            $( '.cancelreceiveupvotereminders').removeClass('hidden');
         }
      }
   });
};
function cancelreceiveupvotereminders(){
   $.ajaxSetup({
      headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
   $.ajax({
      type: 'GET',
      url: web_base_url + '/messages/cancelreceiveupvotereminders',
      data: {
          },
      success: function(data) {
         if (data == "works"){
            $( '.receiveupvotereminders').removeClass('hidden');
            $( '.cancelreceiveupvotereminders').addClass('hidden');
         }
      }
   });
};
function sosadpretreat(string) {//将正常文本预处理
    string = string.replace(/\[br\]/g,"</p><br><p>");
    string = string.replace(/\r\n/g,"\n");
    string = string.replace(/\r/g,"\n");
    string = string.replace(/\n{1,}/g,"</p><p>");
    string = "<p>"+string+"</p>";
    string = string.replace(/<p><\/p>/g,"");
    return(string);
}
function doublebreakline(string) {//魔改md，使得单一换行能够显示
    string = string.replace(/\n/g,"<br>");
    return(string);
}

function addoption(thread_id){//让投票区增加分支选择项
    var option_number = $('#thread-'+thread_id+'-poll-options > input').length + 1;
    if (option_number<=10){
        $('#thread-'+thread_id+'-poll-options').append('<input type="text" name="option-'+option_number+'" class="form-control" id="thread-'+thread_id+'-poll-option-'+ option_number +'" placeholder="请填写选项'+option_number+'">');
        //console.log(option_number);
    }else{
        alert("只能填写十项");
    }
}
