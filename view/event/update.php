<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuog5LlTmtUH8-wB5IjxdJMY_Cq-CqhVU&language=fr&callback=initMap">
</script>

<?php
echo "<p id='test'></p>";
if ( isset($_GET[ModelEvent::getPrimary()])) {  //MàJ d'un event
    if(Conf::getDebug()) {
        echo "<form id='update' method='get' action='index.php'>
                    <fieldset>
                        <legend>Mettre a jour l'evenement :</legend>
				<p><input type='hidden' name='action' value='updated'>
				   <input type='hidden' name='controller' value='event'>";
    }else {
        echo "<form id='update' method='post' action='index.php?controller=event&action=updated'>
                    <fieldset>
                        <legend>Mettre a jour l'evenement :</legend>
				<p>";
    }
    echo "<label for=\"date_id\">Date</label> :
					<input type=\"date\" placeholder=\"Ex :00/00/00\" name=\"date\" id=\"date_id\" value=\"" . $v -> getDate () . "\" required/>
					
					<label for=\"longitude_id\">Coordonee X</label> :
					<input type=\"text\" placeholder=\"Ex :128\" name=\"longitude\" id=\"longitude_id\" value=\"" . $v -> getLongitude () . "\" readonly=\"readonly\" required/>
					
					<label for=\"latitude_id\">Coordonee Y</label> :
					<input type=\"text\" placeholder=\"Ex :256\" name=\"latitude\" id=\"latitude_id\" value=\"" . $v -> getLatitude () . "\" readonly=\"readonly\" required/>
					
					<label for=\"description_id\">Description</label></label> :
					<input type=\"text\" placeholder=\"Ex :Ceci est un reportage\" name=\"description\" id=\"description_id\" value=\"" . $v -> getDescription () . "\" />
					
					<label for='mp3_id'>MP3</label> :
					<input type='text' placeholder=\"Ex :http://loremipsum.fr/exemple.mp3\" name=\"mp3\" id=\"mp3_id\" value=\"" . $v -> getMP3 () . "\" />
					
					<label for='nom_id'>Nom</label> :
					<input type='text' placeholder='Ex :Developpement des écoles' name='nom' id='nom_id' value='" . $v -> getNom () . "' required/>
					
					
					<input type='hidden' name='login' value='".$v->getLogin()."'>
					<input type='hidden' name='id' value='".$v->getId()."'>
					</p>
				<p>
					<input type=\"submit\" value=\"Modifier\"/>
				</p>
			</fieldset>
		</form>";
} else {    //New event
    if(Conf::getDebug()) {
        echo "<form method='get' action='index.php'>
                    <fieldset>
                        <legend>Creer un evenement :</legend>
                <p><input type='hidden' name='action' value='created'>
                  <input type='hidden' name='controller' value='event'>";
    }else {
        echo "<form method='post' action='index.php?controller=event&action=created'>
                    <fieldset>
                        <legend>Creer un evenement :</legend>
				<p>";
    }
    echo "<label for=\"date_id\">Date :</label>
					<input type=\"date\" placeholder=\"Ex :00/00/00\" name=\"date\" id=\"date_id\" required/>
					
					<label for=\"longitude_id\">Coordonee X :</label>
					<input type=\"text\" placeholder=\"Ex :128\" name=\"longitude\" id=\"longitude_id\" readonly=\"readonly\" required/>
					
					<label for=\"latitude_id\">Coordonee Y :</label>
					<input type=\"text\" placeholder=\"Ex :256\" name=\"latitude\" id=\"latitude_id\" readonly=\"readonly\" required/>
					
					<label for=\"description_id\">Description :</label>
					<input type=\"text\" placeholder=\"Ex :Ceci est un reportage\" name=\"description\" id=\"description_id\"  />
					
					<label for=\"mp3_id\">MP3 :</label>
					<input type=\"text\" placeholder=\"Ex :http://loremipsum.fr/exemple.mp3\" name=\"mp3\" id=\"mp3_id\" />
					
					<label for=\"nom_id\">Nom :</label>
					<input type=\"text\" placeholder=\"Ex :Developpement des écoles\" name=\"nom\" id=\"nom_id\" required/>
					
					<input type='hidden' name='login' value='".$_SESSION["login"]."'>
					</p>
				<p>
					<input type=\"submit\" value=\"Créer\"/>
				</p>
			</fieldset>
		</form>";
}
//require_once File ::build_path ([ 'view' , 'event', 'ScriptAjoutEvent.php']);
?>
<div id="map"></div>
<script>

    var marker;

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 2,
            center: {lat: 0, lng: 0 }
        });

        /*marker = new google.maps.Marker({
            position: {lat: document.getElementById("latitude_id").value, lng: document.getElementById("longitude_id").value},
            map: map
        });*/

        //placeMarkerAndPanTo({lat: document.getElementById("latitude_id"), lng: document.getElementById("latitude_id")}, map);

       //TODO: Fix le code juste au dessus -> Si on update un event il faut qu'il s'affiche sur la carte et qu'on puisse modifier sa position en cliquant sur la carte

        map.addListener('click', function(e) {
            placeMarkerAndPanTo(e.latLng, map);
        });
    }

    function placeMarkerAndPanTo(latLng, map) {
        if(typeof marker !== 'undefined'){  //Détruit le précédent marqueur si on en avait placé un
            marker.setMap(null);
        }
        marker = new google.maps.Marker({
            position: latLng,
            map: map
        });
        document.getElementById("longitude_id").value = marker.getPosition().lat();
        document.getElementById("latitude_id").value = marker.getPosition().lng();
        map.panTo(latLng);
    }
</script>
