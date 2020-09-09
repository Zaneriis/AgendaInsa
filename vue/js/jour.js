$(document).ready(function(){
  $('.tooltipped').tooltip();

  $('.col-event').hover(
     function(){ $(this).addClass('z-depth-3') },
     function(){ $(this).removeClass('z-depth-3') }
   )
});
