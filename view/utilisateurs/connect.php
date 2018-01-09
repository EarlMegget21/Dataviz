<?php
    if(Conf::getDebug()) {
        echo "<form method='get' action='index.php'>
            <p><input type='hidden' name='controller' value='utilisateurs'>
			<input type='hidden' name='action' value='connected'>";
    }else {
        echo "<form method='post' action='index.php?controller=utilisateurs&action=connected'>
            <p>";
    }
	echo "<label for='login_id'>Login :</label>
			<input type='text'  name='login' id='login_id_id' required/>
			<label for='mdp_id'>Mot de passe :</label>
			<input type='password' name='mdp' id='mdp_id' required>
        </p>
			<input type=\"submit\" value=\"Se Connecter\"/>
		</form>";
?>