commentIndex = -1; // Index de l'élément commenté ou discuté ou édité
var commentAction = false; // 1 = commentaire, 2 = discussion, 3 = edition
var commentMouseOn = false;
var iAmAdmin = false; 
var linkadded = false;
// To preload images
jQuery.preloadImages = function()
{
  for(var i = 0; i<arguments.length; i++) {
    jQuery("<img>").attr("src", arguments[i]);
  }
}

$(function () {
  iAmAdmin = ( $.cookie("comparoPrivateKey") != null
              &&  $.cookie("comparoPrivateKey") != "null"
              && $.cookie("comparoPublicKey") == publicKey );
  // Si on a les droits pour commenter
  if ( iAmAdmin ) {    
    $("#liste-differences > li").addClass("letscomment").click(commentStart);
    $("#option-discuss").append("<br />"
            +"<img src=\"img/key.png\" alt=\"\" width=\"16\" height=\"16\" /> Autoriser les discussions sur ce Comparo : "
            +"<label for=\"enableDiscuss\"><input type=\"radio\" id=\"enableDiscuss\" name=\"discuss\" value=\"1\">oui,</label> "
            +"<label for=\"disableDiscuss\"><input type=\"radio\" id=\"disableDiscuss\" name=\"discuss\" value=\"0\">non</label>");
    if ( discussAllowed ) {
      $("#enableDiscuss").attr("checked", "checked").click(toggleDiscuss);
      $("#disableDiscuss").removeAttr("checked").click(toggleDiscuss);
    }
    else {
      $("#disableDiscuss").attr("checked", "checked").click(toggleDiscuss);
      $("#enableDiscuss").removeAttr("checked").click(toggleDiscuss);
    }
  }
   
  // Stop comment or discuss when clicking somewhere
  $("body").click(function() {
    if ( ! commentMouseOn ) {
      commentDiscussStop();
    }    
  });
    
  // Get comments
  $.post("ajax.php",
    {
        action: "get",
        comparo: idComparo
    }, 
    function(data){ 
     $(data).find("commentaire").each(
      function(){
        commentSet($(this).text(), $(this).attr("index"), false);
      });
      
     $(data).find("discuss").each(
      function(){
        discussAdd($(this).text(), $(this).attr("index"), false, $(this).attr("pseudo"), $(this).attr("id"));
      });
          
     $(data).find("modif").each(
      function(){
        modifSet($(this).text(), $(this).attr("idline"), false);
      });
     
      restrict_lines() // Pour mettre à jour les filtres 
    }, "xml");
    
    
  // Preload images
  $.preloadImages("img/indicator.white.gif", "img/exclamation.png");
    
  // Discuss
  $("#liste-differences > li").prepend("<a href=\"#\" title=\"Discuter sur cette ligne\" class=\"discuss-button\">Discuter</a>");
  
  // Edit
  if ( true ) $("#liste-differences > li").prepend("<a href=\"#\" title=\"Editer cette ligne\" class=\"edit-button\">&Eacute;diter cette ligne</a>");
  
  if ( discussAllowed ) {
    $(".discuss-button").css("display", "block");
  }
  else {
    $(".discuss-button").css("display", "none");
  }
  
  $(".discuss-button").click(discussStart);
  $(".edit-button").click(editStart);
  $("#pseudo").change(changeNick);
  
  if ( $.cookie("comparoPseudo") != null ) {
    $("#pseudo").val($.cookie("comparoPseudo"));
  }
  
  // Prevent form validation
  $("form").submit(commentDiscussStop);    
});

function commentMouseOnLi() { commentMouseOn = true; }
function commentMouseOffLi() { commentMouseOn = false; }

function editStart() {
  var li = $(this).parent("li:first");
  var index = $("#liste-differences > li").index(li);  
  
  commentDiscussStop();
  commentMouseOnLi();

  // Bind
  li.bind("mouseover", commentMouseOnLi).bind("mouseout", commentMouseOffLi);
  
  // Variables
  commentAction = 3;
  commentIndex = index;
  
  // Show input
  count = ids_r[index].length;
  if ( count == 0 ) alert('Aucune ligne éditable');
  else {  
    li.append("<div id='editor'></div>");
    
    for ( i = 0; i < ids_r[index].length; i++) {
      $("#editor").append("<textarea name='editor' rows='2' class='editor'></textarea>");
      $(".editor").eq(i).val(lines_modified[index][i]).data('id', ids_r[index][i]);
    }
  }
  
  $('#editor').append("<input type='button' value='OK' id='editor-send' />");
  $('#editor-send').click(commentDiscussStop);
  
  $('.editor').keyup(function() {
    var text = $(this).val().split("\n");
    for ( i = 0; i < text.length; i++ ) {
      if ( text[i].length > 40 ) {
        $(this).addClass('toolong');
        return false;
      }
    }
    $(this).removeClass('toolong');
    return false;
  });

  return false;
}

function discussStart() {
  var li = $(this).parent("li:first");
  var index = $("#liste-differences > li").index(li);
  
  if ( ! checkNick() ) {
    return false;
  }
  
  commentDiscussStop();
  commentMouseOnLi();
  commentDom(li);
  
  // Bind
  li.bind("mouseover", commentMouseOnLi).bind("mouseout", commentMouseOffLi);
  
  // Variables
  commentAction = 2;
  commentIndex = index;
  
  // Show input
  li.children(".discuss").css("display", "block");
  li.children(".discuss").append("<li id=\"discuss-input\"><input type=\"text\" name=\"discuss-text\" id=\"discuss-text\" /></li>")
  $("#discuss-text").focus();
  return false;
}

function commentDom(jelement) {
  if ( ! jelement.data("commentDom") ) {
    jelement.append("<span class=\"comment\" style=\"display:none\"></span><li class=\"discuss\" style=\"display:none\"></li>");
    jelement.data("commentDom", true);
  }
}

function checkNick() {
  if ( $.trim($("#pseudo").val()) == "" ) {
    alert("Vous n'avez pas choisi de pseudo.\nRemplissez le champ dans '+ d'options'");
    return false;
  }
  return true;
}


function changeNick() {
  pseudo = $("#pseudo").val();
  $.cookie("comparoPseudo", pseudo, { expires: 365 });
}


function commentStart() {
  var element = $(this);
  var index = $("#liste-differences > li").index(element);

  if ( index === commentIndex && ( commentAction === 2 || commentAction === 3 ) ) {
    return false;  
  }

  if ( index === commentIndex && commentAction === 1 ) {
    $("#comment").focus();
    return false;  
  }
    
  commentDiscussStop();
  commentMouseOnLi();
  commentDom(element);
  
  // Bind
  element.bind("mouseover", commentMouseOnLi).bind("mouseout", commentMouseOffLi);
  
  // Variables
  commentAction = 1;
  commentIndex = index;
  
  // show input
  element.children(".comment").css("display", "none");
  $(this).children(".comment").after("<span id=\"comment_input\"><input type=\"text\" name=\"comment\" value=\"\" id=\"comment\" /></span>");
  $("#comment").val($(this).children(".comment").text()).focus(); // Set text and focus

}




function commentDiscussStop() {
  if ( commentIndex !== -1 ) {
    if ( commentAction === 1 ) {
      commentSet($("#comment").val(), commentIndex, true);
      $("#comment_input").remove();
    }
    else if ( commentAction === 2 ) {
      text = $("#discuss-text").val();
      $("#discuss-input").remove();
      discussAdd(text, commentIndex, true, $("#pseudo").val());
    }
    else if ( commentAction === 3 ) {
      $(".editor").each(function() {
        modifSet($(this).val(), $(this).data('id'), true);
      });
    
      $("#editor").remove();
    }
  }
  
  $("#liste-differences > li").unbind("mouseover", commentMouseOnLi).unbind("mouseout", commentMouseOffLi);
  commentMouseOffLi();
  commentAction = 0;
  commentIndex = -1;
  return false;
}

function discussDelete() {
  var element = $(this).parent("ul:first")
  var parent = element.parent("li:first");
  var line = parent.parent("li:first");
  var id = element.data("id");
  if ( id === undefined ) {
    alert("Impossible de supprimer. Rechargez la page");
    return false;
  }
  
  element.hide("blind").remove();
  if ( parent.children("ul").length == 0 ) {
    parent.hide("blind");
    line.removeClass("discussed");
    classActive(line);
  }
  
  setStatus(line, 1);
  
  $.ajax({
    type: "POST",
    cache: false,
    dataType: "text",
    data: {
      action: "delete",
      privateKey: $.cookie("comparoPrivateKey"),
      comparo: idComparo,
      discussId: id
    },
    url: "ajax.php",
    success: function(msg, textStatus) {
      if ( msg != "ok" ) {
        addError(line);
        setStatus(line, 0);
        alert(msg);   
      }
      else {
        setStatus(line, 0);
      }
    },
    error: function() {
      addError(line);
      setStatus(line, 0);
      alert("Le message n'a pas pu être supprimé.");
    }
  }); 
  
  return false;
}


function classActive(element) {
  if ( element.hasClass("commented") || element.hasClass("discussed") || element.hasClass("modified") ) {
    element.addClass("active");
  }
  else {
    element.removeClass("active");
  }
}

function discussAdd(text, index, send, pseudo, id) {
  text = $.trim(text);
  var element = $("#liste-differences > li:eq("+index+")");
  
  if ( text != '' ) {
    commentDom(element);
    element.addClass("discussed").addClass("active").children(".discuss").css("display", "block");
    
    if ( iAmAdmin ) {
      var ligne = $("<ul><span class=\"delete\"></span><span class=\"username\"></span> : <span class=\"text_discuss\"></span></ul>");
      ligne.children(".delete").click(discussDelete);
    }
    else {
      var ligne = $("<ul><span class=\"username\"></span> : <span class=\"text_discuss\"></span></ul>");
    }
    ligne.children(".username").text(pseudo);
    ligne.children(".text_discuss").text(text);
    ligne.data("id", id);
    element.children(".discuss").append(ligne);
    
    if ( send ) {
      setStatus(element, 1);
      $.ajax({
        type: "POST",
        cache: false,
        dataType: "text",
        data: {
          action: "discuss",
          comparo: idComparo,
          value: text,
          index: index,
          pseudo: pseudo
        },
        url: "ajax.php",
        success: function(msg, textStatus) {
          if ( ! IsNumeric(msg) ) {
            addError(element);
            setStatus(element, 0);
            alert(msg);   
          }   
          else {
            ligne.data("id", msg);
            setStatus(element, 0);
          }
        },
        error: function() {
          addError(element);
          setStatus(element, 0);
          alert("Le commentaire n'a pas pu être enregistré");
        }
      }); 
    }
  }
  else {
    if ( element.children(".discuss").children().length == 0 ) {
      element.children(".discuss").css("display", "none");
    }
  }
  
}

function modifSet(text, id, send) {
  index = -1;
  index_j = -1;

  if ( ! linkadded ) {
    linkadded = true;
    $(".files").append("<h2>Fichier modifié</h2><p><a id='fileModif' href='modified-file.php?comparo="+idComparo+"'>Télécharger le fichier modifié</a></p>");
    $("#fileModif").click(function() {
      alert("Attention\nCette fonction est expérimentale. Vérifiez systématiquement que des lignes n'ont pas été perdues ou mal modifiées");
      return true;
    });
  }

  for ( i = 0; i < ids_r.length; i++ ) {
    for ( j = 0; j < ids_r[i].length; j++ ) {
      if ( ids_r[i][j] == id ) {
        index = i;
        index_j = j;
      }
    }
  }
  
  if ( index == -1 ) {
    alert("Erreur : impossible de trouver la ligne concernée par la modification\nID="+id);
    return false;
  }
  
  lines_modified[index][index_j] = text;
  
  var element = $("#liste-differences > li").eq(index);

  if ( element.children('.edited').length == 0 ) {
    element.append('<div class="edited"></div>');
  }
  
  found = false;
  var edited = element.children('.edited');
  count = edited.find("span").length;
  for ( i = 0; i < count; i++ ) {
    if ( edited.find("span").eq(i).data("id") == id ) {
      edited.find("span").eq(i).text(text.replace("\n", " || "));
      found = true;
    }
  }
  
  if ( ! found ) {
    if ( count > 0 ) edited.append(' ][ ');
    var span = $("<span></span>").data("id", id).text(text.replace("\n", " || "));
    edited.append(span);
  }
  
  element.addClass("commented").addClass("active");
  
  if ( send ) {
    setStatus(element, 1);
    $.ajax({
      type: "POST",
      cache: false,
      dataType: "text",
      data: {
        action: "modif",
        comparo: idComparo,
        value: text,
        privateKey: $.cookie("comparoPrivateKey"),
        index: index,
        idline: id
      },
      url: "ajax.php",
      success: function(msg, textStatus) {
        if ( ! IsNumeric(msg) ) {
          addError(element);
          setStatus(element, 0);
          alert(msg);   
        }   
        else {
          setStatus(element, 0);
        }
      },
      error: function() {
        addError(element);
        setStatus(element, 0);
        alert("La modification n'a pas pu être enregistrée");
      }
    }); 
  }
}


function commentSet(text, index, send) {
  text = $.trim(text);
  var element = $("#liste-differences > li:eq("+index+")");
  
  commentDom(element);

  // Display
  element.children(".comment").text(text);
  if ( text != '' ) {
    element.addClass("commented").addClass("active");
    element.children(".comment").css("display", "block");
  }
  else {
    element.removeClass("commented");
    classActive(element);
    element.children(".comment").css("display", "none");
  }
  
  if ( send ) {
    element.addClass("comment-sending");
    $.ajax({
      type: "POST",
      cache: false,
      dataType: "text",
      data: {
        action: "set",
        privateKey: $.cookie("comparoPrivateKey"),
        comparo: idComparo,
        value: text,
        index: index
      },
      url: "ajax.php",
      success: function(msg, textStatus) {
        if ( msg != "ok" ) {
          addError(element);
          setStatus(element, 0);
          alert(msg);   
        }   
        else {
          setStatus(element, 0);
        }
      },
      error: function() {
        addError(element);
        setStatus(element, 0);
        alert("Le commentaire n'a pas pu être enregistré");
      }
    }); 
  }
}

// Enable / Disable discuss in a Comparos
function toggleDiscuss() {
  if ( $("#enableDiscuss").attr("checked") ) {
    var action = "enable_discuss";
    $(".discuss-button").css("display","block");
  }
  else {
    var action = "disable_discuss";
    $(".discuss-button").css("display","none");
    commentDiscussStop();
  }
  
  $.ajax({
    type: "POST",
    cache: false,
    dataType: "text",
    data: {
      action: action,
      privateKey: $.cookie("comparoPrivateKey"),
      comparo: idComparo
    },
    url: "ajax.php",
    success: function(msg, textStatus) {
      if ( msg != "ok" ) {
        alert(msg);   
      }   
    },
    error: function() {
      alert("Impossible de mettre a jour les autorisations de discussions.");
    }
  }); 
}

function IsNumeric(sText) {
  var ValidChars = "0123456789.";
  var IsNumber=true;
  var Char;
 
  for (i = 0; i < sText.length && IsNumber == true; i++) { 
    Char = sText.charAt(i); 
    if (ValidChars.indexOf(Char) == -1) {
      IsNumber = false;
    }
  }
  return IsNumber;   
}

function setStatus(element, status) {
  if ( status == 1 ) { // Loading
    element.removeClass("comment-error").addClass("comment-sending");
  }
  else {
    var count = element.data("errorCount");
    if ( count == null ) {
      element.removeClass("comment-error").removeClass("comment-sending");
    }
    else {
      element.removeClass("comment-sending").addClass("comment-error");
    }
  }

}

function addError(element) {
  var count = element.data("errorCount");

  if ( count == null ) {
    element.data("errorCount", 1);
  }
  else {
    element.data("errorCount", count + 1);
  }
}