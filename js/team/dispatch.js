$(document).ready(function(){
  var values = [];
  var total = 0;

  /* Displays a msg if no dispatch */
  function nojobmsg() {
    $("#nojob").remove();
    if ( values.length == 0 ) {
      $("#jobs").append('<li id="nojob">Aucune partie n\'est définie.</li>');
    }
  }
  
  if ( mode == 1 ) {
    $("#dispatch_lines").removeAttr("checked");
    $("#dispatch_timing").attr("checked", "checked");
  }
  else {
    $("#dispatch_timing").removeAttr("checked");
    $("#dispatch_lines").attr("checked", "checked");
  }
  
  /* Change mode */
  $("#dispatch_lines, #dispatch_timing").click(function() {
    if ( $("#dispatch_lines").is(":checked") && mode == 1 ) {
      mode = 0;
      $("#jobs .job").each(function() {
        var dispatchid = $(this).data("dispatchid");
        var from = Math.round(values[dispatchid][0]*100 / timing);
        var to = Math.round(values[dispatchid][1]*100 / timing);
        createSlider(100, from, to, $(this).find(".slider"));
        sliding($(this).attr("id"), from, to);
      });
    }
    else if ( ! $("#dispatch_lines").is(":checked") && mode == 0 ) {
      mode = 1;
      $("#jobs .job").each(function() {
        var dispatchid = $(this).data("dispatchid");
        var from = Math.round(values[dispatchid][0]*timing / 100);
        var to = Math.round(values[dispatchid][1]*timing / 100);
        
        createSlider(timing, from, to, $(this).find(".slider"));
        
        sliding($(this).attr("id"), from, to);
      });
    } 
    
    $("#mode").val(mode); 
  });
  
  
  /* Adds a line */
  function add_dispatch(uid, username, from, to) {
    /* DOM + Slider */
    var job = $('<div class="job"><span class="delete"></span><span class="user"></span><div class="slider"></div><span class="display"></span><input type="hidden" name="job_uid[]" value="'+uid+'" /><input type="hidden" name="job_from[]" value="" /><input type="hidden" name="job_to[]" value="" /></div>');
    job.find(".user").text(username);
    $("#jobs").append(job);
    job.attr("id", "dispatch_"+total).data("dispatchid", values.length);
    
    if ( mode == 0 ) {
      createSlider(100, from, to, job.find(".slider"));
    }
    else {
      createSlider(timing, from, to, job.find(".slider"));
    }
    
    /* Array */
    values.push([from, to]);
    sliding("dispatch_"+total, from, to);
    total++;
    
    /* Delete */
    job.find(".delete").click(function() {
      var id = $(this).parent().data("dispatchid");
      values.splice(id,1);
      $("#jobs .job").each(function() {
        var dispatchid = $(this).data("dispatchid");
        if ( dispatchid > id ) {
          $(this).data("dispatchid", dispatchid-1);
        }
      });
      $(this).parent().remove();
      check_dispatch();
      nojobmsg();
    });
  }

  /* Sets inputs values and displays */
  function sliding(id, from, to) {
    var job = $("#"+id)
    if ( mode == 0 ) {
      job.children(".display").html(from+"% à "+to+"%");
    }
    else {
      if ( parseInt(to) == timing ) 
        job.children(".display").html(from+":00 à la fin");
      else
        job.children(".display").html(from+":00 à "+to+":00");
    }
    
    job.children("input:eq(1)").val(from);
    job.children("input:eq(2)").val(to);
    values[job.data("dispatchid")] = [from, to];
  }


  /* Checks errors on dispatch */
  function check_dispatch() {
    error_chevauchement = false;
    error_vide = false;
    
    data = values.slice(0);
    data.sort(function(a,b){return a[0]-b[0]});
    
    last = 0;
    for ( j = 0; j < data.length; j++ ) {
      if ( data[j][1] - data[j][0] > 0 ) { 
        if ( data[j][0] < last ) {
          error_chevauchement = true;
        }
        if ( data[j][0] > last ) {
          error_vide = true;
        }
        
        if ( data[j][1] >= last )
          last = data[j][1];
      }
    } 
    if ( mode == 0 ) {
      if ( last != 100 && last != 0 ) error_vide = true;
    }
    if ( mode == 1 ) {
      if ( last != timing && last != 0 ) error_vide = true;
    }
   
    if ( error_chevauchement && error_vide )
      $("#alert_dispatch").text("Certains dispatchs se chevauchent et des parties ne sont attribuées à personne").css("visibility", "visible");
    else if ( error_chevauchement )
      $("#alert_dispatch").text("Certains dispatchs se chevauchent").css("visibility", "visible");
    else if ( error_vide )
      $("#alert_dispatch").text("Des parties ne sont attribuées à personne").css("visibility", "visible")
    else
      $("#alert_dispatch").css("visibility", "hidden");
  }


  /* Auto-repartition button */
  $("#equivalent").click(function() {
    var i = 0;
    if ( mode == 0 ) {
      var part = 100 / values.length;
    }
    if ( mode == 1 ) {
      var part = timing / values.length;
    }
    
    
    var before = 0;
    $(".job").each(function() {
      var slid = $(this).children(".slider");
      
      var start = before;
      before = Math.round((i+1)*part);
      
      slid.slider("values", 0, start);
      slid.slider("values", 1, before);
      sliding($(this).attr('id'), start, before);
      i++;
    });
    check_dispatch();
    return false;
  });
  
  
  /* Add user button */
  $("#user").change(function() {
    var select = $("#user option:selected");
    if ( select.val() != "" ) {
      add_dispatch(select.val(), select.text(), 0, 0);
      nojobmsg();
      check_dispatch();
    }
    $("#user option:first").attr("selected", "selected");
    return false;
  });
  
  
  /* Destroys the slider and creates a new one with new configuration */
  function createSlider(max, from, to, jElement) {
    jElement.slider("destroy");
    jElement.attr("style", ""); // Sinon il y a des problèmes au changement de mode
    jElement.html("");
    jElement.slider({
      range: true,
      min: 0,
      max: max,
      values: [from, to],
      slide: function(event, ui) {
        sliding($(this).parent().attr("id"), ui.values[0], ui.values[1]);
      },
      change: check_dispatch
    });
  }
  
  /* INITIALIZING */
  $("#jobs").sortable({ handle : ".user" });
  for ( i = 0; i < dispatch.length; i++ ) {
    add_dispatch(dispatch[i][0], dispatch[i][1], dispatch[i][2], dispatch[i][3]);
  }
  nojobmsg();
  check_dispatch();
  
  /* Enable submit button and mode */
  $("#sendbutton").removeAttr("disabled");
  $("#mode").val(mode);
});
