<?php
	echo "<form method=\"get\" action=\"index.php\">"; //TODO: Une fois finis changer le get en post
	if ( isset($_GET[ModelEvent::getPrimary () ]) ) {
		$v = ModelEvent ::select ( $_GET[ModelEvent::getPrimary () ] );

		echo "<fieldset>
	<legend>Mon formulaire :</legend>
	<p>
		
		<label for=\"date_id\">Date</label> :
		<input type=\"date\" placeholder=\"Ex :00/00/00\" name=\"date\" id=\"date_id\" value=\"" . $v -> getDate () . "\" required/>
		
		<label for=\"longitude_id\">Coordonee X</label> :
		<input type=\"text\" placeholder=\"Ex :128\" name=\"longitude\" id=\"longitude_id\" value=\"" . $v -> getLongitude () . "\" required/>
		
		<label for=\"latitude_id\">Coordonee Y</label> :
		<input type=\"text\" placeholder=\"Ex :256\" name=\"latitude\" id=\"latitude_id\" value=\"" . $v -> getLatitude () . "\" required/>
		
		<label for=\"description_id\">Description</label></label> :
		<input type=\"text\" placeholder=\"Ex :Ceci est un reportage\" name=\"description\" id=\"description_id\" value=\"" . $v -> getDescription () . "\" />
		
		<label for=\"mp3_id\">MP3</label> :
		<input type=\"text\" placeholder=\"Ex :http://loremipsum.fr/exemple.mp3\" name=\"mp3\" id=\"mp3_id\" value=\"" . $v -> getMP3 () . "\" />
		
		<label for=\"nom_id\">Nom</label> :
		<input type=\"text\" placeholder=\"Ex :Developpement des écoles\" name=\"nom\" id=\"nom_id\" value=\"" . $v -> getNom () . "\" required/>
		
		
		<input type='hidden' name='login' value='".$v->getLogin()."'>
		<input type='hidden' name='id' value='".$v->getId()."'>
		<input type='hidden' name='action' value='updated'>
		<input type='hidden' name='controller' value='event'>
	</p>
	<p>
		<input type=\"submit\" value=\"Envoyer\"/>
	</p>
</fieldset>
</form>";
	} else {
		echo "
<fieldset>
	<legend>Mon formulaire :</legend>
	<p>
	
		<label for=\"date_id\">Date</label> :
		<input type=\"date\" placeholder=\"Ex :00/00/00\" name=\"date\" id=\"date_id\" required/>
		
		<label for=\"longitude_id\">Coordonee X</label> :
		<input type=\"text\" placeholder=\"Ex :128\" name=\"longitude\" id=\"longitude_id\"  required/>
		
		<label for=\"latitude_id\">Coordonee Y</label> :
		<input type=\"text\" placeholder=\"Ex :256\" name=\"latitude\" id=\"latitude_id\" required/>
		
		<label for=\"description_id\">Description</label></label> :
		<input type=\"text\" placeholder=\"Ex :Ceci est un reportage\" name=\"description\" id=\"description_id\"  />
		
		<label for=\"mp3_id\">MP3</label> :
		<input type=\"text\" placeholder=\"Ex :http://loremipsum.fr/exemple.mp3\" name=\"mp3\" id=\"mp3_id\" />
		
		<label for=\"nom_id\">Nom</label> :
		<input type=\"text\" placeholder=\"Ex :Developpement des écoles\" name=\"nom\" id=\"nom_id\" required/>
		
		
		
		<input type='hidden' name='login' value='".$_SESSION["login"]."'>
		<input type='hidden' name='action' value='created'>
		<input type='hidden' name='controller' value='event'>

	</p>
	<p>
		<input type=\"submit\" value=\"Envoyer\"/>
	</p>
</fieldset>
</form>
";
	}
?>
