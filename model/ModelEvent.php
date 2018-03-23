<?php

	require_once File ::build_path ( [ 'model' , 'Model.php' ] );

	/**
	 *
	 */
	class ModelEvent extends Model {

		private $id;
		private $date;
		private $longitude;
		private $latitude;
		private $description;
		private $mp3;
		private $nom;
		private $login;
		static protected $object = "Event";
		static protected $primary = "id";

		public function __construct ( $i = NULL , $d = NULL , $x = NULL , $y = NULL , $de = NULL , $m = NULL , $n = NULL , $l = NULL ) {
			if ( !is_null ( $i ) && !is_null ( $n ) && !is_null ( $d ) && !is_null ( $x ) && !is_null ( $y ) && !is_null ( $de ) && !is_null ( $l ) && !is_null ( $m ) ) {
				$this -> id = $i;
				$this -> nom = $n;
				$this -> date = $d;
				$this -> longitude = $x;
				$this -> latitude = $y;
				$this -> description = $de;
				$this -> login = $l;
				$this -> mp3 = $m;
			}
		}

		public function getId ()
		{
			return $this -> id;
		}

		public function getDate ()
		{
			return $this -> date;
		}

		public function getLongitude ()
		{
			return $this -> longitude;
		}

		public function getLatitude ()
		{
			return $this -> latitude;
		}

		public function getDescription ()
		{
			return $this -> description;
		}

		public function getNom ()
		{
			return $this -> nom;
		}

		public function getLogin ()
		{
			return $this -> login;
		}

		public function getMP3 ()
		{
			return $this -> mp3;
		}

		public static function getPrimary ()
		{
			return self ::$primary;
		}

		//Recherche d'events en fonction de la date et de la position
		public static function getEventList ( $lowest , $highest , $xa, $ya , $xb, $yb, $keyword) {
			$sql = "SELECT * 
					FROM Event 
					WHERE date>=:low and date<=:high and latitude>=:yA and latitude<=:yB and ";
			if($xa>$xb){ //si on est de l'autre côté de la Terre (x1>x2)
			    $sql=$sql.'(longitude>=:xA or longitude<=:xB)';
		    }else{
			$sql=$sql.'longitude>=:xA and longitude<=:xB';
		    }
				if(!is_null($keyword)){ //Si on a un keyword alors on recherche
				    $sql = $sql." AND (description LIKE CONCAT('%',:keyword,'%') OR nom LIKE CONCAT('%',:keyword,'%'))";
		    }
		    try{
			$req_prep = Model ::$pdo -> prepare ( $sql );
			if(!is_null($keyword)) { //Si on a un keyword
			    $match = [
				"low" => $lowest,
				"high" => $highest,
				"xA" => $xa,
				"yA" => $ya,
				"xB" => $xb,
				"yB" => $yb,
				"keyword" => $keyword,
			    ];
			}else{  //Pas de keyword
			    $match = [
				"low" => $lowest,
				"high" => $highest,
				"xA" => $xa,
				"yA" => $ya,
				"xB" => $xb,
				"yB" => $yb,
			    ];
			}

			$req_prep -> execute ( $match );
			$req_prep -> setFetchMode ( PDO::FETCH_CLASS , 'ModelEvent' );
			$tab_v=$req_prep -> fetchAll ();

			//création du doc XML(virtuel)
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

			return $doc;
		    } catch(PDOException $e){
			if ( Conf ::getDebug () ) {
			    echo $e -> getMessage (); // affiche un message d'erreur
			} else {
			    echo 'Une erreur est survenue <a href="#"> retour a la page d\'accueil </a>';
			}
			die(); //supprimer equilvalent à System.exit(1); en java
		    }
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
		public static function searchEvent ( $date1 , $date2 , $A , $B, $keyword)
		{
			$filter = self ::getEventList ( $date1 , $date2 , $A , $B, $keyword);

			return $filter;

		}
		
		public static function getEventListJSON ( $lowest , $highest , $xa, $ya , $xb, $yb, $keyword) {
			$sql = "SELECT * 
				FROM Event 
				WHERE date>=:low and date<=:high and latitude>=:yA and latitude<=:yB and ";
			if($xa>$xb){ //si on est de l'autre côté de la Terre (x1>x2)
				$sql=$sql.'(longitude>=:xA or longitude<=:xB)';
			}else{
				$sql=$sql.'longitude>=:xA and longitude<=:xB';
			}
			if(!is_null($keyword)){ //Si on a un keyword alors on recherche
				$sql = $sql." AND (description LIKE CONCAT('%',:keyword,'%') OR nom LIKE CONCAT('%',:keyword,'%'))";
			}
			try{
				$req_prep = Model ::$pdo -> prepare ( $sql );
				if(!is_null($keyword)) { //Si on a un keyword
				    $match = [
					"low" => $lowest,
					"high" => $highest,
					"xA" => $xa,
					"yA" => $ya,
					"xB" => $xb,
					"yB" => $yb,
					"keyword" => $keyword,
				    ];
				}else{  //Pas de keyword
				    $match = [
					"low" => $lowest,
					"high" => $highest,
					"xA" => $xa,
					"yA" => $ya,
					"xB" => $xb,
					"yB" => $yb,
				    ];
				}

				$req_prep -> execute ( $match );
				$req_prep -> setFetchMode ( PDO::FETCH_ASSOC);
				$tab_v=$req_prep -> fetchAll ();

				return json_encode($tab_v);
			    } catch(PDOException $e){
				if ( Conf ::getDebug () ) {
				    echo $e -> getMessage (); // affiche un message d'erreur
				} else {
				    echo 'Une erreur est survenue <a href="#"> retour a la page d\'accueil </a>';
				}
				die(); //supprimer equilvalent à System.exit(1); en java
			    }
			}

		}

?>
