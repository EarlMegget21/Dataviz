<?php

	/**
	 *
	 */
	class ModelModerateur
	{
		/**
		 * @var String
		 */
		private $login;
		/**
		 * @var String
		 */
		private $mdp;

		// a constructor
		public function __construct ( $l = NULL , $m = NULL )
		{
			if ( !is_null ( $l ) && !is_null ( $m ) ) {
				$this -> login = $l;
				$this -> mdp = $m;
			}
		}
	}

?>
