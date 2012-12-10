$(document).ready(function() {
  var target = $("#apercu");
  target.html("");
  target.css("position", "relative");
  var canvas = $('<canvas width="500" height="20"></canvas>').appendTo(target).get(0);
  
  var ctx = canvas.getContext("2d");
  
  ctx.fillStyle = "rgb(200,0,0)";
  ctx.fillRect (0, 0, 130, 15);

  ctx.fillStyle = "rgb(255,106,0)";
  ctx.fillRect (100, 0, 130, 15);
  ctx.fillRect (300, 0, 100, 15);
  
  ctx.fillStyle = "rgb(0,127,14)";
  ctx.fillRect (230, 0, 70, 15);
});