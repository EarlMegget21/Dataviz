<?php
	require_once File ::build_path ( [ 'model' , 'Model.php' ] );

	/**
	 *
	 */
	class ModelUtilisateurs extends Model
	{
		/**
		 * @var String
		 */
		private $login;

		/**
		 * @var String
		 */
		private $mdp;

		/**
		 * @var int
		 */
		private $isAdmin;

		/**
		 * @return null
		 */
		public function getIsAdmin ()
		{
			return $this -> isAdmin;
		}
		/**
		 * @var string
		 */
		static protected $object = "Utilisateurs";

		/**
		 * @var string
		 */
		static protected $primary = "login";


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

		/**
		 * @return String
		 */
		public function getLogin ()
		{
			return $this -> login;
		}

		/**
		 * @return String
		 */
		public function getMdp ()
		{
			return $this -> mdp;
		}

        /**
         * @return string
         */
        public static function getPrimary()
        {
            return self::$primary;
        }
	}

?>