function initMap() { //fonction qui créer la map
    var map = new google.maps.Map(document.getElementById('map'), { //créer une map
        zoom: 1,
        center: {lat: 0 , lng: 0},
        streetViewControl: false,
        mapTypeControl: false,
        rotateControl: false,
        fullscreenControl: false
    });
    // Display the area between the location southWest and northEast.
    map.fitBounds(bounds);

    var infoWindow = new google.maps.InfoWindow; //créer une mini fenetre qui s'affichera avec les infos de l'event
    downloadUrl('http://dataviz.yvesdaniel.fr/xml/points.xml', function(data) { //appel pour récupérer les infos dans le XML et créer des points
        var xml = data.responseXML; //récupère le doc XML
        var markers = xml.documentElement.getElementsByTagName('marker'); //récupère les tags XML 'marker' pour les mettre dans un tableau
        Array.prototype.forEach.call(markers, function (markerElem) { //pour chaque tag marker dans le tableau
            var id = markerElem.getAttribute('id'); //on récupère les attributs dans des variables
            var nom = markerElem.getAttribute('nom');
            var description = markerElem.getAttribute('description');
            var date = markerElem.getAttribute('date');
            var login = markerElem.getAttribute('login');
            var point = new google.maps.LatLng( //créer un objet de type LatLng pour le point
                parseFloat(markerElem.getAttribute('lat')),
                parseFloat(markerElem.getAttribute('lng')));

            var infowincontent = document.createElement('div'); //créer un tag <div> html

            var strong = document.createElement('strong'); //créer un <strong> avec le nom
            strong.textContent = nom;
            infowincontent.appendChild(strong);
            infowincontent.appendChild(document.createElement('br'));//y ajoute une enfant <br>

            var lien = document.createElement('a'); //créer un <a> menant aux details
            lien.setAttribute("href", "http://localhost/Dataviz/index.php?controller=event&action=read&id="+id);
            lien.textContent = 'en savoir plus';
            infowincontent.appendChild(lien);
            infowincontent.appendChild(document.createElement('br'));

            var text = document.createElement('text'); //créer un <text> avec la date
            text.textContent = date;
            infowincontent.appendChild(text);

            var marker = new google.maps.Marker({ //créer le marker (point)
                map: map,
                position: point
            });

            marker.addListener('click', function () { //ajoute un handler lorsqu'on clique sur le point
                $('#detail').html('<h3>'+nom+'</h3><p>'+description+'</p><p>Date des faits:'+date+'</p><p>Publié par:'+login+'</p>'); //rempli le <div id="detail">
                infoWindow.setContent(infowincontent); //défini le contenu de la mini fenetre en y mettant le <div> créé plus haut
                infoWindow.open(map, marker); //ouvre cette mini fenêtre avec les details de l'event
            });
        });
    });
    /* Ici, on ajoute l'écouteur d'événement suite à un glisser / déposer  */
    google.maps.event.addListener(map, 'dragend', function() { //met à jour la valeur des coins si on fait glisser la map
        /* On récupère les coordonnées des coins de la map */
        var bds = map.getBounds(); //objet de type LatLngBounds
        var y2 = bds.getSouthWest().lat();
        var x1 = bds.getSouthWest().lng();
        var y1 = bds.getNorthEast().lat();
        var x2 = bds.getNorthEast().lng();
        $("#sub").on("click", function() { //handler JQuery pour le click sur Submit: ça rempli les champs de recherche
            $("#longitude_id1").val(x1);
            $("#longitude_id2").val(x2);
            $("#latitude_id1").val(y1);
            $("#latitude_id2").val(y2);
            $('#zoom').val(map.getZoom());
        });
        //callAjax(South_Lat,South_Lng,North_Lat,North_Lng);
    });

    /*function callAjax(slt,slg,nlt,nlg){
        var sendAjax = $.ajax({
            type: "POST",
            url: 'retrievePoints.php',
            data: 'SO_Lt='+slt+'&SO_lg='+slg+'&NE_lt='+nlt+'&NE_lg='+nlg,
            success: handleResponse
    });

     //  Cette fonction est sensée retourner un ensemble de points à afficher sur la carte
    function handleResponse(){
        TRAITEMENT DE LA REPONSE AJAX:
            "sendAjax.responseText" OU "sendAjax.responseXML";
    }*/

    google.maps.event.addListenerOnce(map, 'idle', function(){ //met à jour la valeur des coins si on fait rien (au chargement de la map)
        /* On récupère les coordonnées des coins de la map */
        var bds = map.getBounds();
        var y2 = bds.getSouthWest().lat();
        var x1 = bds.getSouthWest().lng();
        var y1 = bds.getNorthEast().lat();
        var x2 = bds.getNorthEast().lng();
        $("#sub").on("click", function() {
            $("#longitude_id1").val(x1);
            $("#longitude_id2").val(x2);
            $("#latitude_id1").val(y1);
            $("#latitude_id2").val(y2);
            $('#zoom').val(map.getZoom());
        });
    });

    google.maps.event.addListenerOnce(map, 'zoom_changed', function(){ //met à jour la valeur des coins si le zoom change
        /* On récupère les coordonnées des coins de la map */
        var bds = map.getBounds();
        var y2 = bds.getSouthWest().lat();
        var x1 = bds.getSouthWest().lng();
        var y1 = bds.getNorthEast().lat();
        var x2 = bds.getNorthEast().lng();
        $("#sub").on("click", function() {
            $("#longitude_id1").val(x1);
            $("#longitude_id2").val(x2);
            $("#latitude_id1").val(y1);
            $("#latitude_id2").val(y2);
            $('#zoom').val(map.getZoom());
        });
    });
}

function downloadUrl(url, callback) { //fonction qui permet de récupérer le doc XML , callback est une fonction anonyme qui s'exiécute lors de l'appel
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
