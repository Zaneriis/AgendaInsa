$(document).ready(function(){
  $('.tooltipped').tooltip();


  $('.depth-on-hover').hover(
     function(){ $(this).addClass('z-depth-3') },
     function(){ $(this).removeClass('z-depth-3') }
   )
});
