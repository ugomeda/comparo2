var timer = null;

$(document).ready(function(){
  timer = setTimeout("updatePosts()", 20000);
});

function updatePosts() {
  clearTimeout(timer); // Evite les double boucles

  $.ajax({
    cache: false,
    data: {
        action: "last_posts",
        time: lastupdate,
        sid: sid,
        nocache: Math.random()
    },
    dataType: "xml",
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      alert("Erreur lors de la mise a jour : "+textStatus+"\n"+XMLHttpRequest.responseText);
    },
    success: function(data){ 
      $(data).find("post").each(function() {
        $("#nomsg").css("display", "none");
        var content = $(this).text();
        var username = $(this).attr("username");
        var id = $(this).attr("id");
        var date = $(this).attr("date");
        
        var html = '<span class="user"><img src="../img/unknown_user.jpg" width="100" height="100" alt="" />'+username+'</span><span class="content"><span class="date">'+date+'</span>'+content+'</span><span class="clear"></span>';
        
        var old = $("#p-"+id);
        if ( old.length ) {
          old.html(html);
          old.effect("highlight", 2000);
        }
        else {
          var post = $("<li style='display:none' id='p-"+id+"'>"+html+"</li>");
          $("#posts").append(post)
          post.show("blind");
        }
      });
      $(data).find("lastupdate").each(function() {
        lastupdate = $(this).text();
      });
    },
    type: "POST",
    url: "ajax.php"});
    
  timer = setTimeout("updatePosts()", 20000);
}