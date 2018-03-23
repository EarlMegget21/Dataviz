<?php
	require_once File ::build_path ( [ 'model' , 'ModelEvent.php' ] ); // chargement du modèle
    require_once File ::build_path ( [ 'model' , 'ModelCommentaire.php' ] ); // chargement du modèle

	class ControllerEvent {

        protected static $controller="event";

		public static function readAll () {
		    $affiche=true; //boolean pour indiquer au serveur d'envoyer les javascript
			$object = 'event';
			$view = 'list';
			$pagetitle = 'Liste des events';
			require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
		}

		public static function created() {
            if ( isset( $_SESSION[ "login" ] ) ) {
                $data = array ();
                if ( Conf::getDebug() ) {
                    foreach ($_GET as $k => $v) {
                        if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                            $data += [$k => $v];
                        }
                    }
                }else{
                    foreach ($_POST as $k => $v) {
                        if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                            $data += [$k => $v];
                        }
                    }
                }
				ModelEvent ::save ( $data );
			}
            self ::readAll ();
		}

		public static function update () {
			if ( isset( $_SESSION[ "login" ] )) { //si on est connecté
				if ( isset( $_GET[ "id" ] ) ) { //cas où on update
					$v = ModelEvent ::select ( $_GET[ "id" ] );
					if($v == null){
                        self ::readAll ();
                    }else{
					    if(!Session::is_admin()){
                            if ( strcmp ( $v -> getLogin () , $_SESSION[ "login" ] ) == 0 ) { //Un utilisateur ne peut modifier que des event qu'il a créé
                                $object = 'event';
                                $view = 'update';
                                $pagetitle = 'Update Event';
                                require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
                            } else {
                                self ::readAll ();
                            }
                        } else {
                            $object = 'event';
                            $view = 'update';
                            $pagetitle = 'Update Event';
                            require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
                        }
                    }
				} else { //cas où on crée
					$object = 'event';
					$view = 'update';
					$pagetitle = 'Créer Event';
					require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
				}
			} else {
				self ::readAll ();
			}
		}


		public static function updated () {
            if ( isset( $_SESSION[ "login" ] ) ) {
                $data = array();
                if (Conf::getDebug()) {
                    foreach ($_GET as $k => $v) {
                        if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                            $data += [$k => $v];
                        }
                    }
                } else {
                    foreach ($_POST as $k => $v) {
                        if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                            $data += [$k => $v];
                        }
                    }
                }
                if (isset($_SESSION["login"]) && isset($data["login"])) {
                    if (Session::is_admin() || strcmp($data["login"], $_SESSION["login"]) == 0) {//Admin ou utilisateur qui modif un evnet qu'il a lui-même créé
                        ModelEvent::update($data);
                    }
                }
                self::readAll();
            }
		}


        public static function delete () {
		    if(isset($_GET['model'])) {
                $model = $_GET['model'];
		        if(isset($_GET[$model::getPrimary()])) {
                    $primary = $_GET[$model::getPrimary()];
                    $l = $model::select($primary);
                    if (isset($_SESSION["login"]) && (Session::is_admin() || Session::is_user($l->getLogin()))) {
                        $model::delete($primary);
                    }
                }
            }
            self ::readAll ();
        }

		public static function generate() {
		    $n=$_GET["n"];
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

				ModelEvent::save( [ "date" => $date , "longitude" => $longitude , "latitude" => $latitude ,
				                             "description" => $description , "mp3"=>$mp3 , "nom"=>$nom , "login"=>$login ] );
			}
			self::readAll ();
		}

		private static function generateRandomString ( $length = 10 ) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen ( $characters );
			$randomString = '';
			for ( $i = 0 ; $i < $length ; $i++ ) {
				$randomString .= $characters[ rand ( 0 , $charactersLength - 1 ) ];
			}
			return $randomString;
		}

		public static function comment() {
            if ( isset( $_SESSION[ "login" ] ) ) {
                $data = array ();
                if ( Conf::getDebug() ) {
                    foreach ($_GET as $k => $v) {
                        if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                            $data += [$k => $v];
                        }
                    }
                }else{
                    foreach ($_POST as $k => $v) {
                        if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                            $data += [$k => $v];
                        }
                    }
                }
                ModelCommentaire ::save ( $data );
            }
            self::readAll();
		}


    public static function test(){
        $t=[];
        if(Conf::getDebug()){
            if(isset($_GET[ "login" ])){
                $t["login"]=$_GET[ "login" ];
            }
            if(isset($_GET[ "mdp" ])){
                $t["mdp"]=$_GET[ "mdp" ];
            }
            if(isset($_GET[ "mdp_conf" ])){
                $t["mdp_conf"]=$_GET[ "mdp_conf" ];
            }
            if(isset($_GET[ "isAdmin" ])){
                $t["isAdmin"]=$_GET[ "isAdmin" ];
            }
        }else{
            if(isset($_POST[ "login" ])){
                $t["login"]=$_POST[ "login" ];
            }
            if(isset($_POST[ "mdp" ])){
                $t["mdp"]=$_POST[ "mdp" ];
            }
            if(isset($_POST[ "mdp_conf" ])){
                $t["mdp_conf"]=$_POST[ "mdp_conf" ];
            }
            if(isset($_POST[ "isAdmin" ])){
                $t["isAdmin"]=$_POST[ "isAdmin" ];
            }
        }
        return $t;
    }

        public static function searchEvents() { //fonction appelée par AJAX pour récupérer le XML des events
		    $mindate=$_GET["mindate"];
		    $maxdate=$_GET["maxdate"];
		    $xa=$_GET["xa"];
		    $ya=$_GET["ya"];
		    $xb=$_GET["xb"];
		    $yb=$_GET["yb"];
		    $keyword=$_GET["keyword"];
            $doc = ModelEvent::getEventList ($mindate."-01-01", $maxdate."-12-31", $xa, $ya, $xb, $yb, $keyword); //appel au modèle pour interroger la BD
            header('Content-Type: text/xml');
            echo $doc->saveXML();
        }
		
	public static function searchEventsJSON() { //fonction appelée par Android pour récupérer le JSON des events
	    $mindate=$_GET["mindate"];
	    $maxdate=$_GET["maxdate"];
	    $xa=$_GET["xa"];
	    $ya=$_GET["ya"];
	    $xb=$_GET["xb"];
	    $yb=$_GET["yb"];
	    $keyword=$_GET["keyword"];
            $doc = ModelEvent::getEventListJSON ($mindate."-01-01", $maxdate."-12-31", $xa, $ya, $xb, $yb, $keyword); //appel au modèle pour interroger la BD
            echo($doc);
        }

        public static function searchComments() { //fonction appelée par AJAX pour récupérer le XML des commentaires
		    $idEvent=$_GET["idEvent"];
            $doc = ModelCommentaire::getCommentList ($idEvent); //appel au modèle pour interroger la BD
            header('Content-Type: text/xml');
            echo $doc->saveXML();
        }
	}
