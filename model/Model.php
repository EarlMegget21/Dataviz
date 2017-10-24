<?php
	require_once File ::build_path ( [ 'config' , 'Conf.php' ] );

	/**
	 * Description of model
	 *
	 * @author sonettir
	 */
	class Model
	{
		/**
		 * @var PDO
		 */
		static private $pdo;

		/**
		 * @var String
		 */
		static protected $object;

		/**
		 * @var String
		 */
		static protected $primary;

		public static function Init ()
		{
			$hostname = Conf ::getHostname ();
			$login = Conf ::getLogin ();
			$password = Conf ::getPassword ();
			$database = Conf ::getDatabase ();
            $port=Conf::getPort();
			try {
				self ::$pdo = new PDO( "mysql:host=$hostname;port=".$port.";dbname=$database" , $login , $password , [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ] );
				self ::$pdo -> setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) {
				if ( Conf ::getDebug () ) {
					echo $e -> getMessage (); // affiche un message d'erreur
				}
				else {
					echo 'Une erreur est survenue <a href=""> retour a la page d\'accueil </a>';
				}
				die(); //supprimer equilvalent à System.exit(1); en java
			}
		}

		/**
		 * @return mixed
		 */
		/*
		 * Cette fonction sera utilisé par les Model-fils où
		 * les informations tel que "object" seront spécifié.
		 */
		public static function selectAll ()
		{

			$table_name = [ "name" => static ::$object ];
			$class_name = 'Model' . ucfirst ( static ::$object );
			$sql = "SELECT * FROM " . $table_name[ "name" ];

			$req_prep = Model ::$pdo -> prepare ( $sql );

            $req_prep -> execute ();
			$req_prep -> setFetchMode ( PDO::FETCH_CLASS , $class_name );

			return $req_prep -> fetchAll ();
		}

		/**
		 * @param $primary_value
		 *
		 * @return bool
		 */
		/*
		 * Cette fonction prend en valeur une valeur correspondant à l'une des valeurs de clés primaires
		 * et retourne le premier tuple trouvé.
		 */
		public static function select ( $primary_value )
		{

			$table_name = [ "name" => static ::$object , "primary" => static ::$primary , ];
			$class_name = 'Model' . ucfirst ( static ::$object );
			$sql = "SELECT * from " . $table_name[ "name" ] . " WHERE " . $table_name[ "primary" ] . "=:nom_tag";
			$req_prep = Model ::$pdo -> prepare ( $sql );

			$values = [ "nom_tag" => $primary_value , ];
			$req_prep -> execute ( $values );

			$req_prep -> setFetchMode ( PDO::FETCH_CLASS , $class_name );
			$tab_rep = $req_prep -> fetchAll ();
			if ( empty( $tab_rep ) ) {
				return FALSE;
			}

			return $tab_rep[ 0 ];
		}

		/**
		 * @param $primary_value
		 */

		/*
		* Cette fonction prend en valeur une valeur correspondant à l'une des valeurs de clés primaires
		* et supprime le premier tuple trouvé.
		*/
		public static function delete ( $primary_value )
		{

			$table_name = [ "name" => static ::$object , "primary" => static ::$primary , ];
			$sql = "DELETE FROM " . $table_name[ "name" ] . " WHERE " . $table_name[ "primary" ] . "=:nom_tag";
			$req_prep = Model ::$pdo -> prepare ( $sql );
			$values = [ "nom_tag" => $primary_value , ];
			$req_prep -> execute ( $values );
		}

		/**
		 * @param $data array
		 */
		/*
		 * Cette fonction prend en paramètre un tableau ayant pour index le nom
		 * des attributs de la table excepté l'identifiant et comme valeur,
		 * les valeurs correspondante aux index.
		 */
		public static function save ( $data )
		{
			$table_name = [ "name" => static ::$object ];

			$sqlPart1 = "INSERT INTO " . $table_name[ "name" ] . "(";
			$sqlPart2 = ")VALUES(";
			foreach ( $data as $cle => $v ) {
				$sqlPart1 = $sqlPart1 . $cle . ",";
				$sqlPart2 = $sqlPart2 . ":" . $cle . ",";
			}
			$sql = rtrim ( $sqlPart1 , "," ) . rtrim ( $sqlPart2 , "," ) . ')';

			$req_prep = Model ::$pdo -> prepare ( $sql );
			$req_prep -> execute ( $data );
		}
		/**
		 * @param $data array
		 */
		/*
		 * Cette fonction prend en paramètre un tableau ayant pour
		 * index le nom des attributs de la table et comme valeur,
		 * les valeurs correspondante aux index.
		 */
		public static function update ( $data )
		{

			$table_name = [ "name" => static ::$object , "primary" => static ::$primary , ];
			$class_name = 'Model' . ucfirst ( static ::$object );
			$sql = "UPDATE " . $table_name[ "name" ] . " SET ";
			foreach ( $data as $cle => $v ) {
				if ( strcmp ( $cle , $table_name[ "primary" ] ) != 0 ) {
					$sql = $sql . " " . $cle . "= :" . $cle . " ,";
				}
			}
			$sql = rtrim ( $sql , "," ) . " WHERE " . $table_name[ "primary" ] . " =:" . $table_name[ "primary" ];

			$req_prep = Model ::$pdo -> prepare ( $sql );
			$req_prep -> execute ( $data );
		}
	}

	Model ::Init ();
?>