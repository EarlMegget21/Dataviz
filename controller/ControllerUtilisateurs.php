<?php
	require_once File ::build_path ( [ 'model' , 'ModelUtilisateurs.php' ] ); // chargement du modèle

    class ControllerUtilisateurs{

        protected static $controller="utilisateurs";

		public static function readAll() {
		    if( isset( $_SESSION[ "login" ] )) {
                $tab_v = ModelUtilisateurs::selectAll();     //appel au modèle pour gerer la BD
                $object = 'utilisateurs';
                $view = 'list';
                $pagetitle = 'Liste des utilisateurs';
                require(File::build_path(['view', 'view.php']));  //"redirige" vers la vue
            }else{
		        self::connect();
            }
		}


		public static function read() {
			if ( isset( $_SESSION[ "login" ] ) && (($_SESSION[ "isAdmin" ] == 1 ) || ($_SESSION["login"] == $_GET['login'])) ) {    //Il fau être un admin connecté pour accéder aux détails des utilisateurs
				$v = ModelUtilisateurs ::select ( $_GET['login'] );
				$object = 'utilisateurs';
				$view = 'detail';
				$pagetitle = 'Détail de l\'utilisateur.';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
			}
			else {
			    $object = 'utilisateurs';
			    $view = "error";
			    $pagetitle = "Accès interdit";
                require File ::build_path( array ( 'view', 'view.php' ) );
			}
		}


		public static function created() {
            $data = array ();
            if ( Conf ::getDebug () ) {
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
			if ( !isset( $_SESSION[ "login" ] ) && strcmp ( $data[ "mdp" ] , $data[ "mdp_conf" ] ) == 0 ) {
				unset( $data[ "mdp_conf" ] );
				$data[ "mdp" ] = Security ::chiffrer ( $data[ "mdp" ] );
				$data[ "isAdmin" ] = 0;
				ModelUtilisateurs ::save ( $data );
				$tab_v = ModelUtilisateurs ::selectAll ();
				$object = 'utilisateurs';
				$view = 'created';
				$pagetitle = 'Liste des utilisateurs';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
			}
			else {
				echo "<p>Vous êtes déjà connecté ou le mot de passe que vous avez rentré n'est pas le bon !</p>";
				self ::readAll ();
			}
		}

		public static function update() {
			if ( isset($_GET["login"])&&(Session ::is_user ( $_GET[ "login" ] ) || Session::is_admin ()) ) {
				$object = 'utilisateurs';
				$view = 'update';
				$pagetitle = 'Utilisateur update';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );

			}
			else if(empty($_SESSION["login"])){
				$object = 'utilisateurs';
				$view = 'update';
				$pagetitle = 'Utilisateur créée';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );

			}
			else{
				ControllerEvent::readAll ();
			}
		}


		public static function updated() {
            $data = array ();
            if ( Conf::getDebug() ) {
                foreach ($_GET as $k => $v) {
                    if (strcmp( $k, "action" ) != 0 && strcmp( $k, "controller" ) != 0) {
                        $data += [ $k => $v ];
                    }
                }
            }else{
                foreach ($_POST as $k => $v) {
                    if (strcmp($k, "action") != 0 && strcmp($k, "controller") != 0) {
                        $data += [$k => $v];
                    }
                }
            }
			if ( isset( $data[ "login" ] ) && (Session ::is_user ( $data[ "login" ] ) || Session::is_admin ())&& strcmp ( $data[ "mdp" ] , $data[ "mdp_conf" ] ) == 0 ) {
				unset( $data[ "mdp_conf" ] );
				$data[ "mdp" ] = Security ::chiffrer ( $data[ "mdp" ] );
				ModelUtilisateurs ::update ( $data );
				$view = 'updated';
				$pagetitle = 'Admin updated';

			}
			else {
				echo "Vous n'êtes pas connecté ou les deux mots de passes que vous avez rentré sont différent !";
				$view = 'list';
				$pagetitle = 'Liste des utilisateurs';
			}
			$object = 'utilisateurs';

			$tab_v = ModelUtilisateurs ::selectAll ();

			require ( File ::build_path ( [ 'view' , 'view.php' ] ) );

		}


		public static function delete() {
            $model = $_GET['model'];
            $primary = $_GET[$model::getPrimary()];
			if ( Session ::is_user ( $primary ) ) {
				$object = 'utilisateurs';

				ModelUtilisateurs ::delete ( $primary );
				$view = 'delete';
				$pagetitle = 'Utilisateur supprimé';
				$tab_v = ModelUtilisateurs ::selectAll ();

				session_unset ();
				session_destroy ();
				setcookie ( session_name () , '' , time () - 1 );
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );

			}
			elseif ( !isset( $_SESSION[ "login" ] ) ) {

				$object = 'utilisateurs';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );

			}
			else {
				self ::readAll ();
			}

		}

		public static function connect() {
			if ( !isset( $_SESSION[ "login" ] ) ) {
				$object = 'main';
				$view = 'connect';
				$pagetitle = 'Connection à la page utilisateur';
				require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
			}
			else {
				ControllerEvent ::readAll ();
			}
		}

		public static function connected() {
            if ( Conf::getDebug() ) {
                $login = $_GET["login"];
                $mdp = $_GET["mdp"];
            }else{
                $login = $_POST["login"];
                $mdp = $_POST["mdp"];
            }
			if ( !isset( $_SESSION[ "login" ] ) ) {
				$g = ModelUtilisateurs ::checkPassword ( $login , $mdp );
				if ( $g !== FALSE ) {
					$_SESSION[ "login" ] = $g -> getLogin ();
					$_SESSION[ "isAdmin" ] = $g -> getIsAdmin ();
					ControllerUtilisateurs ::read($_SESSION["login"]);
				}
				else {
					echo "Mauvais mot de passe.";
					$object = 'main';
					$view = 'connect';
					$pagetitle = 'Connection à la page utilisateur';
					require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
				}
			}
			else {
				ControllerUtilisateurs ::connect();
			}
		}

		public static function disconnect() {
			if ( isset( $_SESSION[ "login" ] ) ) {
				session_unset ();
				session_destroy ();
				setcookie ( session_name () , '' , time () - 1 );
			}
			ControllerEvent ::readAll ();
		}

		public static function generate($n){
			for($i=0;$i<$n;$i++){
				$mdp="dataviz";
				$login=self::generateRandomString ();
				ModelUtilisateurs::save (["login"=>$login,"mdp"=>Security::chiffrer ($mdp),"isAdmin"=>0]);
			}
			self::readAll ();
		}
		private static function generateRandomString($length = 10) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}

        public static function error(){
            $object = 'main';
            $view = 'error';
            $pagetitle = 'Erreur';
            require( File ::build_path( [ 'view', 'view.php' ] ) );
        }
	}
