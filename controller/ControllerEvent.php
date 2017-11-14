<?php
	require_once File ::build_path( array ( 'model', 'ModelEvent.php' ) ); // chargement du modèle

	class ControllerEvent
	{

		public static function readAll ()
		{
			$tab_v = ModelEvent ::selectAll();     //appel au modèle pour gerer la BD
            $lat=40; //on centre la map par défault
            $lng=10; //on centre la map par défault
            $zoom=3; //définition du zoom par défault
			$object = 'event';
			$view = 'list';
			$pagetitle = 'Liste des events';
			require( File ::build_path( [ 'view', 'view.php' ] ) );  //"redirige" vers la vue
		}


		public static function read ($primary)
		{
			$v = ModelEvent ::select( $primary );
            $lat=$v->getLatitude(); //on centre la map sur le point sélectionné
            $lng=$v->getLongitude(); //on centre la map sur le point sélectionné
            $zoom=4;
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
            $lat=40;
            $lng=10;
            $zoom=3;
			$object = 'event';
			$view = 'created';
			$pagetitle = 'Liste des events';
			require( File ::build_path( [ 'view', 'view.php' ] ) );
		}


		public static function update ()
		{
            $lat=40;
            $lng=10;
            $zoom=3;
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
            $lat=40;
            $lng=10;
            $zoom=3;
			if (isset( $_SESSION[ "login" ] ) && isset( $data[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $data[ "login" ], $_SESSION[ "login" ] ) == 0 )) {

				ModelEvent ::update( $data );
                $tab_v = ModelEvent ::selectAll();
				$view = 'updated';
				$pagetitle = 'Event updated';
				$object = 'event';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} elseif (!isset($_SESSION["login"])) {
				$object = 'utilisateurs';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else {
                $tab_v = ModelEvent ::selectAll();
				$view = "list";
				$pagetitle = "Liste des events";
				$object = 'event';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			}

		}


		public static function delete ($primary)
		{
			$l = ModelEvent ::select( $primary );
			$isLogged=isset( $_SESSION[ "login" ] );
            $lat=40;
            $lng=10;
            $zoom=3;
			if ( $isLogged&& ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $l -> getLogin(), $_SESSION[ "login" ] ) == 0 )) {

				ModelEvent ::delete( $primary );
				$tab_v = ModelEvent ::selectAll();
				$object = 'event';
				$view = 'delete';
				$pagetitle = 'Event supprimé';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else if (!$isLogged) {
				$object = 'utilisateurs';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require( File ::build_path( [ 'view', 'view.php' ] ) );
			} else {
				self ::readAll();
			}
		}

		public static function search ($date1, $date2, $A, $B, $mot=NULL)
		{
			$tab_v = ModelEvent ::searchEvent( $date1, $date2, $A, $B, $mot);
            $lat=($B[1]+$A[1])/2; //on centre la map là où elle était centrée lors de la recherche
            $lng=($A[0]+$B[0])/2;
            $zoom=$_GET['zoom']; //affecte le dernier zoom
			$object = 'event';
			$view = 'list';
			$pagetitle = 'Liste de la recherche';
			require( File ::build_path( [ 'view', 'view.php' ] ) );
		}

	}
