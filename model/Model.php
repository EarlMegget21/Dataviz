<?php
require_once File::build_path(array('config','Conf.php'));

/**
 * Description of model
 *
 * @author sonettir
 */
class Model {
    public static $pdo;

    public static function Init(){
        $hostname=Conf::getHostname();
        $login=Conf::getLogin();
        $password=Conf::getPassword();
        $database=Conf::getDatabase();
        try{
            self::$pdo=new PDO("mysql:host=$hostname;dbname=$database",$login,$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
           if (Conf::getDebug()) {
              echo $e->getMessage(); // affiche un message d'erreur
           } else {
              echo 'Une erreur est survenue <a href=""> retour a la page d\'accueil </a>';
           }
            die(); //supprimer equilvalent à System.exit(1); en java
        }
            
    }
}
Model::Init();