<?php
	echo "<form method=\"get\" action=\"index.php\">"; //TODO: Une fois finis changer le get en post
	if ( isset($_GET[ModelUtilisateurs	::getPrimary () ]) &&(Session::is_user ($_GET[ "login" ])||Session::is_admin ())) {
		$v = ModelUtilisateurs ::select ( $_GET[ModelUtilisateurs::getPrimary () ] );

		echo "<fieldset>
	<legend>Mon formulaire :</legend>
	<p>
		<label for=\"mdp_id\">Mot de passe</label> :
		<input type=\"password\" placeholder=\"Ex :Gh;]Yv<ZKM_87^%E\" name=\"mdp\" id=\"mdp_id\" required/>
		
		<label for='mdp_conf_id'>Confirmer mot de passe</label>
		<input type='password' placeholder='Ex :Gh;]Yv<ZKM_87^%E\' name='mdp_conf' id='mdp_conf_id' required/>
		
		<input type='hidden' name='login' value='".$v->getLogin()."'>
		<input type='hidden' name='action' value='updated'>
		<input type='hidden' name='controller' value='utilisateurs'>
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
		<input type=\"password\" placeholder=\"Ex : Gh;]Yv<ZKM_87^%E\" name=\"mdp\" id=\"marque_id\" required/>
		
		
		<label for='mdp_conf_id'>Confirmer mot de passe</label>
		<input type='password' placeholder='Ex :Gh;]Yv<ZKM_87^%E\' name='mdp_conf' id='mdp_conf_id' required/>
		
		<input type='hidden' name='action' value='created'>
		<input type='hidden' name='controller' value='utilisateurs'>

	</p>
	<p>
		<input type=\"submit\" value=\"Envoyer\"/>
	</p>
</fieldset>
</form>
";
	}
?>
