<?php
	require_once File ::build_path ( [ 'config' , 'Conf.php' ] );

	/**
	 * Description of model
	 *
	 * @author sonettir
	 */
	class Model
	{
		public static $pdo;
		protected static $object;
		protected static $primary;

		public static function Init ()
		{
			$hostname = Conf ::getHostname ();
			$login = Conf ::getLogin ();
			$password = Conf ::getPassword ();
			$database = Conf ::getDatabase ();
			try {
				self ::$pdo = new PDO( "mysql:host=$hostname;dbname=$database" , $login , $password , [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ] );
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

	}

	Model ::Init ();