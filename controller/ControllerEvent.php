<?php
	require_once File ::build_path( array ( 'model', 'ModelEvent.php' ) ); // chargement du modèle
	class ControllerEvent
	{

		public static function readAll ()
		{

			$tab_v = ModelEvent ::selectAll();     //appel au modèle pour gerer la BD
			$object = 'event';
			$view = 'list';
			$pagetitle = 'Liste des events';
			require( File ::build_path( [ 'view', 'view.php' ] ) );  //"redirige" vers la vue
		}


		public static function read ($primary)
		{
			$v = ModelEvent ::select( $primary );
			$object = 'event';
			$view = 'detail';
			$pagetitle = 'Détail de l\'event.';
			require( File ::build_path( [ 'view', 'view.php' ] ) );  //"redirige" vers la vue
		}


		public static function created ($data)
		{
			if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1) {
				ModelEvent ::save( $data );
			}
			$tab_v = ModelEvent ::selectAll();
			$object = 'event';
			$view = 'created';
			$pagetitle = 'Liste des events';
			require( File ::build_path( [ 'view', 'view.php' ] ) );
		}


		public static function update ()
		{
			if (isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1) {
				if (isset( $_GET[ "id" ] )) {
					$l = ModelEvent ::select( $_GET[ "id" ] );
					if (strcmp( $l -> getLogin(), $_SESSION[ "login" ] ) == 0) {
						$object = 'event';
						$view = 'update';
						$pagetitle = 'Update Event';
						require( File ::build_path( [ 'view', 'view.php' ] ) );
					} else {
						self ::readAll();
					}
				} else {
					$object = 'event';
					$view = 'update';
					$pagetitle = 'Créer Event';
					require( File ::build_path( [ 'view', 'view.php' ] ) );
				}
			} else {
				self ::readAll();
			}
		}


		public static function updated ($data)
		{
			if (isset( $_SESSION[ "login" ] ) && isset( $data[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $data[ "login" ], $_SESSION[ "login" ] ) == 0 )) {

				ModelEvent ::update( $data );
				$view = 'updated';
				$pagetitle = 'Event updated';
				$object = 'event';
				$tab_v = ModelEvent ::selectAll();
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} elseif (!isset($_SESSION["login"])) {
				$object = 'main';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else {
				$view = "list";
				$pagetitle = "Liste des events";

				$object = 'event';
				$tab_v = ModelEvent ::selectAll();
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			}

		}


		public static function delete ($primary)
		{
			$l = ModelEvent ::select( $primary );
			$isLogged=isset( $_SESSION[ "login" ] );
			if ( $isLogged&& ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $l -> getLogin(), $_SESSION[ "login" ] ) == 0 )) {

				ModelEvent ::delete( $primary );

				$tab_v = ModelEvent ::selectAll();
				$object = 'event';
				$view = 'delete';
				$pagetitle = 'Event supprimé';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else if (!$isLogged) {
				$object = 'main';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else {
				self ::readAll();
			}
		}

		public static function search ($date1, $date2, $A, $B)
		{
			$tab_v = ModelEvent ::searchEvent( $date1, $date2, $A, $B );
			$object = 'event';
			$view = 'list';
			$pagetitle = 'Liste de la recherche';
			require( File ::build_path( [ 'view', 'view.php' ] ) );
		}

	}
