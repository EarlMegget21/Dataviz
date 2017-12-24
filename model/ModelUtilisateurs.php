<?php
	require_once File ::build_path ( [ 'model' , 'Model.php' ] );

	/**
	 *
	 */
	class ModelUtilisateurs extends Model {

		private $login;
		private $mdp;
		private $isAdmin;
        static protected $object = "Utilisateurs";
        static protected $primary = "login";

		public function getIsAdmin () {
			return $this -> isAdmin;
		}

		/**
		 * ModelModerateur constructor.
		 *
		 * @param null $l
		 * @param null $m
		 */
		public function __construct ( $l = NULL , $m = NULL, $i=NULL )
		{
			if ( !is_null ( $l ) && !is_null ( $m )&& !is_null($i) ) {
				$this -> login = $l;
				$this -> mdp = $m;
				$this->isAdmin=$i;
			}
		}

		public function getLogin ()
		{
			return $this -> login;
		}

		public function getMdp ()
		{
			return $this -> mdp;
		}

        public static function getPrimary()
        {
            return self::$primary;
        }

		public static function checkPassword($login,$mot_de_passe_chiffre){
			$sql = "SELECT * 
					FROM Utilisateurs 
					WHERE mdp=:mdp and login=:login";

			$req_prep = Model ::$pdo -> prepare ( $sql );

			$match = [
				"login"  => $login ,
				"mdp" => Security::chiffrer ($mot_de_passe_chiffre)
			];
			$req_prep -> execute ( $match );
			$req_prep -> setFetchMode ( PDO::FETCH_CLASS , 'ModelUtilisateurs' );
			$tab=$req_prep->fetchAll ();
			if(empty($tab)){
				return FALSE;
			}
			return $tab[0];
		}
	}

?>
