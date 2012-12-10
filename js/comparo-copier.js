$(document).ready(function(){
  //$("<a href=\"#\" class=\"copy\">Copier</a>").insertAfter("#liste-differences li .timings")
  $("#liste-differences li").prepend("<a href=\"#\" title=\"Copier cette ligne\" class=\"copy-button\">Copier</a>");
  
  $(".copy-button").click(copyOneLine);
});

function htmlentities(texte) {
	texte = texte.replace(/"/g,'&quot;'); // 34 22
	texte = texte.replace(/&/g,'&amp;'); // 38 26
	texte = texte.replace(/\'/g,'&#39;'); // 39 27
	texte = texte.replace(/</g,'&lt;'); // 60 3C
	texte = texte.replace(/>/g,'&gt;'); // 62 3E
	texte = texte.replace(/\^/g,'&circ;'); // 94 5E
	return texte;
}

function copyOneLine() {
  var element = $(this).parents("li:first");
  
  // Timings
  text = htmlentities(element.children(".timings").text());
  
  // Text 1
  var clone = element.children(".text:first").clone();
  clone.children(".id_relu").remove();
  clone.children(".id_nonrelu").remove();
  clone.children(".cpl_relu").remove();
  clone.children(".cpl_nonrelu").remove();
  clone.children("ins").remove();
  if ( element.hasClass("show-ins") || element.hasClass("show-del") ) {
    clone.children("del").remove();
  }
  else {
    clone.children("del").each(function() {
      $(this).text("["+$(this).text()+"]");  
    });
  }    
  
  text += "\n<br />"+htmlentities(clone.text());
  clone.remove();

  // Text 1
  if ( ! element.hasClass("line-ins") && ! element.hasClass("line-del") && ! element.hasClass("line-ok") && ! element.hasClass("line-timing") ) {
    var clone = element.children(".text:first").clone();
    
    clone.children(".id_relu").remove();
    clone.children(".id_nonrelu").remove();
    clone.children(".cpl_relu").remove();
    clone.children(".cpl_nonrelu").remove();
    clone.children("del").remove();
    clone.children("ins").each(function() {
      $(this).text("["+$(this).text()+"]");  
    });
    
    text += "\n<br />"+htmlentities(clone.text());
    clone.remove();
  }
  
  // Commentaire
  if ( element.children(".comment").length ) {  
    var comment = element.children(".comment").text();
    if ( comment != "" ) {
      text += "\n<br />Commentaire : "+htmlentities(comment);
    }
  }
  
  $("#thickbox .copytext").html(text);
  tb_show("", "#TB_inline?inlineId=thickbox&width=800&height=100", false);
  return false;
}