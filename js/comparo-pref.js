$(function () {
  var prefId = new Array("show-ok", "show-edit", "show-timing", "show-ins", "show-del", "show-unk", "show-id", "show-graph", "show-vo", "mode-diff1", "mode-diff2", "show-rs", "keep-commented", "keep-discussed", "no-diff", "show-cp", "keep-modified");
  
  var pref = $.cookie('comparoPref');
  
  // Fonctions
  function prefUpdate() {
    var newPref = 0;
    for ( var i = 0; i < prefId.length; i++ ) {
      var element = $("#"+prefId[i]);
      var id = Math.pow(2,i);
      if ( element.length > 0 ) {
        if ( prefEnable(prefId[i]) ) {
          newPref += id;
        }
      }
      else {
        newPref += id & pref;
      }
    }
    
    pref = newPref;
    $.cookie('comparoPref', pref, { expires: 365 });
  }
  
  function prefEnable(id, enabled) {
    var element = $("#"+id);
    var attr = "checked"
  
    if ( enabled === undefined ) {
      return element.attr(attr);
    }
    else {
      if ( enabled ) 
        element.attr(attr, attr);
      else
        element.removeAttr(attr);
    }
  }
  
  
  for ( var i = 0; i < prefId.length; i++ ) {
    if ( pref === null ) { pref = (2+4+8+16+32+128+256+1024+2048+4096+8192+65536); }
    
    var id = Math.pow(2,i);
    prefEnable(prefId[i], id & pref);
    $("#"+prefId[i]).click(prefUpdate);
  }
    
});