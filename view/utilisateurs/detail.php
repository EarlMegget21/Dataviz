<?php
	if ($v !== FALSE && isset( $_SESSION[ "login" ] ) &&  ( ($_SESSION[ "isAdmin" ] == 1 ) || ($_SESSION["login"] == $v -> getLogin()) ) ) {
		{
			echo "<p>Login:" .
				$v -> getLogin()
				. "<br> MDP:"
				. $v -> getMdp()
				."<br><a href=index.php?controller=utilisateurs&action=update&"
				. ModelUtilisateurs ::getPrimary()
				. '='
				. rawurlencode( $v -> getLogin() )
				. ">Update</a> <a href=index.php?controller=utilisateurs&action=delete&"
				. ModelUtilisateurs ::getPrimary()
				. '='
				. rawurlencode( $v -> getLogin() )
				. ">Delete</a> <br>";
		}
	} else {
		require File ::build_path( array ( 'view', 'utilisateurs', 'error.php' ) );
	}
?>