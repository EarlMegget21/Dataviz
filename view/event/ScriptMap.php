<!-- <script src="/Dataviz/script/sliderScript.js"> //script pour le slider </script> -->
<script async defer
        src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCuog5LlTmtUH8-wB5IjxdJMY_Cq-CqhVU&language=fr&callback=initMap">
</script> <!-- include l'API Javascript grâce à notre Clé, async pour asynchrone donc la map sera chargée(InitMap() terminée) puis le code reprendra son court != en même temps -->

<script>
    /*
     *
     Toutes les déclarations à l'exterieur des fonctions pour être accessibles de partout
     *
    */
    var tab_marker=[]; //déclaration du tableau qui contiendra les event qui correspondront aux recherches
    var tab_comment;
    var tab_minmax=["3000-01-01", "0000-12-31"]; //tableau contenant les dates min et max, on l'initialise avec des valeurs hors du range pour pouvoir tester
    var map; //déclaration de la map
    var infoWindow; //déclaration d'une mini fenetre qui s'affichera avec les infos de l'event
    var Observable; //déclaration de l'observable
    var login=<?php if(isset($_SESSION['login'])){echo "'".$_SESSION['login']."'";}else{echo "null";}?>; //variable qui stock le login de la personne connectée
    var action=<?php if(isset($_GET['action'])){echo "'".$_GET['action']."'";}else{echo "null";}?>;
    var model=<?php if(isset($_GET['model'])){echo "'".$_GET['model']."'";}else{echo "null";}?>;
    var isAdmin=<?php if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']){ echo 1; }else{ echo 0;}?>;
    var debug=<?php if(Conf::getDebug()){ echo "true"; }else{ echo "false";}?>;
    var dragStartCenter; //variable contenant le denier centre de la map pour pouvoir la recentrer en cas de sortie du cadre
    var minMaxLat=[-80, 80]; //maximum et minimum de latitude pour le centre de la map
</script>

<script src="./script/map.js">
      // importe les fonction pour créer la map et les marqueurs
</script>