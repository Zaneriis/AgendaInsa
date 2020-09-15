$(document).ready(function(){
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown();
    $('.modal').modal();

    var formationsComplet = [];

    $.ajax({
      url: "controleur/getAgendas.php",
      success: function(data) {
        console.log(data);
        data = JSON.parse(data);
        console.log(data);
        formationsComplet = JSON.parse(JSON.stringify(data)); //stockage global résultat de l'url

        $.each(data, function(key, value) {
          data[key] = null;
        });

        $('input.autocomplete').autocomplete({
          data: data,
        });
      },
    });

    $('#validation-formation').click(function() {
      console.log(formationsComplet);
      var valeurSaisie = $('#autocomplete-input').val();
      if (formationsComplet.hasOwnProperty(valeurSaisie)) {
        console.log(formationsComplet[valeurSaisie]);
        location.assign("index.php?fo="+formationsComplet[valeurSaisie]);
      }
      else {
        alert("La valeur saisie ne correspond à aucun agenda repertorié, veuillez réessayer");
      }
    });

    //console.log(formations);

    /*$.get("controleur/getAgendas.php").done(function(formations) {
      console.log(formations);
      $('input.autocomplete').autocomplete({
        data: formations,
      })
    });*/

});
