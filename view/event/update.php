<?php
	echo "<form method=\"get\" action=\"index.php\">"; //TODO: Une fois finis changer le get en post
	if ( isset($_GET[ModelEvent::getPrimary () ]) ) {
		$v = ModelEvent ::select ( $_GET[ModelEvent::getPrimary () ] );

		echo "<fieldset>
	<legend>Mon formulaire :</legend>
	<p>
		<label for=\"id\">id</label> :
		<input type=\"text\" placeholder=\"Ex : 1\" name=\"id\" id=\"id\" value=\"" . $v -> getId () . "\" readonly/>
		
		<label for=\"date_id\">Date</label> :
		<input type=\"date\" placeholder=\"Ex :00/00/00\" name=\"date\" id=\"date_id\" value=\"" . $v -> getDate () . "\" required/>
		
		<label for=\"coordonneeX_id\">Coordonee X</label> :
		<input type=\"text\" placeholder=\"Ex :128\" name=\"coordonneeX\" id=\"coordonneeX_id\" value=\"" . $v -> getCoordonneeX () . "\" required/>
		
		<label for=\"coordonneeY_id\">Coordonee Y</label> :
		<input type=\"text\" placeholder=\"Ex :256\" name=\"coordonneeY\" id=\"coordonneeY_id\" value=\"" . $v -> getCoordonneeY () . "\" required/>
		
		<label for=\"description_id\">Description</label></label> :
		<input type=\"text\" placeholder=\"Ex :Ceci est un reportage\" name=\"description\" id=\"description_id\" value=\"" . $v -> getDescription () . "\" />
		
		<label for=\"mp3_id\">MP3</label> :
		<input type=\"text\" placeholder=\"Ex :http://loremipsum.fr/exemple.mp3\" name=\"mp3\" id=\"mp3_id\" value=\"" . $v -> getMP3 () . "\" />
		
		<label for=\"nom_id\">Nom</label> :
		<input type=\"text\" placeholder=\"Ex :Developpement des écoles\" name=\"nom\" id=\"nom_id\" value=\"" . $v -> getNom () . "\" required/>
		
		
		<label for=\"login_id\">Login du créateur</label> :
		<input type=\"text\" placeholder=\"Ex :XxDarkSasukeDu69KiDefonceToutxX\" name=\"login\" id=\"login_id\" value=\"" . $v -> getLogin () . "\" readonly/>
		
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
		
		<label for=\"coordonneeX_id\">Coordonee X</label> :
		<input type=\"text\" placeholder=\"Ex :128\" name=\"coordonneeX\" id=\"coordonneeX_id\"  required/>
		
		<label for=\"coordonneeY_id\">Coordonee Y</label> :
		<input type=\"text\" placeholder=\"Ex :256\" name=\"coordonneeY\" id=\"coordonneeY_id\" required/>
		
		<label for=\"description_id\">Description</label></label> :
		<input type=\"text\" placeholder=\"Ex :Ceci est un reportage\" name=\"description\" id=\"description_id\"  />
		
		<label for=\"mp3_id\">MP3</label> :
		<input type=\"text\" placeholder=\"Ex :http://loremipsum.fr/exemple.mp3\" name=\"mp3\" id=\"mp3_id\" />
		
		<label for=\"nom_id\">Nom</label> :
		<input type=\"text\" placeholder=\"Ex :Developpement des écoles\" name=\"nom\" id=\"nom_id\" required/>
		
		
		<label for=\"login_id\">Login du créateur</label> :
		<input type=\"text\" placeholder=\"Ex :XxDarkSasukeDu69KiDefonceToutxX\" name=\"login\" id=\"login_id\"  required/>
		
		
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
