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
		private $longitude;

		/**
		 * @var float
		 */
		private $latitude;

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
				$this -> longitude = $x;
				$this -> latitude = $y;
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
		public function getLongitude ()
		{
			return $this -> longitude;
		}

		/**
		 * @return float
		 */
		public function getLatitude ()
		{
			return $this -> latitude;
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
		private static function getEventList ( $lowest , $highest , $A , $B, $mot)
		{
			$sql = "SELECT * 
					FROM Event 
					WHERE date>=:low and date<=:high and latitude>=:yA and latitude<=:yB and ";
			if($A[ 0 ]>$B[ 0 ]){ //si on est de l'autre côté de la Terre (x1>x2)
			    $sql=$sql.'(longitude>=:xA or longitude<=:xB)';
            }else{
                $sql=$sql.'longitude>=:xA and longitude<=:xB';
            }
			if(!is_null($mot)){
			    $sql = $sql." AND (description LIKE CONCAT('%',:mot,'%') OR nom LIKE CONCAT('%',:mot,'%'))";
            }
			$req_prep = Model ::$pdo -> prepare ( $sql );

			$match = [
				"low"  => $lowest ,
				"high" => $highest ,
				"xA"   => $A[ 0 ] ,
				"yA"   => $A[ 1 ] ,
				"xB"   => $B[ 0 ] ,
				"yB"   => $B[ 1 ] ,
                "mot"  => $mot,
			];

			$req_prep -> execute ( $match );
			$req_prep -> setFetchMode ( PDO::FETCH_CLASS , 'ModelEvent' );
			$tab_v=$req_prep -> fetchAll ();

            if(static::$object=="Event") { //si la classe appelante c'est ModelEvent alors auvegarde dans un XML avant de retourner
                if (file_exists("./xml/points.xml")) { //si le fichier XML existe déjà
                    unlink("./xml/points.xml"); //supprime le fichier XML
                }
                $doc = new DOMDocument("1.0", "UTF-8"); //créer un objet de type document DOM(format de balises comme XML, html, ...)
                $node = $doc->createElement("markers"); //créer une balise <markers> contenant tous les points
                $parnode = $doc->appendChild($node); //ajoute cette balise au document
                foreach ($tab_v as $event) { //pour chaque event retourné par la requête
                    // Add to XML document node
                    $node = $doc->createElement("marker"); //créer une balise <marker> représentant un point
                    $newnode = $parnode->appendChild($node); //ajoute cette balise en enfant à <markers>

                    $newnode->setAttribute("id", $event->getId()); //ajoute chaque attribut
                    $newnode->setAttribute("nom", $event->getNom());
                    $newnode->setAttribute("description", $event->getDescription());
                    $newnode->setAttribute("lat", $event->getLatitude());
                    $newnode->setAttribute("lng", $event->getLongitude());
                    $newnode->setAttribute("date", $event->getDate());
                    $newnode->setAttribute("login", $event->getLogin());
                    $newnode->setAttribute("mp3", $event->getMP3());
                }
                $xmlfile = $doc->save("./xml/points.xml"); //sauvegarde le document en fichier physique à l'adresse suivante et sous le nom points.xml sur le seveur
            }
            return $tab_v;
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
		public static function searchEvent ( $date1 , $date2 , $A , $B, $mot)
		{
			$filter = self ::getEventList ( $date1 , $date2 , $A , $B, $mot);

			//TODO: Filtrer keywords (Rajouter un paramètre pour les mots)

			return $filter;

		}

		public static function getMinMax($tab_event){   //Retourne un tableau contenant les min/max des coordonnées et des dates des events issus d'une recherche
            $minLat = 0;
            $maxLat = 40;
            $minLong = 0;
            $maxLong = 40;
            $minDate = "1900-01-01";
            $maxDate = "2017-31-12";
            foreach($tab_event as $key => $event){
		        $currLat = $event->getLatitude();
		        $currLong = $event->getLongitude();
		        $currDate = $event->getDate();
                if($key == 0){
                    $minLat = $currLat;
                    $maxLat = $currLat;
                    $minLong = $currLong;
                    $maxLong = $currLong;
                    $minDate = $currDate;
                    $maxDate = $currDate;
                }else{
                    if($currLat < $minLat){
                        $minLat =$currLat;
                    }else if($currLat > $maxLat){
                        $maxLat = $currLat;
                    }
                    if($currLong < $minLong){
                        $minLong = $currLong;
                    } else if($currLong > $maxLong){
                        $maxLong = $currLong;
                    }
                    if($currDate < $minDate){
                        $minDate = $currDate;
                    }else if($currDate > $maxDate){
                        $maxDate = $currDate;
                    }
                }
            }
            $tab_minmax = [
                "minLat" => $minLat,
                "maxLat" => $maxLat,
                "minLong" => $minLong,
                "maxLong" => $maxLong,
                "minDate" => $minDate,
                "maxDate" => $maxDate
            ];
            return $tab_minmax;
        }

	}

?>