<?php

    require_once File ::build_path ( [ 'model' , 'Model.php' ] );
    /**
	 *
	 */
	class ModelEvent extends Model
	{

		/**
		 * @var int
		 */
		private $id;

		/**
		 * @var Date
		 */
		private $date;

		/**
		 * @var float
		 */
		private $coordonneeX;

		/**
		 * @var float
		 */
		private $coordonneeY;

		/**
		 * @var String
		 */
		private $description;

		/**
		 * @var String
		 */
		private $MP3;
		/**
		 * @var String
		 */
		private $nom;

		/**
		 * @var String
		 */
		private $login;

		/**
		 * @var string
		 */
		static protected $object = "Event";

		/**
		 * @var string
		 */
		static protected $primary = "id";

		/**
		 * ModelEvent constructor.
		 *
		 * @param null $n
		 * @param null $d
		 * @param null $x
		 * @param null $y
		 * @param null $de
		 * @param null $al
		 */
		public function __construct ( $i=NULL , $d = NULL , $x = NULL , $y = NULL , $de = NULL ,$m=NULL,$n = NULL, $l = NULL )
		{
			if ( !is_null ($i) && !is_null ( $n ) && !is_null ( $d ) && !is_null ( $x ) && !is_null ( $y ) && !is_null ( $de ) && !is_null ( al ) ) {
				$this->id=$i;
				$this -> nom = $n;
				$this -> date = $d;
				$this -> coordonneX = $x;
				$this -> coordonneY = $y;
				$this -> description = $de;
				$this -> login = $l;
				$this->MP3=$m;
			}
		}

		/**
		 * @return int
		 */
		public function getId ()
		{
			return $this -> id;
		}

		/**
		 * @return Date
		 */
		public function getDate ()
		{
			return $this -> date;
		}

		/**
		 * @return float
		 */
		public function getCoordonneeX ()
		{
			return $this -> coordonneeX;
		}

		/**
		 * @return float
		 */
		public function getCoordonneeY ()
		{
			return $this -> coordonneeY;
		}

		/**
		 * @return String
		 */
		public function getDescription ()
		{
			return $this -> description;
		}

		/**
		 * @return String
		 */
		public function getNom ()
		{
			return $this -> nom;
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
        public function getMP3()
        {
            return $this->MP3;
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