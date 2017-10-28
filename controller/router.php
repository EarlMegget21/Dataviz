
<?php
    require_once File::build_path(array('controller','ControllerEvent.php'));
    require_once File::build_path(array('controller','ControllerAdmin.php'));
	if ( isset( $_GET[ 'controller' ] ) ) {

        $controller = $_GET[ 'controller' ];
        $model_class = 'Model' . ucfirst ( $controller );
        $controller_class = 'Controller' . ucfirst ( $controller );

        if ( class_exists ( $controller_class ) ) {

            if ( isset( $_GET[ 'action' ] ) ) {
                $action = $_GET[ "action" ];

                switch ( $action ) {
                    case "readAll":
                        $controller_class ::readAll ();
                        break;
                    case "read":
                        $controller_class ::read ( $_GET[ $model_class::getPrimary ()] );
                        break;
                    case "create":
                        $controller_class ::create ();
                        break;
                    case "created":
                        $data= array ();
                        foreach ($_GET as $k=>$v){
                            if(strcmp($k,"action")!=0&& strcmp($k,"controller")!=0){
                                $data+=[$k=>$v];
                            }
                        }
                        $controller_class ::created ( $data );
                        break;
                    case "delete":
                        $controller_class ::delete ( $_GET[ $model_class::getPrimary ()] );
                        break;
                    case "update":
                            $controller_class ::update ();
                        break;
                    case "updated":
                        $data= array ();
                        foreach ($_GET as $k=>$v){
                            if(strcmp($k,"action")!=0&& strcmp($k,"controller")!=0){
                                $data+=[$k=>$v];
                            }
                        }
                        $controller_class ::updated ( $data );
                        break;
                    case "search":
                        $A = array($_GET['coordonneeX1'], $_GET['coordonneeY1']);
                        $B = array($_GET['coordonneeX2'], $_GET['coordonneeY2']);
                        $C = array($_GET['coordonneeX3'], $_GET['coordonneeY3']);
                        $D = array($_GET['coordonneeX4'], $_GET['coordonneeY4']);
                        ControllerEvent::search($_GET['date1'], $_GET['date2'], $A, $B, $C, $D);
                        break;
                    default:
                        require File::build_path(array('view','main','error.php'));
                        break;
                }
            } else {
                $controller_class ::readAll ();
            }
        } else {
            require File::build_path(array('view','main','error.php'));
        }
    } else {
        ControllerEvent ::readAll ();
    }
?>