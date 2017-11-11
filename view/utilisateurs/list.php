<?php
	foreach ($tab_v as $v) {
		echo "<p>\nLogin: <a href=index.php?controller=utilisateurs&action=read&" . ModelUtilisateurs ::getPrimary() . "=" . rawurlencode( $v -> getLogin() ) . ">" . htmlspecialchars( $v -> getLogin() ) . "</a>\n</p>\n";
	}
	if (!isset( $_SESSION[ "login" ] )) {
		echo "<br>\n
				<a href=\"index.php?controller=utilisateurs&action=update\">Créer un compte</a>";
    }
?>

