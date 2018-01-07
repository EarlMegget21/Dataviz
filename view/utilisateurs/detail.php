<?php
	if ($v !== FALSE && isset( $_SESSION[ "login" ] ) &&  ( ($_SESSION[ "isAdmin" ] == 1 ) || ($_SESSION["login"] == $v -> getLogin()) ) ) {
		{
            if($v->getIsAdmin()){
                echo "<p style='font-weight: bold'>Administrateur</p><br>";
            }
			echo "<p>Login: " .
				$v -> getLogin()
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