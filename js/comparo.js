var timing_min = false;
var timing_max = false;

function restrict_lines() {
  $("#error").remove();

  // Options daffichage
  var show_ok = $("#show-ok").is(':checked');
  var show_edit = $("#show-edit").is(':checked');
  var show_timing = $("#show-timing").is(':checked');
  var show_ins = $("#show-ins").is(':checked');
  var show_del = $("#show-del").is(':checked');
  var show_unk = $("#show-unk").is(':checked');
  var keep_com = $("#keep-commented").is(':checked');
  var keep_dis = $("#keep-discussed").is(':checked');
  var keep_mod = $("#keep-modified").is(':checked');
  
  // Gestion quick-filters
  if ( show_ok ) { $("#quick-ok").attr("src", "img/tick.png"); }
  else { $("#quick-ok").attr("src", "img/tick-n.png"); }
  
  if ( show_edit ) { $("#quick-edit").attr("src", "img/pencil.png"); }
  else { $("#quick-edit").attr("src", "img/pencil-n.png"); }
  
  if ( show_timing ) { $("#quick-timing").attr("src", "img/time.png"); }
  else { $("#quick-timing").attr("src", "img/time-n.png"); }
  
  if ( show_ins ) { $("#quick-ins").attr("src", "img/add.png"); }
  else { $("#quick-ins").attr("src", "img/add-n.png"); }
  
  if ( show_del ) { $("#quick-del").attr("src", "img/cancel.png"); }
  else { $("#quick-del").attr("src", "img/cancel-n.png"); }
  
  if ( show_unk ) { $("#quick-unk").attr("src", "img/clock_edit.png"); }
  else { $("#quick-unk").attr("src", "img/clock_edit-n.png"); }
  
  var i = 0;
  var count = 0;
  
  $("#liste-differences > li").each(function(){
    var line = $(this);
    if ( ( timing_min === false && timing_max === false ) || (times[i] >= timing_min && times[i] <= timing_max) ) {      
      // Options daffichage
      //TRUE = il faut cacher
      var cond_ok = ( ! show_ok && line.hasClass("line-ok") );
      var cond_edit = ( ! show_edit && line.hasClass("line-edit") );
      var cond_timing = ( ! show_timing && line.hasClass("line-timing") );
      var cond_ins = ( ! show_ins && line.hasClass("line-ins") );
      var cond_del = ( ! show_del && line.hasClass("line-del") );
      var cond_unk = ( ! show_unk && line.hasClass("line-unk") );
      
      // True = Il faut garder
      var cond_com = ( line.hasClass("commented") && keep_com );
      var cond_dis = ( line.hasClass("discussed") && keep_dis );
      var cond_mod = ( line.hasClass("modifed") && keep_mod );
      
      if ( ( cond_ok || cond_edit || cond_timing || cond_ins || cond_del || cond_unk ) && ! cond_com && ! cond_dis && ! cond_mod ) {
        line.css("display", "none");
      }
      else {
        line.css("display", "block");
        count++;
      }
    }
    else {
      line.css("display", "none");
    }
    i++;
  });
 
  if ( count == 0 ) {
    $("#liste-differences").append("<li id=\"error\"><span>Aucune ligne ne correspond à la sélection</span></li>");
  }
}


$(document).ready(function() {
  var plot = null;
  
  //Graph initialisation
  plot = $.plot($("#graph"), [ { data: graph_modif }, { data: graph_normal }],
                        {colors: ["#bbbbbb", "#CE6123"], 
                         xaxis: { tickFormatter: function (v, axis) { return secondsToString(v.toFixed(axis.tickDecimals)); }},
                         yaxis: {ticks: [], min: 0}, selection: { mode: "x", color: "#cccccc" }});

  // Graph (un)selection
  $("#graph").bind("selectioncleared", function () {
     $("#selection").stop(true, true);
     timing_min = false;
     timing_max = false;
     $("#selection").css("display", "none");
     restrict_lines();
  });

  $("#graph").bind("plotselected", function(event, ranges) {
     $("#selection").stop(true, true); 
     timing_min = Math.floor(ranges.xaxis.from.toFixed(1));
     timing_max = Math.ceil(ranges.xaxis.to.toFixed(1));
     
     if ( timing_min < 5 ) timing_min = 0;
     timing_max += 5;
     
     $("#selection").html("<img src=\"img/timeline_marker.png\" width=\"16\" height=\"16\" alt=\"\" /> Les timings ont été restreints de <strong>"+secondsToString(timing_min)+"</strong> à <strong>"+secondsToString(timing_max)+"</strong>");
     $("#selection").css("display", "block").effect("highlight", {}, 1000);
     restrict_lines();
  });
  
  // Copy text
  $("#liste-differences > li").not(".line-timing").not(".line-ok").each(function() {
    var line = $(this);
    var text = line.children(".text");
    var text_html = text.addClass("text_orig").html();
    var text_2 = document.createElement("span");
    $(text_2).html(text_html).insertAfter(text).addClass("text").addClass("text_2").css("display", "none");
  });

  // Quick buttons
  $("#quick-ok").css("cursor", "pointer").click(function(){ changevalue($("#show-ok")); return false; });
  $("#quick-edit").css("cursor", "pointer").click(function(){ changevalue($("#show-edit")); return false; });
  $("#quick-timing").css("cursor", "pointer").click(function(){ changevalue($("#show-timing")); return false; });
  $("#quick-ins").css("cursor", "pointer").click(function(){ changevalue($("#show-ins")); return false; });
  $("#quick-del").css("cursor", "pointer").click(function(){ changevalue($("#show-del")); return false; });
  $("#quick-unk").css("cursor", "pointer").click(function(){ changevalue($("#show-unk")); return false; });

  // Filters buttons
  $("#show-ok, #show-edit, #show-timing, #show-ins, #show-del, #show-unk, #keep-commented, #keep-discussed, #keep-modified").click(restrict_lines);
  restrict_lines();

  // No diff button
  $("#no-diff").click(function() {
    if ( $(this).is(":checked") ) 
      $("#liste-differences").addClass("no-diff");
    else 
      $("#liste-differences").removeClass("no-diff");
  }).triggerHandler("click");
  
  // Mode diff button
  $("#mode-diff1, #mode-diff2").click(function() {
    var mode = $("input[@name='mode-diff'][@checked]").val();
    if ( mode == "1" ) {
      $(".text_orig").removeClass("text_1");
      $(".text_2").css("display", "none");
    }
    else {
      $(".text_orig").addClass("text_1");
      $(".text_2").css("display", "block");
    } 
  }).triggerHandler("click");
  
  // Show graph button
  $("#show-graph").click(function() {
    if ( $(this).is(":checked") ) 
      $("#graph").css("display", "block");  
    else {
      $("#graph").css("display", "none"); 
      plot.clearSelection();
    }
  }).triggerHandler("click");
  
  // Show id button
  $("#show-id").click(function() {
    if ( $(this).is(":checked") ) 
      $("#liste-differences").removeClass("hide-id");
    else
      $("#liste-differences").addClass("hide-id");
  }).triggerHandler("click");

  // Show CP button
  $("#show-cp").click(function() {
    if ( $(this).is(":checked") ) 
      $("#liste-differences").removeClass("hide-cp");
    else
      $("#liste-differences").addClass("hide-cp");
  }).triggerHandler("click");

  // Show rs button
  $("#show-rs").click(function(){
    if ( $(this).is(':checked') )
      $(".rs").css("display", "inline");  
    else
    $(".rs").css("display", "none"); 
  }).triggerHandler("click");
  
  // Show vo button
  $("#show-vo").click(function() {
    if ( $(this).is(':checked') )
      $(".vo").css("display", "block");
    else
      $(".vo").css("display", "none");
  }).triggerHandler("click");
  

});

/* UTILS */
function secondsToString(sec) {
  var total = sec;
  var secondes = Math.floor(total%60);
  var minutes = Math.floor(((total-secondes)/60)%60);
  var heures = Math.floor((total-(60*minutes)-secondes)/3600);

  if ( secondes < 10 ) secondes = "0"+secondes;
  if ( minutes < 10 ) minutes = "0"+minutes;

  if ( heures > 0 ) {
    return heures+":"+minutes+":"+secondes;
  }
      
  return minutes+":"+secondes;
}
  
function changevalue(element) {
  if ( element.is(":checked") )
    element.removeAttr("checked");
  else
    element.attr("checked", "checked");
  
  element.triggerHandler("click");
}