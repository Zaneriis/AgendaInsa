$(document).ready(function(){
    $('.sidenav').sidenav();
    $(".dropdown-trigger").dropdown();
    $('.modal').modal();

    var formationsComplet = [];
    var sessionsComplet = [];

    $.ajax({
      url: "controleur/getAgendas.php",
      success: function(dataFormations) {

        dataFormations = JSON.parse(dataFormations);
        formationsComplet = JSON.parse(JSON.stringify(dataFormations)); //stockage global résultat de l'url

        $.each(dataFormations, function(key, value) {
          dataFormations[key] = null;
        });

        $('#autocomplete-input-formation').autocomplete({
          data: dataFormations
        });
      },
    });

    $.ajax({
      url: "controleur/getSession.php?fo=2020-ING-ASI-S7",
      success: function(dataSessions) {

          dataSessions = JSON.parse(dataSessions);
          sessionsComplet = JSON.parse(JSON.stringify(dataSessions));

          $.each(dataSessions, function(key, value) {
            dataSessions[key] = null;
          });

          $('#autocomplete-input-session').autocomplete({
            data: dataSessions
          });
      },
    });

    $('#validation-formation').click(function() {

      var valeurSaisie = $('#autocomplete-input-formation').val();
      if (formationsComplet.hasOwnProperty(valeurSaisie)) {
        console.log(formationsComplet[valeurSaisie]);
        location.assign("index.php?fo="+formationsComplet[valeurSaisie]);
      }
      else {
        alert("La valeur saisie ne correspond à aucun agenda repertorié, veuillez réessayer");
      }
    });

    $('#validation-session').click(function() {

      var valeurSaisie = $('#autocomplete-input-session').val();
      if (sessionsComplet.hasOwnProperty(valeurSaisie)) {
        location.assign("index.php?fo=2020-ING-ASI-S7&ss="+sessionsComplet[valeurSaisie]);
      }
      else {
        alert("La valeur saisie ne correspond à aucun étudiant, veuillez réessayer");
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
