/*
     *
     Fonction main qui se lance une fois au début au chargement de la page
     *
    */
$(function () { //diminutif JQuery de la fonction javascript de base document.ready(function{...}); s'appel donc une fois dès que la page est prète (genre de main() )
    Observable=new Subject();
    //on ajoute les observeurs qui seront mit à jour
    Observable.AddObserver(new MapObserver()); //la map doit être ajoutée en premier observeur!! car c'est dans sa fonction update que l'on met à jour le tableau de points qui sera parcouru par la liste
    Observable.AddObserver(new SilderObserver());
    Observable.AddObserver(new ListObserver());

    $("#slider").slider({ //créer le slider à l'endroit prévu
        range: true, //deux curseurs
        min: 1900, //valeur minimale
        max: 2050, //valeur maximale
        values: [1900, 2050], //valeurs de départ (à la création)
        stop: function (event, ui) { //listener qui se lance quand on drop le curseur du slider
            Observable.dates[0]=ui.values[0]; //on modifie l'intervalle de dates dans les attributs de l'observable
            Observable.dates[1]=ui.values[1];
            Observable.Notify(); //on indique à l'observable de mettre à jour tous ses observeurs()
        },
        slide: function (event, ui) { //genre de listener lors du slide (se lance tout au long du slide en temps réél)
            $("#date").html("Du 01/01/"+ui.values[0] +" au 31/12/"+ui.values[1]); //mise à jour de l'affichage des dates en direct
        }
    });

    document.getElementById("keywordButton_id").addEventListener("click", function(){
        Observable.keyword=document.getElementById("keyword_id").value;
        Observable.Notify();
    });

    $("#date").html("Du 01/01/"+$("#slider").slider("values", 0)+" au 31/12/"+$("#slider").slider("values", 1)); //initialisation de l'affichage des date au démarrage
    $(".ui-widget-header").css("background-color", "grey"); //CSS provisoir pour modifier les couleurs du slider
    $(".ui-widget-content").css("background", "#dddddd"); //CSS provisoir pour modifier les couleurs du slider
    $(".ui-state-default").css("background-color", "#5E5DFF"); //CSS provisoir pour modifier les couleurs du slider
});

/*
 *
 Fonction pour la map
 *
*/
function initMap() { //fonction qui créer la map qui sera appelée lorsque l'API sera chargée grâce au lien plus haut
    /*
     *
     toute les références à un objet googl.map. sont connus que dans la fonction Inimap, pas à l'exterieur!!
     *
    */
    map = new google.maps.Map(document.getElementById('map'), { //créer une map
        zoom: 1,
        minZoom: 1,
        maxZoom: 11,
        center: {lat: 0 , lng: 0},
        streetViewControl: false, //désactive l'HUD inutile
        mapTypeControl: false,
        rotateControl: false,
        fullscreenControl: false
    });

    /* La classe Point hérite de google.map.marker pour lui ajouter des attribut(les details de nos event):
        L'objet prototype de "google.maps.Marker" doit être copié au sein du prototype
         de "Point" afin que ce dernier puisse bénéficier des mêmes méthodes. */
    Point.prototype = Object.create(google.maps.Marker.prototype, {
        // Le prototype copié possède une référence vers son constructeur, actuellement
        // défini à "google.maps.Marker", nous devons changer sa référence pour "Point"
        // tout en conservant sa particularité d'être une propriété non-énumerable.
        constructor: {
            value: Point,
            enumerable: false,
            writable: true,
            configurable: true
        }
    });

    LatLong.prototype = Object.create(google.maps.LatLng.prototype, { //héritage de google.map.LatLng pour pouvoir créer cet objet à l'exterieur de InitMap()
        constructor: {
            value: LatLong,
            enumerable: false,
            writable: true,
            configurable: true
        }
    });

    infoWindow = new google.maps.InfoWindow; //création de l'infoWindows (petite fenetre)

    google.maps.event.addListener(map, 'dragstart', function(){
        dragStartCenter = map.getCenter(); //avant un drag on récupère le dernier centre pour le réstaurer en cas de sortie du cadre
    });

    google.maps.event.addListener(map, 'dragend', function() { //met à jour les points, le slider et la liste dès qu'on a fait glisser la map
        var lat=map.getCenter().lat();
        if (lat<minMaxLat[0]||lat>minMaxLat[1]){ //si le centre de la map sort du cadre, on la renrentre dedans
            map.setCenter(dragStartCenter);
        }
        /* On récupère les coordonnées des coins de la map */
        var bds = map.getBounds(); //objet de type LatLngBounds
        Observable.coordonnees[2] = bds.getSouthWest().lng(); //on stocks les coins de la map dans l'attribut de l'obsrvable
        Observable.coordonnees[0] = bds.getSouthWest().lat();
        Observable.coordonnees[3] = bds.getNorthEast().lng();
        Observable.coordonnees[1] = bds.getNorthEast().lat();

        Observable.Notify(); //on indique à l'observable de mettre à jour tous ses observeurs dès qu'on drag
    });

    google.maps.event.addListenerOnce(map, 'idle', function(){ //met à jour les points et la liste au chargement de la map(addListenerOnce est valable qu'une fois)
        /* On récupère les coordonnées des coins de la map */
        var bds = map.getBounds(); //objet de type LatLngBounds
        Observable.coordonnees[2] = bds.getSouthWest().lng();
        Observable.coordonnees[0] = bds.getSouthWest().lat();
        Observable.coordonnees[3] = bds.getNorthEast().lng();
        Observable.coordonnees[1] = bds.getNorthEast().lat();

        Observable.Notify(); //on indique à l'observable de mettre à jour tous ses observeurs dès que la map est chargée au début
    });

    google.maps.event.addListener(map, 'zoom_changed', function(){ //met à jour la valeur des coins si le zoom change
        /* On récupère les coordonnées des coins de la map */
        var bds = map.getBounds(); //objet de type LatLngBounds
        Observable.coordonnees[2] = bds.getSouthWest().lng();
        Observable.coordonnees[0] = bds.getSouthWest().lat();
        Observable.coordonnees[3] = bds.getNorthEast().lng();
        Observable.coordonnees[1] = bds.getNorthEast().lat();

        Observable.Notify(); //on indique à l'observable de mettre à jour tous ses observeurs dès qu'on zoom
    });
}

/*
 *
 Fonctions pour les points sur la map
 *
*/
function recupererPoints(reponseRequete){ //fonction qui rempli le tableau de points et créer les points sur la map en fonction de la réponse de la requête HTTP qui renvoie un doc XML (virtuel)
    var xml = reponseRequete.responseXML; //récupère le doc XML de la réponse
    var markers = xml.documentElement.getElementsByTagName('marker'); //récupère les tags XML 'marker' pour les mettre dans un tableau

    tab_minmax=["3000-01-01", "0000-12-31"]; //on reinitialise le tableau des min et max à chaque requête avec des valeurs hors du range pour pouvoir tester
    Array.prototype.forEach.call(markers, function (markerElem) { //pour chaque tag marker dans le tableau
        var id = markerElem.getAttribute('id'); //on récupère les attributs dans des variables
        var nom = markerElem.getAttribute('nom');
        var MP3 = markerElem.getAttribute('mp3');
        var description = markerElem.getAttribute('description');
        var date = markerElem.getAttribute('date');
        var login = markerElem.getAttribute('login');
        var lat=markerElem.getAttribute('lat');
        var long =markerElem.getAttribute('lng');
        var point = new LatLong( //créer un objet de type LatLong héritant de google.map.LatLng pour les coordonnées du point
            parseFloat(lat),
            parseFloat(long)
        );

        var infowincontent = creerInfoWindow(nom,date,login); //rempli la petite fenetre avec les details du point

        var marker = new Point(id,nom,MP3,description,login,point,date,map); //créer le point (hérite de google.map.marker)

        tab_marker.push(marker); //on ajoute chaque point au tableau des points

        if(date<tab_minmax[0]){ //si l'event contient la date min
            tab_minmax[0]=date;
        }
        if(date>tab_minmax[1]){ //si l'event contient la date max
            tab_minmax[1]=date;
        }

        marker.addListener('click', function () { //ajoute un handler lorsqu'on clique sur le point
            $('#detail').html('<h5>'+marker.nom+'</h5>'+ //on affiche les details du point dans la zone correspondante
                '<div><p>Date: '+marker.date+'</p>'+
                '<p>Auteur: '+marker.login+'</p>'+
                '<p>Latitude: '+marker.getPosition().lat()+'</p>'+
                '<p>Longitude: '+marker.getPosition().lng()+'</p>'+
                '<p>Description: '+marker.description+'</p></div>');
            $('#retour').css("display", "block"); //on fait apparaître le bouton retour pour revenir sur la liste
            infoWindow.setContent(infowincontent); //défini le contenu de la mini fenetre en y mettant le <div> créé plus haut
            infoWindow.open(map, marker); //ouvre cette mini fenêtre avec les details de l'event
        });
    });
}

function supprimerPoints() { //fonction qui supprime tous les points en enlevant tous les référencements à son adresse puis le garbage collector se chargera d'autoriser à écrire sur son adresse
    for (var i = 0; i < tab_marker.length; i++) {
        tab_marker[i].setMap(null);
    }
    tab_marker=[];
}

function creerInfoWindow(nom,date,login){ //fonction qui créer la petite fenêtre en fonction des données qu'on lui passe
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

    return infowincontent;
}

function recupererCom(reponseRequete){ //fonction qui rempli le tableau de points et créer les points sur la map en fonction de la réponse de la requête HTTP qui renvoie un doc XML (virtuel)
    var xml = reponseRequete.responseXML; //récupère le doc XML
    var comments = xml.documentElement.getElementsByTagName('comment'); //récupère les tags XML 'comment' pour les mettre dans un tableau

    Array.prototype.forEach.call(comments, function (commentElem) { //pour chaque tag comment dans le tableau
        var idCommentaire = commentElem.getAttribute('idCommentaire');
        var idEvent = commentElem.getAttribute('idEvent'); //on récupère les attributs dans des variables
        var login = commentElem.getAttribute('login');
        var texte = commentElem.getAttribute('texte');
        var note = commentElem.getAttribute('note');

        var comment=new Comment(idCommentaire,idEvent,login,texte,note);

        tab_comment.push(comment);
    });
}

/*
 *
 Fonctions AJAX
 *
 */
function getXhr(){ //créer la requête AJAX
    var xhr = null;
    if(window.XMLHttpRequest) //si le navigateur supporte (Firefox, Chrome et autres)
        xhr = new XMLHttpRequest(); //alors la requete est de type XMLHttpRequest
    else if(window.ActiveXObject){ //sinon de type ActiveXObject (Internet Explorer)
        try {
            xhr = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xhr = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    else { // XMLHttpRequest non supporté par le navigateur
        alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
        xhr = false;
    }
    return xhr
}

function getEventXML(mindate,maxdate,xa,ya,xb,yb,keyword){ //fonction qui permet de récupérer le doc XML sur le serveur grâce à AJAX, les parametres de recherche
    var xhr = getXhr(); //création de la requête
    xhr.onreadystatechange = function(){ // On défini ce qu'on va faire quand on aura la réponse (dès que l'état de la requête change)
        if(xhr.readyState == 4 && xhr.status == 200){ // On ne fait quelque chose que si on a tout reçu(code 4) et que le serveur est ok(200)
            supprimerPoints(); //on supprime tous les points
            recupererPoints(xhr); //on recréer tous les points correspondants à la recherche et on rempli le tableau de recherche
        }
    };
    var url="index.php?controller=event&action=searchEvents&mindate="+mindate+"&maxdate="+maxdate+"&xa="+xa+"&ya="+ya+"&xb="+xb+"&yb="+yb+"&keyword="+keyword;
    xhr.open("GET",url,false); //créer une requête HTTP Get avec cet url en synchrone(false) donc s'effectue en parallèle(genre de thread) car sinon les Observer sont mis à jour avant le tableau de recherche donc ont un résultat obsolète
    xhr.send(null); //envoie la requete avec aucun paramêtre
}

function getComXML(idEvent){ //fonction qui permet de récupérer le doc XML sur le serveur grâce à AJAX, les parametres de recherche
    var xhr = getXhr(); //création de la requête
    xhr.onreadystatechange = function(){ // On défini ce qu'on va faire quand on aura la réponse (dès que l'état de la requête change)
        if(xhr.readyState == 4 && xhr.status == 200){ // On ne fait quelque chose que si on a tout reçu(code 4) et que le serveur est ok(200)
            tab_comment=[]; //on supprime tous les points
            recupererCom(xhr); //on recréer tous les points correspondants à la recherche et on rempli le tableau de recherche
        }
    };
    var url="index.php?controller=event&action=searchComments&idEvent="+idEvent;
    xhr.open("GET",url,false); //créer une requête HTTP Get avec cet url en synchrone(false) donc s'effectue en parallèle(genre de thread) car sinon les Observer sont mis à jour avant le tableau de recherche donc ont un résultat obsolète
    xhr.send(null); //envoie la requete avec aucun paramêtre
}

/*
 *
 Création des classes:
 *
*/

/* Classes des objets extraits du XML */
function Point(id, nom, MP3, description, login, point, date, map) { //classe héritant de google.maps.Marker en lui ajoutant des attributs
    /* On appelle le constructeur de " google.maps.Marker " par le biais de la méthode
     call() afin qu'il affecte de nouvelles propriétés à " Point " */
    google.maps.Marker.call(this,{map: map, position: point}); // call(obj, parametres du constructeur pere) similaire à super(parametres) en java

    // Une fois le constructeur parent appelé, l'initialisation de notre objet peut continuer
    this.id = id;
    this.nom = nom;
    this.MP3 = MP3;
    this.description = description;
    this.login = login;
    this.date = date;
}

function LatLong(lat, long) { //classe héritant de google.maps.LatLng
    google.maps.LatLng.call(this, {lat: lat, lng: long});
}

function Comment(idCommentaire, idEvent, login, texte, note) { //objets qui stockeront nos commentaires
    this.idCommentaire=idCommentaire;
    this.idEvent = idEvent;
    this.login = login;
    this.texte = texte;
    this.note = note;
}

/* Classes du pattern Observable/Observer */
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
    this.dates = [1900,2050]; //stock les dates min et max du slider en temps reel
    this.keyword = "";
}

Subject.prototype.AddObserver = function( observer ){ //methode AddObserver(obj) pour ajouter un observeur
    this.observers.Add( observer );
};

Subject.prototype.Notify = function(){ //methode Notify() qui met à jour tous les observeurs
    var observerCount = this.observers.Count();
    for(var i=0; i < observerCount; i++){
        this.observers.Get(i).Update();
    }
};

// Les Observeurs
function MapObserver(){ //map qui se centrera en fonction des mots clés (peut être du slider aussi)

    this.Update = function(){
        getEventXML(Observable.dates[0],Observable.dates[1],Observable.coordonnees[2],Observable.coordonnees[0],Observable.coordonnees[3],Observable.coordonnees[1], Observable.keyword); //envoie la requête avec les paramêtres de l'Observable
    };

}

function SilderObserver(){ //slider

    this.Update = function(){ //on lui passe le tableau des résultats en parametre
        var minDate = parseInt(tab_minmax[0].slice(0, 4), 10);
        var maxDate = parseInt(tab_minmax[1].slice(0, 4), 10);
        if(minDate>2050){ //si il n'y a aucun évènement
            minDate=1900;
        }
        if(maxDate<1900){ //si il n'y a aucun évènement
            maxDate=2050;
        }
        $("#slider").slider('values',0,minDate); //set le curseur mini du slider
        $("#slider").slider('values',1,maxDate); //set le curseur maxi du slider
        $("#date").html("Du 01/01/"+minDate+" au 31/12/"+maxDate); //mise à jour de l'affichage des date
    };
}

function ListObserver(){ //liste

    this.Update = function(){ //met à jour la liste des events
        if(login!=null&&action!=null){
            switch (action){
                case "created":
                    $('#detail').html('<p>Evènement créé !</p>');
                    break;
                case "updated":
                    $('#detail').html('<p>L\'évènement a bien été mis à jour</p>');
                    break;
                case "delete":
                    if(model=='ModelCommentaire'){
                        $('#detail').html('<p>Le commentaire a bien été supprimé</p>');
                    }else{
                        $('#detail').html('<p>L\'évènement a bien été supprimé</p>');
                    }
                    break;
                default:
                    $('#detail').html('');
                    break;
            }
        }else{
            $('#detail').html('');
        }
        if(tab_marker.length!=0) { //si il y a des évènements on affiche la liste
            $('#detail').append('<ul>'); //création d'une liste
            for (var i = 0; i < tab_marker.length; i++) { //on affiche le nom de chaque marker avec un bouton pour voir les details
                $('#detail').append('<li>' + tab_marker[i].nom + ' <input type="button" id="list' + tab_marker[i].id + '" value="Voir>>" style="padding:0px 1px;"></li>');
                $('#list' + tab_marker[i].id).click(function (tab_marker, i) { //utilisation de closure (bien se renseigner sur le fonctionnement) car sinon l'index i dans le handler était égale au dernier i comme si les listener de chaque bouton se créaient après la boucle entière. J'aurais pu aussi mettre l'id du bouton = à l'index de l'event en queton puis récupérer cet id avec target (comme e.source en java)
                    return function () { //la fonction en parametre de click(), doit être fonction() pour se lancer à chaque event sinon se lance juste à la creation du listener. Ici, vu qu'on lui passe des paramêtres, on doit renvoyer une function() qui se lancera donc à chaque event
                        voirHandler(tab_marker, i) //les parametres passés sont bel et bien connu car on se trouve à l'interieur de l'autre fonction, on récupère donc ses parametres
                    }
                }(tab_marker, i)); //permet de passer tab_marker et i en parametre (notez la couleur de i)
            }
            $('#detail').append('</ul>'); //fin de la liste
        }else{ //si il n'y a pas d'évènement, on l'indique à l'utilisateur
            $('#detail').append('<p>Aucun évènement dans cette région à ce moment là...</p>');
        }
        if(login!=null){
            $('#detail').append('<a href="index.php?controller=event&action=update">Créer Evenement</a>'); //création d'un evenement
        }
        //on refait tout pour le listener du boutton retour
        $('#retour').click(function(){
            retourHandler(tab_marker);
        });
        $('#retour').css("display", "none"); //des qu'on click sur "<<Retour" on doit donc faire disparaitre le bouton
    };
}

/*
 *
 Handlers
 *
*/
function retourHandler(markers){ //fonction du handler pour les boutons <<Retour
    infoWindow.close(); //on ferme la petite fenetre au dessus du point
    if(login!=null&&action!=null){
        switch (action){
            case "created":
                $('#detail').html('<p>Evènement créé !</p>');
                break;
            case "updated":
                $('#detail').html('<p>L\'évènement a bien été mis à jour</p>');
                break;
            case "delete":
                if(model=='ModelCommentaire'){
                    $('#detail').html('<p>Le commentaire a bien été supprimé</p>');
                }else{
                    $('#detail').html('<p>L\'évènement a bien été supprimé</p>');
                }
                break;
            default:
                $('#detail').html('');
                break;
        }
    }else{
        $('#detail').html('');
    }
    $('#detail').append('<ul>'); //on recréer la liste des events
    for(var i=0;i<markers.length;i++){ //on affiche le nom et le bouton pour voir les details
        $('#detail').append('<li>'+markers[i].nom+' <input type="button" id="list'+markers[i].id+'" value="Voir>>" style="padding:0px 1px;"></li>');
        $('#list'+markers[i].id).click(function(markers, i){ //utilisation de closure (bien se renseigner sur le fonctionnement) car sinon l'index i dans le handler était égale au dernier i comme si les listener de chaque bouton se créaient après la boucle entière. J'aurais pu aussi mettre l'id du bouton = à l'index de l'event en queton puis récupérer cet id avec target (comme e.source en java)
            return function(){ //la fonction en parametre de click(), doit être fonction() pour se lancer à chaque event sinon se lance juste à la creation du listener. Ici, vu qu'on lui passe des paramêtres, on doit renvoyer une function() qui se lancera donc à chaque event
                voirHandler(markers, i) //les parametres passés sont bel et bien connu car on se trouve à l'interieur de l'autre fonction, on récupère donc ses parametres
            }
        }(markers,i));
    }
    $('#detail').append('</ul>'); //fin de la liste
    if(login!=null){
        $('#detail').append('<a href="index.php?controller=event&action=update">Créer Evenement</a>'); //création d'un evenement
    }
    $('#retour').css("display", "none"); //des qu'on click sur "<<Retour" on doit donc faire disparaitre le bouton
}

function voirHandler(markers, i) { //fonction du handler pour les boutons Voir>>
    var infowincontent = creerInfoWindow(markers[i].nom,markers[i].date,markers[i].login); //créer une petite fenêtre
    infoWindow.setContent(infowincontent); //défini le contenu de la mini fenetre en y mettant le <div> créé plus haut
    infoWindow.open(map, markers[i]); //ouvre cette mini fenêtre avec les details de l'event

    if(login!=null){
        if(login==markers[i].login || isAdmin){
            $('#detail').html('<a href="index.php?controller=event&action=update&id='+markers[i].id+'">Modifier</a><br>'+
                '<a href="index.php?controller=event&action=delete&model=ModelEvent&id='+markers[i].id+'">Supprimer</a>');
        }else{
            $('#detail').html('');
        }
    }else{
        $('#detail').html('');
    }

    $('#detail').append('<h5>' + markers[i].nom + '</h5>' + //affiche les details en cas de click
        '<div><p>Date: ' + markers[i].date + '</p>' +
        '<p>Audio: </p><audio controls><source src=\"' + markers[i].MP3 + '\" type="audio/mpeg"/></audio>'+
        '<p>Auteur: ' + markers[i].login + '</p>' +
        '<p>Latitude: ' + markers[i].getPosition().lat() + '</p>' +
        '<p>Longitude: ' + markers[i].getPosition().lng() + '</p>' +
        '<p>Description: ' + markers[i].description + '</p></div>');

    getComXML(markers[i].id);
    var com = false; //booleen pour savoir si il y a des commentaires
    $('#detail').append('<div style="border: solid;">'); //si on veut mettre les com dedans il faut lui mettre un id et faire append
    for (var y in tab_comment) { //on affiche les commentaires
        $('#detail').append('<p>Note:' + tab_comment[y].note + '/5</p>' +
            '<p>Commentaire:' + tab_comment[y].texte + '</p>' +
            '<p>Publié par:' + tab_comment[y].login + '</p>');
        if (login == tab_comment[y].login) {

            $('#detail').append('<a href="index.php?controller=event&action=delete&model=ModelCommentaire&idCommentaire=' + tab_comment[y].idCommentaire + '">Supprimer Commentaire</a>');
        }
        com = true;
    }
    if (!com) { //si il n'y en a pas, on affiche aucun
        $('#detail').append('<p>Aucun Commentaire</p>');
    }
    if(login!=null){
        var text;
        if(debug) {
            text = '<form method="get" action="index.php">' +
                '<fieldset>' +
                '<legend>Commenter :</legend>' +
                '<p><input type="hidden" name="action" value="comment">' +
                '<input type="hidden" name="controller" value="event">';
        }else{
            text = '<form method="post" action="index.php?controller=event&action=comment">' +
                '<fieldset>' +
                '<legend>Commenter :</legend>' +
                '<p>';
        }
        text+='<label for="note_id">Note</label>'+
            '<input type="number" step="1" min="0" max="5" name="note" id="note_id" value="0" required/>'+
            '<label for="texte_id">Votre Message</label>'+
            '<textarea placeholder="Laissez votre message ici !" name="texte" id="texte_id" rows="2" cols="30" required/></textarea>'+
            '<input type="hidden" name="login" value="'+login+'">'+
            '<input type="hidden" name="idEvent" value='+markers[i].id+'></p>'+
            '<p><input type="submit" value="Poster"/></p>'+
            '</fieldset>';

        $('#detail').append(text);
    }
    $('#retour').css("display", "block"); //apparition du bouton retour
}