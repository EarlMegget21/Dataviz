<?php
	require_once File ::build_path ( [ 'model' , 'ModelEvent.php' ] ); // chargement du modèle

	class ControllerEvent {

        protected static $controller="event";

		public static function readAll ()
		{
		    $affiche=true;
			$tab_v = ModelEvent ::selectAll ();     //appel au modèle pour gerer la BD
            $tab_minmax = ModelEvent::getMinMax($tab_v);    //$tab_minmax("minLat", "maxLat", "minLong", "maxLong", "minDate", "maxDate")
			$object = 'event';
			$view = 'list';
			$pagetitle = 'Liste des events';
			require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
		}

		public static function created ( $data )
		{
			if ( isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1 ) {
				ModelEvent ::save ( $data );
			}
            self ::readAll ();
		}


		public static function update ()
		{
			if ( isset( $_SESSION[ "login" ] ) && $_SESSION[ "isAdmin" ] == 1 ) {
				if ( isset( $_GET[ "id" ] ) ) { //cas où on update
					$l = ModelEvent ::select ( $_GET[ "id" ] );
					if ( strcmp ( $l -> getLogin () , $_SESSION[ "login" ] ) == 0 ) {
						$object = 'event';
						$view = 'update';
						$pagetitle = 'Update Event';
						require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
					}
					else {
						self ::readAll ();
					}
				}
				else { //cas où on créer
					$object = 'event';
					$view = 'update';
					$pagetitle = 'Créer Event';
					require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
				}
			}
			else {
				self ::readAll ();
			}
		}


		public static function updated ( $data )
		{
			if ( isset( $_SESSION[ "login" ] ) && isset( $data[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp ( $data[ "login" ] , $_SESSION[ "login" ] ) == 0 ) ) {
				ModelEvent ::update ( $data );
                ControllerEvent::readAll(); //afficher "evenement mis à jour"
			}
			elseif ( !isset( $_SESSION[ "login" ] ) ) {
				$object = 'main';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
			}
			else {
                self::readAll();
			}

		}


		public static function delete ( $primary )
		{
			$l = ModelEvent ::select ( $primary );
			$isLogged = isset( $_SESSION[ "login" ] );
			if ( $isLogged && ( $_SESSION[ "isAdmin" ] == 1 && strcmp ( $l -> getLogin () , $_SESSION[ "login" ] ) == 0 ) ) {
				ModelEvent ::delete ( $primary );
				self::readAll(); //afficher "evenement supprimé"
			}
			elseif ( !$isLogged ) {
				$object = 'main';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
			}
			else {
				self ::readAll ();
			}
		}

		public static function generate ( $n )
		{
			$users = ModelUtilisateurs ::selectAll ();
			for ( $i = 0 ; $i < $n && count ( $users ) != 0 ; $i++ ) {
				$start = strtotime("01 January 1950");
				$end = strtotime("31 December 2017");
				$date = date("Y-m-d", mt_rand($start, $end));
				$longitude = rand ( -180 , 180 );
				$latitude = rand ( -90 , 90 );
				$mp3 = "";
				$nom = self ::generateRandomString ();
				$login = $users[ rand ( 0 , count ( $users ) - 1 ) ]->getLogin();

				$description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo nulla est, vitae euismod neque viverra non. Donec vestibulum pharetra odio, vitae vulputate nisi accumsan sed. Morbi ac luctus lectus. Pellentesque lectus metus, ullamcorper a magna vel, rutrum blandit tortor. Donec gravida eros sit amet urna maximus vehicula. Aenean semper tortor vel ipsum fringilla, ac tempor purus pretium. Maecenas et dapibus tortor, volutpat consectetur velit. Proin eget aliquet neque.

Phasellus ex ex, pharetra sed vulputate a, consequat vel lectus. Nulla molestie tincidunt facilisis. Cras tempus odio nec mauris mollis, at ornare quam facilisis. Praesent sit amet eleifend velit. Vivamus porttitor placerat libero quis semper. Integer pretium et nunc elementum auctor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Duis efficitur auctor sagittis. Nam leo elit, pulvinar et efficitur et, tristique quis orci. Proin bibendum neque ac augue viverra, at ullamcorper enim ullamcorper. Integer vehicula lectus orci, finibus pharetra ipsum ornare quis. Praesent porttitor interdum ornare. In eu turpis nisl. Sed vel egestas neque. Vestibulum volutpat ut nulla sit amet finibus. Pellentesque diam urna, sodales quis orci sit amet, pulvinar tempor orci.

Mauris eleifend risus at nisl ultrices, a iaculis mi dictum. Maecenas vestibulum lorem id est accumsan dapibus. Quisque sed nisi ac lorem blandit interdum at at elit. Integer sed magna egestas, accumsan dolor sed, cursus enim. Aenean vitae molestie lacus. Sed vitae velit quis sem rhoncus suscipit sodales ornare mi. Donec ultricies blandit risus, sit amet condimentum ipsum rhoncus nec. Pellentesque eget viverra enim. Pellentesque hendrerit erat libero, vitae rhoncus sapien volutpat quis. Morbi feugiat elit et metus efficitur, sed feugiat eros lobortis.

Morbi ut malesuada nisl. Etiam finibus aliquam enim non consequat. Aliquam et ipsum sollicitudin, laoreet ligula ac, facilisis urna. Cras lacus massa, pretium consequat felis ac, aliquam tempus augue. Curabitur ut fringilla nibh, sit amet eleifend eros. Nulla convallis urna nec lorem posuere, quis dignissim nisl ultricies. In sodales tincidunt turpis, in lobortis erat commodo sit amet. Duis dapibus mauris sed convallis pretium. Nulla facilisi. Integer scelerisque interdum libero, ac tincidunt ipsum pretium eu. Pellentesque non dui efficitur, viverra nulla eu, semper massa. Fusce ut tellus tellus.

Aliquam lectus nunc, varius eget sagittis viverra, pharetra ut sapien. Phasellus hendrerit ex sapien, at vehicula ante dapibus non. Aenean quis neque ac lacus tristique volutpat nec vehicula sem. Sed et purus maximus, vulputate mi non, dictum leo. Sed consequat, ipsum imperdiet tempor placerat, tortor tortor blandit nisi, nec consequat augue turpis eu est. Vestibulum ac varius ante. Curabitur molestie mauris et suscipit rutrum. Quisque vel dictum quam. Sed vel nunc ante. Nam lacinia massa felis, sed lacinia risus volutpat et.";

				ModelEvent ::save ( [ "date" => $date , "longitude" => $longitude , "latitude" => $latitude ,
				                             "description" => $description , "mp3"=>$mp3 , "nom"=>$nom , "login"=>$login ] );

			}
			self::readAll ();
		}

		private static function generateRandomString ( $length = 10 )
		{
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen ( $characters );
			$randomString = '';
			for ( $i = 0 ; $i < $length ; $i++ ) {
				$randomString .= $characters[ rand ( 0 , $charactersLength - 1 ) ];
			}

			return $randomString;
		}

		public static function comment(){
		    $idEvent = $_GET['id'];
		    $login = $_GET['login'];
		    $note = $_GET['note'];
		    $commentaire = $_GET['commentaire'];
		    require_once File::build_path(array('model', 'ModelCommentaire.php'));
		    $c = new ModelCommentaire($idEvent, $login, $commentaire, $note);
		    $c->save();
		    self::read($idEvent);
		}

	}
