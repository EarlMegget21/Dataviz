<?php
if ( isset($_GET[ModelUtilisateurs	::getPrimary () ]) &&(Session::is_user ($_GET[ "login" ])||Session::is_admin ())) {
    $v = ModelUtilisateurs ::select ( $_GET[ModelUtilisateurs::getPrimary () ] );
    if(Conf::getDebug()) {
        echo "<form method='get' action='index.php'>
                    <fieldset>
                        <legend>Mon formulaire :</legend>
			<p><input type='hidden' name='action' value='updated'>
				        <input type='hidden' name='controller' value='utilisateurs'>";
    }else {
        echo "<form method='post' action='index.php?controller=utilisateurs&action=updated'>
                    <fieldset>
                        <legend>Mon formulaire :</legend>
			<p>";
    }
    echo "
				<label for=\"mdp_id\">Mot de passe</label> :
				<input type=\"password\" name=\"mdp\" id=\"mdp_id\"/>
				
				<label for='mdp_conf_id'>Confirmer mot de passe</label>
				<input type='password' name='mdp_conf' id='mdp_conf_id'/>
				
				<input type='hidden' name='login' value='".$v->getLogin()."'>
				
			</p>";
    if(Session::is_admin()){
        echo "<label for='admin_id'>Administrateur </label>
                <input type='checkbox' value=1 name='isAdmin' id='admin_id' ";
        if($v->getIsAdmin()){
            echo "checked/>";
        }else{
            echo "/>";
        }
    }

    echo "<p>
				<input type=\"submit\" value=\"Envoyer\"/>
			</p>
		</fieldset>
		</form>";
} else if(Session::is_admin ()){
    if (Conf::getDebug()) {
        echo "<form method='get' action='index.php'>
                    <fieldset>
                        <legend>Mon formulaire :</legend>
			<p><input type='hidden' name='action' value='created'>
				<input type='hidden' name='controller' value='utilisateurs'>
		";
    } else {
        echo "<form method='post' action='index.php?controller=utilisateurs&action=created'>
                    <fieldset>
                        <legend>Mon formulaire :</legend>
			<p>";
    }
    echo "<label for=\"login_id\">Login</label> :
				<input type=\"text\" name=\"login\" id=\"login_id\" required/>
				
				<label for=\"marque_id\">Mot de passe</label> :
				<input type=\"password\" name=\"mdp\" id=\"marque_id\" required/>
				
				<label for='mdp_conf_id'>Confirmer mot de passe</label>
				<input type='password' name='mdp_conf' id='mdp_conf_id' required/>
			</p>";
    if (Session::is_admin()) {
        echo "<label for='admin_id'>Administrateur </label>
                <input type='checkbox' name='isAdmin' id='admin_id' ";
    }

    echo "<p>
				<input type=\"submit\" value=\"Envoyer\">
			</p>
		</fieldset>
		</form>";
} else{
    echo "404 Not Found! :)";
}
?>
