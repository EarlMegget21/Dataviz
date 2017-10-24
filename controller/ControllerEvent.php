<?php
require_once File::build_path(array('Model','ModelEvent.php')); // chargement du modèle
class ControllerEvent {
    public static function readAll() {
        $tab_v = ModelEvent::selectAll(); //appel au modèle pour gerer la BD
          //"redirige" vers la vue (pas require_once car on peut appeler plusieur fois dans le code pour 'copier' le html à la manière d'un include en C
        $pagetitle='ListEvent';
        $view='list';
        $controller='event';
        require File::build_path(array('view','view.php'));
    }
    public static function read() {
        $immat=$_GET['immatriculation'];
        if(!$v = ModelEvent::getEventByImmat($immat)){
            $pagetitle='Error!';
            $view='Error';
            $controller='main';
            require File::build_path(array('view','view.php'));
        } else {
            $pagetitle='DetailEvent';
            $view='DetailEvent';
            $controller='event';
            require File::build_path(array('view','view.php'));
        }   
    }
    public static function create() {
        $pagetitle='Create';
        $view='Create';
        $controller='event';
        require File::build_path(array('view','view.php'));
    }
    public static function created() {
//        $tab_v=array(
//            'marque'=>$_GET['marque'],
//            'couleur'=>$_GET['couleur'],
//            'immatriculation'=>$_GET['immatriculation']);
        $car1=new ModelEvent($_GET["marque"], $_GET["couleur"], $_GET["immatriculation"]);
        if(!$car1->save()){ //NULL est interprété comme non vrai aussi donc soit on return true en cas de succès soit on teste si $car1->save() === false (le === check si c'est bien un boolean et si c'est false donc si c'est NULL ça ne sera pas un boolean)
            $pagetitle='Error!';
            $view='Error';
            $controller='main';
            require File::build_path(array('view','view.php'));
        } else {
            $tab_v = ModelEvent::getAllEvent();
            $pagetitle='ListEvent';
            $view='Created';
            $controller='event';
            require File::build_path(array('view','view.php'));
        }
    }
    public static function update() {
        $pagetitle='Update';
        $view='Update';
        $controller='event';
        require File::build_path(array('view','view.php'));
    }
    public static function updated() {
//        $tab_v=array(
//            'marque'=>$_GET['marque'],
//            'couleur'=>$_GET['couleur'],
//            'immatriculation'=>$_GET['immatriculation']);
        $car1=new ModelEvent($_GET["marque"], $_GET["couleur"], $_GET["immatriculation"]);
        if(!$car1->update($_GET['immatriculation'])){ //NULL est interprété comme non vrai aussi donc soit on return true en cas de succès soit on teste si $car1->save() === false (le === check si c'est bien un boolean et si c'est false donc si c'est NULL ça ne sera pas un boolean)
            $pagetitle='Error!';
            $view='Error';
            $controller='main';
            require File::build_path(array('view','view.php'));
        } else {
            $v = ModelEvent::getEventByImmat($_GET["immatriculation"]);
            $pagetitle='DetailEvent';
            $view='Updated';
            $controller='event';
            require File::build_path(array('view','view.php'));
        }
    }
    public static function delete() {
        ModelEvent::delete($_GET['immatriculation']);
        $tab_v = ModelEvent::getAllEvent();
        $pagetitle='ListEvent';
        $view='Deleted';
        $controller='event';
        require File::build_path(array('view','view.php'));
    }
}




//$rep=model::$pdo->query('SELECT * FROM event');
//$tab_obj=$rep->fetchAll(PDO::FETCH_OBJ); //créer tableau d'objets avec attributs d'objets=attributs de table
//$rep->setFetchMode(PDO::FETCH_CLASS, 'ModelEvent'); //permet de créer une classe dans le même principe qu'au dessus mais on peut la renommer et définir des méthodes etc
//$tab_voit=$rep->fetchAll(PDO::FETCH_CLASS, 'ModelEvent'); //si on met la ligne du dessus, on appel fetchAll sans paramêtres
//foreach (ModelEvent::getAllEvent() as $key => $value) {
////    echo $value->marque.$value->immatriculation.$value->couleur;
//    echo $value->display();
//}
//echo event::getEventByImmat('21XYZ34')->display();
//$lambo=new ModelEvent('Lambo', 'jaune', 'XXXXX2');
//$lambo->save();
//echo ModelEvent::getEventByImmat('XXXXX2')->display();