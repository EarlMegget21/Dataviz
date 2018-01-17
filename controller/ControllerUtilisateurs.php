<?php
require_once File ::build_path ( [ 'model' , 'ModelUtilisateurs.php' ] ); // chargement du modèle

class ControllerUtilisateurs{

    protected static $controller="utilisateurs";

    public static function readAll() {
        if( Session::is_admin()) { //seul les admin peuvent voir tous les utilisateurs
            $tab_v = ModelUtilisateurs::selectAll();     //appel au modèle pour gerer la BD
            $object = 'utilisateurs';
            $view = 'list';
            $pagetitle = 'Liste des utilisateurs';
            require(File::build_path(['view', 'view.php']));  //"redirige" vers la vue
        }else{
            self::connect();
        }
    }


    public static function read() {
        if ( isset($_GET['login']) && ( Session::is_admin() || Session::is_user($_GET["login"] ) ) ) {    //Il faut être un admin ou accéder à ses propres détails
            $v = ModelUtilisateurs ::select ( $_GET['login'] );
            $object = 'utilisateurs';
            $view = 'detail';
            $pagetitle = 'Détail de l\'utilisateur.';
            require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
        } else {
            self::connect ();
        }
    }

    public static function create() {
        if(Session::is_admin ()){ //pour créer il faut être admin
            $object = 'utilisateurs';
            $pagetitle='Création d\'un utilisateur';
            $view='update';
            require File::build_path(array('view','view.php'));
        }else {
            self::connect ();
        }
    }


    public static function created() {
    	$t=self::test();
        if(Session::is_admin()) { //si on est admin on peut créer
            if (strcmp($t["mdp"], $t["mdp_conf"]) == 0) { //si les deux mdp sont identiques
                if (isset($t['isAdmin'])) { // si l'utilisateur est admin
                    $data = array(
                        'login' => $t['login'],
                        'mdp' => Security::chiffrer(Security::getSeed() . $t['mdp']),
                        'isAdmin' => 1
                    );
                } else {
                    $data = array(
                        'login' => $t['login'],
                        'mdp' => Security::chiffrer(Security::getSeed() . $t['mdp']),
                        'isAdmin' => 0
                    );
                }
                ModelUtilisateurs::save($data);
                $tab_v = ModelUtilisateurs::selectAll();
                $object = 'utilisateurs';
                $view = 'created';
                $pagetitle = 'Liste des utilisateurs';
                require(File::build_path(['view', 'view.php']));
            } else {
                $error = "mdp";
                self::error($error);
            }
        }else{
            self::connect ();
        }
    }

    public static function update() {
        if ( isset($_GET["login"]) && ( Session ::is_user ( $_GET["login"] ) || Session::is_admin() ) ) {
            $object = 'utilisateurs';
            $view = 'update';
            $pagetitle = 'Mettre à jour le profil';
            require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
        }else{
            self::connect ();
        }
    }

    public static function updated() {
    	$t=self::test();
		if ( isset($t["login"]) && (Session ::is_user ( $t["login"] ) || Session::is_admin ())){    //A-t-on le droit d'update ?
            if (isset($t["mdp"])&&$t["mdp"]!=""){   //Changement du mdp
                if(strcmp($t["mdp"], $t["mdp_conf"]) == 0){   //mdp et confirmation de mdp OK
                    $data["mdp"] = Security::chiffrer(Security::getSeed() . $t["mdp"]);
                    $data["login"] = $t["login"];
                    if(Session::is_admin ()) { //que l'admin a le droit de modifier
                        if (isset($t["isAdmin"])) { //on passe l'user en admin
                            $data["isAdmin"] = $t["isAdmin"];
                        } else {
                            $data["isAdmin"] = 0;
                        }
                    }else{
                        $data["isAdmin"] = 0;
                    }
                    ModelUtilisateurs::update($data);
                    $view = 'updated';
                    $pagetitle = 'Profil mit à jour';
                    $object = 'utilisateurs';
                    require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
                }else{
                    self::error("mdp");
                }
            }else{  //Pas de changement du mdp
                $data["login"] = $t["login"];
                if(Session::is_admin ()) { //que l'admin a le droit de modifier
                    if (isset($t["isAdmin"])) { //on passe l'user en admin
                        $data["isAdmin"] = $t["isAdmin"];
                    } else {
                        $data["isAdmin"] = 0;
                    }
                }else{
                    $data["isAdmin"] = 0;
                }
                ModelUtilisateurs::update($data);
                $view = 'updated';
                $pagetitle = 'Profil mit à jour';
                $object = 'utilisateurs';
                require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
            }
        } else {
            self::connect();
        }
    }


    public static function delete() {
    	$t=self::test();
        if(isset($_GET["login"])) {
            $primary = $_GET["login"];
            if (Session::is_user($primary)||Session::is_admin()) {
                ModelUtilisateurs::delete($primary);
               	if(Session::is_user($primary)){
	                session_unset();
	                session_destroy();
	                setcookie(session_name(), '', time() - 1);
	            }
            } else {
                self::connect();
            }
        }else{
            self::connect ();
        }
    }

    public static function connect() {
        if ( !isset( $_SESSION[ "login" ] ) ) {
            $object = 'utilisateurs';
            $view = 'connect';
            $pagetitle = 'Connection à la page utilisateur';
            require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
        } else {
            self ::read ($_SESSION[ "login" ]);
        }
    }

    public static function connected() {
    	$t=self::test();
       if(isset( $t["login"])&& isset( $t["mdp"])) {
            $login = $t["login"];
            $mdp = Security::getSeed() . $t["mdp"];
        }else{
            self ::connect();
        }
        if ( !isset( $_SESSION[ "login" ] ) ) {
            $g = ModelUtilisateurs ::checkPassword ( $login , $mdp );
            if ( $g !== FALSE ) {
                $_SESSION[ "login" ] = $g -> getLogin ();
                $_SESSION[ "isAdmin" ] = $g -> getIsAdmin ();
            } else {
                self ::error("mdp2");
            }
        }
        $v = ModelUtilisateurs ::select ( $_SESSION['login'] );
        $object = 'utilisateurs';
        $view = 'detail';
        $pagetitle = 'Détail de l\'utilisateur.';
        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
    }

    public static function disconnect() {
        if ( isset( $_SESSION[ "login" ] ) ) {
            session_unset ();
            session_destroy ();
            setcookie ( session_name () , '' , time () - 1 );
        }
        self::connect ();
    }

    public static function test(){
    	$t=[];
    	if(Conf::getDebug()){
	    	if(isset($_GET[ "login" ])){
	    		$t["login"]=$_GET[ "login" ];
		    }
		    if(isset($_GET[ "mdp" ])){
		    	$t["mdp"]=$_GET[ "mdp" ];
		    }
		    if(isset($_GET[ "mdp_conf" ])){
		    	$t["mdp_conf"]=$_GET[ "mdp_conf" ];
		    }
		    if(isset($_GET[ "isAdmin" ])){
		    	$t["isAdmin"]=$_GET[ "isAdmin" ];
		    }
		}else{
			if(isset($_POST[ "login" ])){
	    		$t["login"]=$_POST[ "login" ];
		    }
		    if(isset($_POST[ "mdp" ])){
		    	$t["mdp"]=$_POST[ "mdp" ];
		    }
		    if(isset($_POST[ "mdp_conf" ])){
		    	$t["mdp_conf"]=$_POST[ "mdp_conf" ];
		    }
		    if(isset($_POST[ "isAdmin" ])){
		    	$t["isAdmin"]=$_POST[ "isAdmin" ];
		    }
		}
		return $t;
    }

    public static function generate($n){
        for($i=0;$i<$n;$i++){
            $mdp="dataviz";
            $login=self::generateRandomString ();
            ModelUtilisateurs::save (["login"=>$login,"mdp"=>Security::chiffrer ($mdp),"isAdmin"=>0]);
        }
        self::readAll ();
    }

    private static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function error($e){
        $error=$e;
        $object = 'utilisateurs';
        $view = 'error';
        $pagetitle = 'Erreur';
        require( File ::build_path( [ 'view', 'view.php' ] ) );
    }
}
