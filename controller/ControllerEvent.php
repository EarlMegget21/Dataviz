<?php
require_once File::build_path(array('Model', 'ModelEvent.php')); // chargement du modèle
class ControllerEvent
{

    public static function readAll()
    {

        $tab_v = ModelEvent::selectAll();     //appel au modèle pour gerer la BD
        $object = 'event';
        $view = 'list';
        $pagetitle = 'Liste des events';
        require(File::build_path(['view', 'view.php']));  //"redirige" vers la vue
    }


    public static function read($primary)
    {
        $v = ModelEvent::select($primary);
        $object = 'event';
        $view = 'detail';
        $pagetitle = 'Détail de l\'event.';
        require(File::build_path(['view', 'view.php']));  //"redirige" vers la vue
    }


    public static function created($data)
    {
        ModelEvent::save($data);
        $tab_v = ModelEvent::selectAll();
        $object = 'event';
        $view = 'created';
        $pagetitle = 'Liste des events';
        require(File::build_path(['view', 'view.php']));
    }


    public static function update()
    {
        $object = 'event';
        $view = 'update';
        $pagetitle = 'Event update';
        require(File::build_path(['view', 'view.php']));
    }


    public static function updated($data)
    {
        ModelEvent::update($data);
        $object = 'event';
        $view = 'updated';
        $pagetitle = 'Event updated';
        $tab_v = ModelEvent::selectAll();
        require(File::build_path(['view', 'view.php']));
    }


    public static function delete($primary)
    {
        ModelEvent::delete($primary);
        $tab_v = ModelEvent::selectAll();
        $object = 'event';
        $view = 'delete';
        $pagetitle = 'Event supprimé';
        require(File::build_path(['view', 'view.php']));
    }

    public static function search($date1, $date2, $A, $B, $C, $D)
    {
        $tab_v = ModelEvent::searchEventPosDate($date1, $date2, $A, $B, $C, $D);
        $object = 'event';
        $view = 'list';
        $pagetitle = 'Liste de la recherche';
        require(File::build_path(['view', 'view.php']));
    }

}
