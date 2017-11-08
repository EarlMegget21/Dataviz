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
		public function __construct ( $i = NULL , $d = NULL , $x = NULL , $y = NULL , $de = NULL , $m = NULL , $n = NULL , $l = NULL )
		{
			if ( !is_null ( $i ) && !is_null ( $n ) && !is_null ( $d ) && !is_null ( $x ) && !is_null ( $y ) && !is_null ( $de ) && !is_null ( al ) ) {
				$this -> id = $i;
				$this -> nom = $n;
				$this -> date = $d;
				$this -> coordonneX = $x;
				$this -> coordonneY = $y;
				$this -> description = $de;
				$this -> login = $l;
				$this -> MP3 = $m;
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
		public function getMP3 ()
		{
			return $this -> MP3;
		}


		/**
		 * @return string
		 */
		public static function getPrimary ()
		{
			return self ::$primary;
		}

		public static function getLowestDate ()
		{
			$sql = "SELECT MIN(date) FROM Event;";
			$req_prep = Model ::$pdo -> prepare ( $sql );
			$req_prep -> execute ();
			$tab_obj = $req_prep -> fetchAll ( PDO::FETCH_OBJ );
			if ( empty( $tab_rep ) ) {
				return FALSE;
			}

			return $tab_rep[ 0 ];
		}

		public static function getHighestDate ()
		{
			$sql = "SELECT MAX(date) FROM Event;";
			$req_prep = Model ::$pdo -> prepare ( $sql );
			$req_prep -> execute ();
			$tab_obj = $req_prep -> fetchAll ( PDO::FETCH_OBJ );
			if ( empty( $tab_rep ) ) {
				return FALSE;
			}

			return $tab_rep[ 0 ];
		}

		//Recherche d'events en fonction de la date et de la position
		private static function getEventList ( $lowest , $highest , $A , $B )
		{
			$sql = "SELECT * 
					FROM Event 
					WHERE date>=:low and date<=:high and coordonneeX>=:xA and coordonneeY>=:yA and coordonneeX<=:xB and coordonneeY<=:yB ;";
			$req_prep = Model ::$pdo -> prepare ( $sql );

			$match = [
				"low"  => $lowest ,
				"high" => $highest ,
				"xA"   => $A[ 0 ] ,
				"yA"   => $A[ 1 ] ,
				"xB"   => $B[ 0 ] ,
				"yB"   => $B[ 1 ] ,
			];

			$req_prep -> execute ( $match );
			$req_prep -> setFetchMode ( PDO::FETCH_CLASS , 'ModelEvent' );

			return $req_prep -> fetchAll ();
		}

		/*
		 * Fonction de recherche d'event:
		 *  1e etape: Recherche SQL (Date, geolocalisation)
		 *  2e etape: Recherche KeyWords
		 *
		 * Pour le moment il y a que la première étape.
		 *
		 * La fonction est coupé en deux pour rendre la fonction plus lisible.
		 */
		public static function searchEvent ( $date1 , $date2 , $A , $B )
		{
			$filter = self ::getEventList ( $date1 , $date2 , $A , $B );

			//TODO: Filtrer keywords (Rajouter un paramètre pour les mots)

			return $filter;

		}

	}

?>