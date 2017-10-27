<?php
//Ctrl+H permet de remplacer les mots par un autre event->admin
require_once File::build_path(array('model','ModelAdmin.php')); // chargement du modèle
class ControllerAdmin {


    public static function readAll() {

        $tab_v = ModelAdmin ::selectAll ();     //appel au modèle pour gerer la BD
        $object = 'admin';
        $view = 'list';
        $pagetitle = 'Liste des admins';
        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
    }


    public static function read($primary) {

        $v = ModelAdmin ::select ( $primary );
        $object = 'admin';
        $view = 'detail';
        $pagetitle = 'Détail de l\'admin.';
        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );  //"redirige" vers la vue
    }


    public static function created ( $data )
    {
        ModelAdmin::save ($data);
        $tab_v = ModelAdmin ::selectAll ();
        $object = 'admin';
        $view = 'created';
        $pagetitle = 'Liste des admin';
        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
    }



    public static function update ()
    {
        $object = 'admin';
        $view = 'update';
        $pagetitle = 'Admin update';
        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
    }




    public static function updated ( $data )
    {
        ModelAdmin ::update ( $data );

        $object = 'admin';
        $view = 'updated';
        $pagetitle = 'Admin updated';
        $tab_v = ModelAdmin ::selectAll ();

        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
    }



    public static function delete ( $primary )
    {
        ModelAdmin ::delete ( $primary );
        $tab_v = ModelAdmin ::selectAll ();

        $object = 'admin';
        $view = 'delete';
        $pagetitle = 'Admin supprimé';
        require ( File ::build_path ( [ 'view' , 'view.php' ] ) );
    }


}
