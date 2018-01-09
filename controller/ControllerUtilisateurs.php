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
            ControllerEvent::readAll();
        }
    }

    public static function create() {
        if(Session::is_admin ()){ //pour créer il faut être admin
            $object = 'utilisateurs';
            $pagetitle='Création d\'un utilisateur';
            $view='update';
            require File::build_path(array('view','view.php'));
        }else {
            ControllerEvent::readAll();
        }
    }


    public static function created() {
        if(Session::is_admin()) { //si on est admin on peut créer
            if (strcmp($_GET["mdp"], $_GET["mdp_conf"]) == 0) { //si les deux mdp sont identiques
                if (isset($_GET['isAdmin'])) { // si l'utilisateur est admin
                    $data = array(
                        'login' => $_GET['login'],
                        'mdp' => Security::chiffrer(Security::getSeed() . $_GET['mdp']),
                        'isAdmin' => 1
                    );
                } else {
                    $data = array(
                        'login' => $_GET['login'],
                        'mdp' => Security::chiffrer(Security::getSeed() . $_GET['mdp']),
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
            ControllerEvent::readAll();
        }
    }

    public static function update() {
        if ( isset($_GET["login"]) && ( Session ::is_user ( $_GET["login"] ) || Session::is_admin() ) ) {
            $object = 'utilisateurs';
            $view = 'update';
            $pagetitle = 'Mettre à jour le profil';
            require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
        }else{
            ControllerEvent::readAll ();
        }
    }


    public static function updated() {
        if ( isset( $_GET[ "login" ] ) && (Session ::is_user ( $_GET[ "login" ] ) || Session::is_admin ())){    //A-t-on le droit d'update ?
            if (isset($_GET["mdp"])){   //Changement du mdp
                if(strcmp($_GET["mdp"], $_GET["mdp_conf"]) == 0){   //mdp et confirmation de mdp OK
                    $data["mdp"] = Security::chiffrer($_GET["mdp"]);
                    $data["login"] = $_GET["login"];
                    if(Session::is_admin ()) {
                        if (isset($_GET["isAdmin"])) {
                            $data["isAdmin"] = $_GET["isAdmin"];
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
                    $error = "mdp";
                    self::error($error);
                }
            }else{  //Pas de changement du mdp
                $data["login"] = $_GET["login"];
                if (isset($_GET["isAdmin"])) {
                    $data["isAdmin"] = $_GET["isAdmin"];
                } else {
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
        if(isset($_GET["login"])) {
            $primary = $_GET["login"];
            if (Session::is_user($primary)) {
                ModelUtilisateurs::delete($primary);
                $object = 'utilisateurs';
                $view = 'delete';
                $pagetitle = 'Utilisateur supprimé';
                $tab_v = ModelUtilisateurs::selectAll();

                session_unset();
                session_destroy();
                setcookie(session_name(), '', time() - 1);
                require(File::build_path(['view', 'view.php']));
            } else {
                self::connect();
            }
        }else{
            ControllerEvent::readAll();
        }
    }

    public static function connect() {
        if ( !isset( $_SESSION[ "login" ] ) ) {
            $object = 'utilisateurs';
            $view = 'connect';
            $pagetitle = 'Connection à la page utilisateur';
            require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
        } else {
            ControllerEvent ::readAll ();
        }
    }

    public static function connected() {
        if ( Conf::getDebug() ) {
            if(isset( $_GET["login"])&& isset( $_GET["mdp"])) {
                $login = $_GET["login"];
                $mdp = $_GET["mdp"];
            }else{
                self ::connect();
            }
        }else{
            if(isset( $_POST["login"])&& isset( $_POST["mdp"])) {
                $login = $_POST["login"];
                $mdp = $_POST["mdp"];
            }else{
                self ::connect();
            }
        }
        if ( !isset( $_SESSION[ "login" ] ) ) {
            $g = ModelUtilisateurs ::checkPassword ( $login , $mdp );
            if ( $g !== FALSE ) {
                $_SESSION[ "login" ] = $g -> getLogin ();
                $_SESSION[ "isAdmin" ] = $g -> getIsAdmin ();
                self ::read($_SESSION["login"]);
            } else {
                self ::error("mdp2");
            }
        } else {
            self ::connect();
        }
    }

    public static function disconnect() {
        if ( isset( $_SESSION[ "login" ] ) ) {
            session_unset ();
            session_destroy ();
            setcookie ( session_name () , '' , time () - 1 );
        }
        ControllerEvent ::readAll ();
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
