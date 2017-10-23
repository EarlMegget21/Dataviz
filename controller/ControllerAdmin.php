<?php
//Ctrl+H permet de remplacer les mots par un autre event->admin
require_once File::build_path(array('Model','ModelAdmin.php')); // chargement du modèle
class ControllerAdmin {
    public static function readAll() {
        $tab_v = ModelAdmin::getAllAdmin();     //appel au modèle pour gerer la BD
          //"redirige" vers la vue (pas require_once car on peut appeler plusieur fois dans le code pour 'copier' le html à la manière d'un include en C
        $pagetitle='ListAdmin';
        $view='ListAdmin';
        $controller='admin';
        require File::build_path(array('view','view.php'));
    }
    public static function read() {
        $immat=$_GET['immatriculation'];
        if(!$v = ModelAdmin::getAdminByImmat($immat)){
            $pagetitle='Error!';
            $view='Error';
            $controller='main';
            require File::build_path(array('view','view.php'));
        } else {
            $pagetitle='DetailAdmin';
            $view='DetailAdmin';
            $controller='admin';
            require File::build_path(array('view','view.php'));
        }   
    }
    public static function create() {
        $pagetitle='Create';
        $view='Create';
        $controller='admin';
        require File::build_path(array('view','view.php'));
    }
    public static function created() {
//        $tab_v=array(
//            'marque'=>$_GET['marque'],
//            'couleur'=>$_GET['couleur'],
//            'immatriculation'=>$_GET['immatriculation']);
        $car1=new ModelAdmin($_GET["marque"], $_GET["couleur"], $_GET["immatriculation"]);
        if(!$car1->save()){ //NULL est interprété comme non vrai aussi donc soit on return true en cas de succès soit on teste si $car1->save() === false (le === check si c'est bien un boolean et si c'est false donc si c'est NULL ça ne sera pas un boolean)
            $pagetitle='Error!';
            $view='Error';
            $controller='main';
            require File::build_path(array('view','view.php'));
        } else {
            $tab_v = ModelAdmin::getAllAdmin();
            $pagetitle='ListAdmin';
            $view='Created';
            $controller='admin';
            require File::build_path(array('view','view.php'));
        }
    }
    public static function update() {
        $pagetitle='Update';
        $view='Update';
        $controller='admin';
        require File::build_path(array('view','view.php'));
    }
    public static function updated() {
//        $tab_v=array(
//            'marque'=>$_GET['marque'],
//            'couleur'=>$_GET['couleur'],
//            'immatriculation'=>$_GET['immatriculation']);
        $car1=new ModelAdmin($_GET["marque"], $_GET["couleur"], $_GET["immatriculation"]);
        if(!$car1->update($_GET['immatriculation'])){ //NULL est interprété comme non vrai aussi donc soit on return true en cas de succès soit on teste si $car1->save() === false (le === check si c'est bien un boolean et si c'est false donc si c'est NULL ça ne sera pas un boolean)
            $pagetitle='Error!';
            $view='Error';
            $controller='main';
            require File::build_path(array('view','view.php'));
        } else {
            $v = ModelAdmin::getAdminByImmat($_GET["immatriculation"]);
            $pagetitle='DetailAdmin';
            $view='Updated';
            $controller='admin';
            require File::build_path(array('view','view.php'));
        }
    }
    public static function delete() {
        ModelAdmin::delete($_GET['immatriculation']);
        $tab_v = ModelAdmin::getAllAdmin();
        $pagetitle='ListAdmin';
        $view='Deleted';
        $controller='admin';
        require File::build_path(array('view','view.php'));
    }
}
