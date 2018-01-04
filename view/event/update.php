<style>
    .hide{
        display: none;
    }
</style>

<?php
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
					
					<label class='hide' for=\"longitude_id\">Coordonee X</label> :
					<input class='hide' type=\"text\" placeholder=\"Ex :128\" name=\"longitude\" id=\"longitude_id\" value=\"" . $v -> getLongitude () . "\" readonly required/>
					
					<label class='hide' for=\"latitude_id\">Coordonee Y</label> :
					<input class='hide' type=\"text\" placeholder=\"Ex :256\" name=\"latitude\" id=\"latitude_id\" value=\"" . $v -> getLatitude () . "\" readonly required/>
					
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
					
					<label class='hide' for=\"longitude_id\">Coordonee X :</label>
					<input class='hide' type=\"text\" placeholder=\"Ex :128\" name=\"longitude\" id=\"longitude_id\" required/>
					
					<label class='hide' for=\"latitude_id\">Coordonee Y :</label>
					<input class='hide' type=\"text\" placeholder=\"Ex :256\" name=\"latitude\" id=\"latitude_id\" required/>
					
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
require_once File::build_path(array("view", "event", "ScriptAjoutEvent.php"));
?>
<div id="map"></div>

