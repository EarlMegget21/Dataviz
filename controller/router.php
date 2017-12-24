<?php
	session_start();
	require_once File ::build_path ( [ 'config' , 'Conf.php' ] );
	require_once File ::build_path( array ( 'controller', 'ControllerEvent.php' ) );
	require_once File ::build_path( array ( 'controller', 'ControllerUtilisateurs.php' ) );
	if (isset( $_GET['controller'] )) {
		$controller = $_GET[ 'controller' ];
		$controller_class = 'Controller' . ucfirst( $controller );
		if (class_exists( $controller_class )) {
			if (isset( $_GET['action'] )) {
				$action = $_GET[ "action" ];
                $actions=get_class_methods($controller_class);
                $valide=false;
                foreach ($actions as $act){ //on test si l'action demandée fait partie des méthodes
                    if($action==$act){
                        $valide=true;
                        break;
                    }
                }
                if($valide){                     //Si l'action n'existe pas pour ce controller alors readAll
                    $controller_class::$action();
                }else {
                    $controller_class::readAll();
                }
			} else {
				$controller_class::readAll();
			}
		} else {
            ControllerUtilisateurs::error();
		}
	} else {
		ControllerEvent::readAll();
	}
?>
