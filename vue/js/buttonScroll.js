$(document).scroll(function(){
  var height = $(document).height();
  var offset = $(document).scrollTop();
  var relativeOffset = offset/height*100;
  var incetitude = 10;
  var nombre_bontons = 5;

  var nBlock = Math.floor((relativeOffset+incetitude) / (100/nombre_bontons) +1);
  if(nBlock == nombre_bontons){
    $('#puce').attr("href","#ancre_custom_1");
    $("#puce i").text("arrow_upward");
  }else{
    $('#puce').attr("href","#ancre_custom_"+(nBlock+1));
    $("#puce i").text("arrow_downward");
  }
});
