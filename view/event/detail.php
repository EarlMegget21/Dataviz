<?php
	echo "Titre: "
		. htmlspecialchars( $v -> getNom() )
		. "<br>Date: "
		. htmlspecialchars( $v -> getDate() )
		. "<br>Coordonnée:<br>&nbsp&nbspLongitude:" //&nbsp fait une tabulation
		. htmlspecialchars( $v -> getLongitude() )
		. "<br>&nbsp&nbspLatitude:"
		. htmlspecialchars( $v -> getLatitude() )
		. "<br>Description:<br><br>"
		. htmlspecialchars( $v -> getDescription() )
		. "<br><br>Autheur :"
		. htmlspecialchars( $v -> getLogin() );

	if (isset( $_SESSION[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $v -> getLogin(), $_SESSION[ "login" ] ) == 0 )) {
		echo "<br><a href=index.php?controller=event&action=update&"
			. ModelEvent ::getPrimary()
			. '='
			. rawurlencode( $v -> getId() )
			. ">Update</a> <a href=index.php?controller=event&action=delete&"
			. ModelEvent ::getPrimary()
			. '='
			. rawurlencode( $v -> getId() )
			. ">Delete</a> <br>";
	}
?>