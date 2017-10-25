<?php
	echo "<form method=\"get\" action=\"index.php\">"; //TODO: Une fois finis changer le get en post
	if ( isset($_GET[ModelAdmin	::getPrimary () ]) ) {
		$v = ModelAdmin ::select ( $_GET[ModelAdmin::getPrimary () ] );

		echo "<fieldset>
	<legend>Mon formulaire :</legend>
	<p>
		<label for=\"marque_id\">Login</label> :
		<input type=\"text\" placeholder=\"Ex : XxDarkSasukeDu69KiDefonceToutxX\" name=\"login\" id=\"login_id\" value=\"" . $v -> getLogin () . "\" readonly/>
		
		<label for=\"mdp_id\">Mot de passe</label> :
		<input type=\"password\" placeholder=\"Ex :Gh;]Yv<ZKM_87^%E\" name=\"mdp\" id=\"mdp_id\" value=\"" . $v -> getMdp () . "\" required/>
		
		<input type='hidden' name='action' value='updated'>
		<input type='hidden' name='controller' value='admin'>
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
		<label for=\"login_id\">Login</label> :
		<input type=\"text\" placeholder=\"Ex : XxDarkSasukeDu69KiDefonceToutxX\" name=\"login\" id=\"login_id\" required/>
		
		<label for=\"marque_id\">Mot de passe</label> :
		<input type=\"password\" placeholder=\"Ex : Gh;]Yv<ZKM_87^%E\" name=\"marque\" id=\"marque_id\" required/>
		
		<input type='hidden' name='action' value='created'>
		<input type='hidden' name='controller' value='admin'>

	</p>
	<p>
		<input type=\"submit\" value=\"Envoyer\"/>
	</p>
</fieldset>
</form>
";
	}
?>
