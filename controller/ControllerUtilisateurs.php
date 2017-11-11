<?php
	//Ctrl+H permet de remplacer les mots par un autre event->utilisateurs
	require_once File ::build_path( array ( 'model', 'ModelUtilisateurs.php' ) ); // chargement du modèle
	class ControllerUtilisateurs
	{


		public static function readAll ()
		{

			$tab_v = ModelUtilisateurs ::selectAll();     //appel au modèle pour gerer la BD
			$object = 'utilisateurs';
			$view = 'list';
			$pagetitle = 'Liste des utilisateurs';
			require( File ::build_path( [ 'view', 'view.php' ] ) );  //"redirige" vers la vue
		}


		public static function read ($primary)
		{
			if (isset( $_SESSION[ "login" ] ) && strcmp( $primary, $_SESSION[ "login" ] ) == 0) {
				$v = ModelUtilisateurs ::select( $primary );
				$object = 'utilisateurs';
				$view = 'detail';
				$pagetitle = 'Détail de l\'utilisateur.';
				require( File ::build_path( [ 'view', 'view.php' ] ) );  //"redirige" vers la vue
			} else {
				self ::readAll();
			}
		}


		public static function created ($data)
		{
			if (!isset( $_SESSION[ "login" ] )) {

				$data[ "isAdmin" ] = 0;
				ModelUtilisateurs ::save( $data );
				$tab_v = ModelUtilisateurs ::selectAll();
				$object = 'utilisateurs';
				$view = 'created';
				$pagetitle = 'Liste des utilisateurs';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else {
				self ::readAll();
			}
		}


		public static function update ()
		{
			if (isset( $_SESSION[ "login" ] )) {
				if (isset( $_GET[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $_GET[ "login" ], $_SESSION[ "login" ] ) == 0 )) {
					$object = 'utilisateurs';
					$view = 'update';
					$pagetitle = 'Utilisateur update';
					require( File ::build_path( [ 'view', 'view.php' ] ) );

				} else {
					self ::readAll();
				}
			} else {
				$object = 'utilisateurs';
				$view = 'update';
				$pagetitle = 'Utilisateur créée';
				require( File ::build_path( [ 'view', 'view.php' ] ) );

			}
		}


		public static function updated ($data)
		{
			if (isset( $_SESSION[ "login" ] ) && isset( $data[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $data[ "login" ], $_SESSION[ "login" ] ) == 0 )) {
				ModelUtilisateurs ::update( $data );
				$view = 'updated';
				$pagetitle = 'Admin updated';

			} else {
				$view = 'list';
				$pagetitle = 'Liste des utilisateurs';
			}
			$object = 'utilisateurs';

			$tab_v = ModelUtilisateurs ::selectAll();

			require( File ::build_path( [ 'view', 'view.php' ] ) );

		}


		public static function delete ($primary)
		{
			$isLogged=isset( $_SESSION[ "login" ] );
			if ( $isLogged&& strcmp( $primary, $_SESSION[ "login" ] ) == 0) {
				$object = 'utilisateurs';

				ModelUtilisateurs ::delete( $primary );
				$view = 'delete';
				$pagetitle = 'Utilisateur supprimé';
				$tab_v = ModelUtilisateurs ::selectAll();
				require( File ::build_path( [ 'view', 'view.php' ] ) );

			} elseif (!$isLogged) {

				$object = 'main';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require( File ::build_path( [ 'view', 'view.php' ] ) );

			} else {
				self::readAll();
			}

		}


	}
