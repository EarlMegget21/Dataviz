<?php
	/**
	 * Created by PhpStorm.
	 * User: yves
	 * Date: 14/11/17
	 * Time: 09:37
	 */
	echo "<form>\n
			<p>\n
			<label for='login_id'>Login</label> :\n
			<input type='text'  name='login' id='login_id_id' required/>\n
			<label for='mdp_id'>Mot de passe</label>\n
			<input type='password' name='mdp' id='mdp_id' required>\n
			<input type='hidden' name='controller' value='utilisateurs'>
			<input type='hidden' name='action' value='connected'>\n
			</p>\n
			<input type=\"submit\" value=\"Connect\"/>\n
		</form>\n";
?>