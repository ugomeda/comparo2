$(document).ready(function() {
  $('.show').hover(function(){
    var parent = $(this).parents("li:first");
    var index = $("#liste-differences > li").index(parent);
    
    var lignes = new Array();
    var colors = new Array();
    
    var colors_disp = ["#edc240", "#afd8f8", "#cb4b4b", "#4da74d", "#9440ed"];
    var id_color = 0;
    
    $(this).attr("id", "details-"+index);
    
    // Content
    var top = "";
    for ( var i = 0; i < ids_s[index].length; i++ ) {
      top += "<span class=\"id_nonrelu\">"+ids_s[index][i]+"</span> ";
      top += "<span class=\"timing\">"+timing_to_tag(timing_s_s[index][i])+" --&gt; "+timing_to_tag(timing_s_e[index][i])+"</span><br />";
      lignes.push([[timing_s_s[index][i], 2], [timing_s_e[index][i], 2]]);
      
      colors.push(colors_disp[id_color]);
      id_color++;
      if ( id_color >= colors_disp.length ) id_color = 0;
    }
    
    if ( parent.hasClass("line-unk") || parent.hasClass("line-timing") ) {
      top += "<div style=\"width: 100%; height: 25px; margin: 2px 0;\" id=\"minigraph\"></div>";
    }
    else if ( ids_s[index].length > 0 && ids_r[index].length > 0 ) {
      top += "<br />";
    }
    
    
    for ( var i = 0; i < ids_r[index].length; i++ ) {
      top += "<span class=\"id_relu\">"+ids_r[index][i]+"</span> ";
      top += "<span class=\"timing\">"+timing_to_tag(timing_r_s[index][i])+" --&gt; "+timing_to_tag(timing_r_e[index][i])+"</span><br />";
      lignes.push([[timing_r_s[index][i], 1], [timing_r_e[index][i], 1]]);
      
      colors.push(colors_disp[id_color]);
      id_color++
      if ( id_color >= colors_disp.length ) id_color = 0;
    }
    
    // Sc
    for ( var i = 0; i < sc[index].length; i++ ) {
      lignes.push([[sc[index][i], 1], [sc[index][i], 2]]);
      colors.push("#444444");
    }
    
    if ( top == "" ) top = "Pas d'information disponible";
    
    JT_show(top,"details-"+index);
    
    if ( parent.hasClass("line-unk") || parent.hasClass("line-timing") ) {
      $.plot($("#minigraph"), lignes, {
        colors: colors,
        yaxis: {ticks: []},
        xaxis: {ticks: []},
        grid: {borderWidth: 0},
        shadowSize: 0
      });
    }
    
  },
  function() {
    $("#JT").remove();
  });
});


function timing_to_array(timing) {
  result = new Array();
  result['ms'] = Math.round((timing%1)*1000);
  result['sec'] = Math.floor(timing%60);
  result['min'] = Math.floor((timing/60)%60);
  result['hour'] = Math.floor(timing/3600);
  return result;
}

function format_int(number, length) {
  var result = ""+number+"";

  for ( var i = 1; i < length; i++ ) {
    if ( number < Math.pow(10, i) ) result = "0"+result;
  }
  return result;
}

function timing_to_tag(timing) {
  var data = timing_to_array(timing);
  
  return format_int(data['hour'],2)+":"+format_int(data['min'],2)+":"+format_int(data['sec'],2)+","+format_int(data['ms'],3);

}