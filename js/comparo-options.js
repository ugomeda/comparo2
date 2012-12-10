$(document).ready(function(){
  var opened = $.cookie('comparoOptions');
  
  if ( opened == 1 ) {
    $('#options').css("display", "block");
  }
  else {
    $('#switch-options').addClass('off');
    $('#options').css("display", "none");
  }
  
  $('#switch-options').click(function() {
    if ( opened == 1 ) {
      $('#options').hide("blind");
      $('#switch-options').addClass('off');
      opened = 0;
    }
    else {
      $('#options').show("blind");
      $('#switch-options').removeClass('off');
      opened = 1;
    }    
    $.cookie('comparoOptions', opened, { expires: 365 });
    return false;
  });
});