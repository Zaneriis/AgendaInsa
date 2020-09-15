$('body').append('<a class="btn-floating" id="puce" href="#ancre_custom_2"><i class="material-icons">arrow_downward</i></a>');
$('head').append('<style>\
#puce{\
  display: none;\
}\
@media (max-width: 992px){\
  #puce{\
    display: block!important;\
    position: fixed!important;\
    bottom: 20px;\
    right: 20px;\
  }\
}</style>');




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
