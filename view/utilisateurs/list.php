<?php
	foreach ($tab_v as $v) {
		echo "<p>\nLogin: <a href=index.php?controller=utilisateurs&action=read&" . ModelUtilisateurs ::getPrimary() . "=" . rawurlencode( $v -> getLogin() ) . ">" . htmlspecialchars( $v -> getLogin() ) . "</a>\n</p>\n";
	}
	if (!isset( $_SESSION[ "login" ] )) {
		echo "<br>\n
				<a href=\"index.php?controller=utilisateurs&action=update\">Créer un compte</a>";
    }

?>
<form method="get" action="index.php">
	<fieldset>
		<legend>Generate users :</legend>
		<p>
			<label for="n_id">Number</label> :
			<input type="number"  name="n" id="n_id" required/>

			<input type='hidden' name='action' value='generate'>
			<input type='hidden' name='controller' value='utilisateurs'>
		</p>
		<p>
			<input type="submit" value="Generate"/>
		</p>
	</fieldset>
</form>

