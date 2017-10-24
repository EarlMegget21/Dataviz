<?php
	require_once File ::build_path ( [ 'model' , 'Model.php' ] );

	/**
	 *
	 */
	class ModelAdmin extends Model
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
		 * @var string
		 */
		static protected $object = "Admin";

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
		public function __construct ( $l = NULL , $m = NULL )
		{
			if ( !is_null ( $l ) && !is_null ( $m ) ) {
				$this -> login = $l;
				$this -> mdp = $m;
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

	}

?>
