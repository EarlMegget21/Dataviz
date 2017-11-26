<!-- <script src="/Dataviz/script/sliderScript.js"> //script pour le slider </script> -->
<script async defer
        src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCuog5LlTmtUH8-wB5IjxdJMY_Cq-CqhVU&language=fr&callback=initMap"></script> <!-- include l'API Javascript grâce à notre Clé -->
<!-- <script src="./script/mapScript.js">
                        // importe les fonction pour créer la map et les marqueurs

                    </script> -->
<script>
    //toutes les déclarations à l'exterieur des fonctions pour être accessibles de partout
    var tab_search=[]; //déclaration du tableau qui contiendra les event correspondants aux critères de recherhe
    var tab_marker=[]; //déclaration du tableau qui contiendra tous les event pour les stocker dans tab_search quand ils correspondront
    var map; //déclaration de la map
    var Observable=new Subject(); //déclaration de l'observable
    var login=<?php if(isset($_SESSION['login'])){echo "\"".$_SESSION['login']."\"";}else{echo "\"none\"";}?>;

    function initMap() { //fonction qui créer la map
        map = new google.maps.Map(document.getElementById('map'), { //créer une map
            zoom: 1,
            center: {lat: 0 , lng: 0},
            streetViewControl: false, //désactive l'HUD inutile
            mapTypeControl: false,
            rotateControl: false,
            fullscreenControl: false
        });

        var infoWindow = new google.maps.InfoWindow; //créer une mini fenetre qui s'affichera avec les infos de l'event
        downloadUrl('http://localhost/dataviz/xml/points.xml', function(data) { //appel pour récupérer les infos dans le XML et créer des points
            var xml = data.responseXML; //récupère le doc XML
            var markers = xml.documentElement.getElementsByTagName('marker'); //récupère les tags XML 'marker' pour les mettre dans un tableau

            /* L'objet prototype de " google.maps.Marker " doit être copié au sein du prototype
             de " Point " afin que ce dernier puisse bénéficier des mêmes méthodes. */
            Point.prototype = Object.create(google.maps.Marker.prototype, {
                // Le prototype copié possède une référence vers son constructeur, actuellement
                // défini à " google.maps.Marker ", nous devons changer sa référence pour " Point "
                // tout en conservant sa particularité d'être une propriété non-énumerable.
                constructor: {
                    value: Point,
                    enumerable: false,
                    writable: true,
                    configurable: true
                }
            });

            Array.prototype.forEach.call(markers, function (markerElem) { //pour chaque tag marker dans le tableau
                var id = markerElem.getAttribute('id'); //on récupère les attributs dans des variables
                var nom = markerElem.getAttribute('nom');
                var description = markerElem.getAttribute('description');
                var date = markerElem.getAttribute('date');
                var login = markerElem.getAttribute('login');
                var lat=markerElem.getAttribute('lat');
                var long =markerElem.getAttribute('lng');
                var point = new google.maps.LatLng( //créer un objet de type LatLng pour le point
                    parseFloat(lat),
                    parseFloat(long)
                );

                var infowincontent = document.createElement('div'); //créer un tag <div> html

                var strong = document.createElement('strong'); //créer un <strong> avec le nom
                strong.textContent = nom;
                infowincontent.appendChild(strong);
                infowincontent.appendChild(document.createElement('br'));//y ajoute une enfant <br>

                var text = document.createElement('text'); //créer un <text> avec la date
                text.textContent = date;
                infowincontent.appendChild(text);
                infowincontent.appendChild(document.createElement('br'));

                var lien = document.createElement('text'); //créer un <text> avec le login de l'auteur
                lien.textContent = login;
                infowincontent.appendChild(lien);

                var marker = new Point(id,nom,description,login,point,date,map); //créer le point (herite de marker)

                tab_marker.push(marker); //on ajoute chaque point au tableau total des points

                marker.addListener('click', function () { //ajoute un handler lorsqu'on clique sur le point
                    $('#detail').html('<h5>'+marker.nom+'</h5>'+ //on affiche les details du point dans la zone correspondante
                        '<div><p>Date: '+marker.date+'</p>'+
                        '<p>Auteur: '+marker.login+'</p>'+
                        '<p>Latitude: '+marker.getPosition().lat()+'</p>'+
                        '<p>Longitude: '+marker.getPosition().lng()+'</p>'+
                        '<p>Description: '+marker.description+'</p></div>');
                    $('#retour').css("display", "block"); //on fait apparître le bouton retour pour revenir sur la liste
                    infoWindow.setContent(infowincontent); //défini le contenu de la mini fenetre en y mettant le <div> créé plus haut
                    infoWindow.open(map, marker); //ouvre cette mini fenêtre avec les details de l'event
                });
            });
        });
        /* Ici, on ajoute l'écouteur d'événement suite à un glisser / déposer  */
        google.maps.event.addListener(map, 'dragend', function() { //met à jour les points, le slider et la liste dès qu'on a fait glisser la map
            /* On récupère les coordonnées des coins de la map */
            var bds = map.getBounds(); //objet de type LatLngBounds
            Observable.coordonnees[2] = bds.getSouthWest().lng(); //on stocks les coins de la map dans l'attribut de l'obsrvable
            Observable.coordonnees[0] = bds.getSouthWest().lat();
            Observable.coordonnees[3] = bds.getNorthEast().lng();
            Observable.coordonnees[1] = bds.getNorthEast().lat();

            search(); //dès qu'on drag, on refait une recherche

        });

        google.maps.event.addListenerOnce(map, 'idle', function(){ //met à jour les points et la liste au chargement de la map(addListenerOnce est valable qu'une fois)
            /* On récupère les coordonnées des coins de la map */
            var bds = map.getBounds(); //objet de type LatLngBounds
            Observable.coordonnees[2] = bds.getSouthWest().lng();
            Observable.coordonnees[0] = bds.getSouthWest().lat();
            Observable.coordonnees[3] = bds.getNorthEast().lng();
            Observable.coordonnees[1] = bds.getNorthEast().lat();
            search(); //dès que la map est chargée, on refait une recherche
        });

        google.maps.event.addListener(map, 'zoom_changed', function(){ //met à jour la valeur des coins si le zoom change
            /* On récupère les coordonnées des coins de la map */
            var bds = map.getBounds(); //objet de type LatLngBounds
            Observable.coordonnees[2] = bds.getSouthWest().lng();
            Observable.coordonnees[0] = bds.getSouthWest().lat();
            Observable.coordonnees[3] = bds.getNorthEast().lng();
            Observable.coordonnees[1] = bds.getNorthEast().lat();

            search(); //dès qu'on zoom, on refait une recherche
        });
    }

    function downloadUrl(url, callback) { //fonction qui permet de récupérer le doc XML , callback est une fonction anonyme qui s'exiécute lors de l'appel de downloadURL
        var request = window.ActiveXObject ? //si
            new ActiveXObject('Microsoft.XMLHTTP') : //alors
            new XMLHttpRequest; //sinon

        request.onreadystatechange = function() { //dès que l'état de la requête change fait:
            if (request.readyState == 4) { //si l'etat c'est 4
                request.onreadystatechange = doNothing; //finalement ça fait rien
                callback(request, request.status); //appel la fonction callback
            }
        };

        request.open('GET', url, true); //récupère le fichier?
        request.send(null);
    }

    function doNothing() {} //fonction qui fait rien

    function search(){ //fonction qui rempli le tableau de recherches et met à jour tous les observeurs
        longmin=Observable.coordonnees[2]; //on récupère les critères actuels qui étaient stockés dans les tableaux attributs de l'observable
        longmax=Observable.coordonnees[3];
        latmin=Observable.coordonnees[0];
        latmax=Observable.coordonnees[1];
        mindate=Observable.dates[0];
        maxdate=Observable.dates[1];
        tab_search.length=0; //on réinitialise le tableau de recherche
        if(longmin>longmax){ // si on est de l'autre côté de la Terre
            for (var i in tab_marker) { //pour chaque event on test si date est dans les créneaux du slider et coordonnees dans la map
                if (parseInt(tab_marker[i].date.split("-")[0]) >= mindate && //peut etre qu'on a pas besoin de faire toute cette transformation en int de l'année pour la comparaison
                    parseInt(tab_marker[i].date.split("-")[0]) <= maxdate &&
                    tab_marker[i].getPosition().lat() > latmin &&
                    tab_marker[i].getPosition().lat() < latmax &&
                    (tab_marker[i].getPosition().lng() > longmin ||
                        tab_marker[i].getPosition().lng() < longmax)) {
                    tab_search.push(tab_marker[i]); //si c'est bon, on l'ajoute au tableau de resultat
                    tab_marker[i].setMap(map); //on le réaffiche sur la map si il etait caché
                }else{
                    tab_marker[i].setMap(null); //sinon on le cache
                }
            }
        }else{ //si on est du bon côté de la Terre
            for (var i in tab_marker) { //pareil
                if (parseInt(tab_marker[i].date.split("-")[0]) >= mindate &&
                    parseInt(tab_marker[i].date.split("-")[0]) <= maxdate &&
                    tab_marker[i].getPosition().lat() > latmin &&
                    tab_marker[i].getPosition().lat() < latmax &&
                    tab_marker[i].getPosition().lng() > longmin &&
                    tab_marker[i].getPosition().lng() < longmax) {
                    tab_search.push(tab_marker[i]);
                    tab_marker[i].setMap(map);
                }else{
                    tab_marker[i].setMap(null);
                }
            }
        }
        Observable.Notify(tab_search); //enfin avec le tableau de resultats et les marker affichés, on indique à l'observable de mettre à jour tous ses observeurs
    }

    function Point(id, nom, description, login, point, date, map) { //classe héritant de google.maps.Marker en lui ajoutant des attributs
        /* On appelle le constructeur de " google.maps.Marker " par le biais de la méthode
         call() afin qu'il affecte de nouvelles propriétés à " Point " */
        google.maps.Marker.call(this,{map: map, position: point}); // call(obj, parametres du constructeur pere) similaire à super(parametres) en java

        // Une fois le constructeur parent appelé, l'initialisation de notre objet peut continuer
        this.id = id;
        this.nom = nom;
        this.description = description;
        this.login = login;
        this.date = date;
    }

    $(function () { //diminutif JQuery de la fonction javascript de base document.ready(function{...}); s'appel donc une fois dès que la page est prète (genre de main() )
        //on ajoute les observeurs qui seront mit à jour
        Observable.AddObserver(new MapOberver());
        Observable.AddObserver(new SilderOberver());
        Observable.AddObserver(new ListOberver());

        $("#slider").slider({ //créer le slider à l'endroit prévu
            range: true, //deux curseurs
            min: 1900, //valeur minimale
            max: 2017, //valeur maximale
            values: [1900, 2017], //valeurs de départ (à la création)
            stop: function (event, ui) {
                Observable.dates[0]=ui.values[0]; //on modifie l'intervalle de dates dans les attributs de l'observable
                Observable.dates[1]=ui.values[1];
                search(); //on fait un recherche pour voir les points et la liste se mettre à jour en temps réél (pas obligatoire, à modifier peut-être pour de l'optimisation)
            },
            slide: function (event, ui) { //genre de listener lors du slide (se lance tout au long du slide en temps réél)

                $("#date").html("Du 01/01/"+ui.values[0] +" au 31/12/"+ui.values[1]); //mise à jour de l'affichage des dates
            }
        });

        $("#date").html("Du 01/01/"+$("#slider").slider("values", 0)+" au 31/12/"+$("#slider").slider("values", 1)); //initialisation de l'affichage des date au démarrage
        $(".ui-widget-header").css("background-color", "grey"); //CSS provisoir pour modifier les couleurs du slider
        $(".ui-widget-content").css("background", "#dddddd");
        $(".ui-state-default").css("background-color", "#5E5DFF");

    });

    //Création des classes
    function ObserverList(){  //création de la classe de l'attribut de l'observable contenant la liste des observeurs
        this.observerList = [];
    }

    ObserverList.prototype.Add = function( obj ){ //methode Add(obj) pour ajouter
        return this.observerList.push( obj );
    };

    ObserverList.prototype.Count = function(){ //methode Count() pour le nombre
        return this.observerList.length;
    };

    ObserverList.prototype.Get = function( index ){ //methode Get(i) pour recupérer à l'indice i
        if( index > -1 && index < this.observerList.length ){
            return this.observerList[ index ];
        }
    };

    //La classe de l'observable
    function Subject(){
        this.observers = new ObserverList(); //liste des observeurs
        this.coordonnees = [-90,90,-180,180]; //stock les coordonnees de la carte en temps reel
        this.dates = [1900,2017]; //stock les dates min et max du slider en temps reel
    }

    Subject.prototype.AddObserver = function( observer ){ //methode AddObserver(obj) pour ajouter un observeur
        this.observers.Add( observer );
    };

    Subject.prototype.Notify = function( context ){ //methode Notify() qui met à jour tous les observeurs
        var observerCount = this.observers.Count();
        for(var i=0; i < observerCount; i++){
            this.observers.Get(i).Update( context );
        }
    };

    // Les Observeurs
    function MapOberver(){ //map qui se centrera en fonction des mots clés (peut être du slider aussi)

        this.Update = function(markers){
            for(var i in markers){
                window.console.log("");
                //rien tant qu'il n'y a pas les mots clé
            }
        };

    }

    function SilderOberver(){ //slider

        this.Update = function(markers){ //on lui passe le tableau des résultats en parametre
            var minDate = 5000; //on initialise à un grand nombre pour les tests
            var maxDate = 0; //on initialise à un petit nombre pour les tests
            for (var i in markers) { //pour chaque marker correspondant
                if (markers[i].date > maxDate + "-31-12") { //si la date est maximale alors on la stock dans l'attribut de l'observable
                    maxDate = parseInt(markers[i].date.slice(0, 4), 10);
                }
                if (markers[i].date < minDate + "-01-01") { //si la date est minimale alors on la stock dans l'attribut de l'observable
                    minDate = parseInt(markers[i].date.slice(0, 4), 10);
                }
            }
        };
    }

    function ListOberver(){ //liste

        this.Update = function(markers){ //met à jour la liste des events
            <?php
            if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1 && isset( $_SESSION[ "action" ] )) {
                switch ($_SESSION[ "action" ]) {
                    case "updated": //ne s'affiche pas??
                        echo "$('#detail').html('<p>L\'evenement à bien été mis à jour</p>');";
                        break;
                    case "delete":
                        echo "$('#detail').html('<p>L\'evenement à bien été supprimé</p>');";
                        break;
                    default:
                        echo "$('#detail').html('');";
                        break;
                }
            }else{
                echo "$('#detail').html('');";
            }
            ?>
            $('#detail').append('<ul>'); //création d'une liste
            for(var i in markers){ //on affiche le nom de chaque marker avec un bouton pour voir les details
                $('#detail').append('<li>'+markers[i].nom+' <input type="button" id="list'+markers[i].id+'" value="Voir>>" style="padding:0px 1px;"></li>');
                $('#list'+markers[i].id).click(function(){ //définition du listener de chaque bouton
                    <?php
                    if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1) {
                        echo "if(login==markers[i].login){
                                        $('#detail').html('<a href=\"index.php?controller=event&action=update&id='+markers[i].id+'\">Modifier</a><br>'+
                                        '<a href=\"index.php?controller=event&action=delete&id='+markers[i].id+'\">Supprimer</a>');
                                      }else{
                                            $('#detail').html('');
                                      }";
                    }else{
                        echo "$('#detail').html('');";
                    }
                    ?>
                    $('#detail').append('<h5>'+markers[i].nom+'</h5>'+ //affiche les details en cas de click
                        '<div><p>Date: '+markers[i].date+'</p>'+
                        '<p>Auteur: '+markers[i].login+'</p>'+
                        '<p>Latitude: '+markers[i].getPosition().lat()+'</p>'+
                        '<p>Longitude: '+markers[i].getPosition().lng()+'</p>'+
                        '<p>Description: '+markers[i].description+'</p></div>');
                    $('#retour').css("display", "block"); //apparition du bouton retour
                });
            }
            $('#detail').append('</ul>'); //fin de la liste
            <?php
            if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1) {
                echo "$('#detail').append('<a href=\"index.php?controller=event&action=update\">Créer Evenement</a>');"; //création d'une liste
            }
            ?>
            $('#retour').click(function(){ //listener du boutton retour
                <?php
                if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1 && isset( $_SESSION[ "action" ] )) {
                    switch ($_SESSION[ "action" ]) {
                        case "updated":
                            echo "$('#detail').html('<p>L\'evenement à bien été mis à jour</p>');";
                            break;
                        case "delete":
                            echo "$('#detail').html('<p>L\'evenement à bien été supprimé</p>');";
                            break;
                        default:
                            echo "$('#detail').html('');";
                            break;
                    }
                }else{
                    echo "$('#detail').html('');";
                }
                ?>
                $('#detail').append('<ul>'); //on recréer la liste des events
                for(var i in markers){ //on affiche le nom et le bouton pour voir les details
                    $('#detail').append('<li>'+markers[i].nom+' <input type="button" id="list'+markers[i].id+'" value="Voir>>" style="padding:0px 1px;"></li>');
                    $('#list'+markers[i].id).click(function(){ //listener du bouton "Voir>>"
                        <?php
                        if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1) {
                            echo "if(login==markers[i].login){
                                        $('#detail').html('<a href=\"index.php?controller=event&action=update&id='+markers[i].id+'\">Modifier</a><br>'+
                                        '<a href=\"index.php?controller=event&action=delete&id='+markers[i].id+'\">Supprimer</a>');
                                      }else{
                                            $('#detail').html('');
                                      }";
                        }else{
                            echo "$('#detail').html('');";
                        }
                        ?>
                        $('#detail').append('<h5>'+markers[i].nom+'</h5>'+
                            '<div><p>Date: '+markers[i].date+'</p>'+
                            '<p>Auteur: '+markers[i].login+'</p>'+
                            '<p>Latitude: '+markers[i].getPosition().lat()+'</p>'+
                            '<p>Longitude: '+markers[i].getPosition().lng()+'</p>'+
                            '<p>Description: '+markers[i].description+'</p></div>');
                        $('#retour').css("display", "block"); //réaparition du bouton "<<Retour" si on click sur "Voir>>"
                    });
                }
                $('#detail').append('</ul>'); //fin de la liste
                $('#detail').append('<a href="index.php?controller=event&action=update">Créer Evenement</a>'); //création d'une liste
                $('#retour').css("display", "none"); //des qu'on click sur "<<Retour" on doit donc faire disparaitre le bouton
            });
        };
    }
</script>