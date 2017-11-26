<?php
	session_start();
	require_once File ::build_path( array ( 'controller', 'ControllerEvent.php' ) );
	require_once File ::build_path( array ( 'controller', 'ControllerUtilisateurs.php' ) );
	if (isset( $_GET[ 'controller' ] )) {

		$controller = $_GET[ 'controller' ];
		$model_class = 'Model' . ucfirst( $controller );
		$controller_class = 'Controller' . ucfirst( $controller );
		if (class_exists( $controller_class )) {

			if (isset( $_GET[ 'action' ] )) {
				$action = $_GET[ "action" ];
				switch ($action) {
					case "readAll":
						$controller_class ::readAll();
						break;
					case "read":
						$controller_class ::read( $_GET[ $model_class ::getPrimary() ] );
						break;
					case "created":
						$data = array ();
						foreach ($_GET as $k => $v) {
							if (strcmp( $k, "action" ) != 0 && strcmp( $k, "controller" ) != 0) {
								$data += [ $k => $v ];
							}
						}
						$controller_class ::created( $data );
						break;
					case "delete":
						$controller_class ::delete( $_GET[ $model_class ::getPrimary() ] );
						break;
					case "update":
						$controller_class ::update();
						break;
					case "updated":
						$data = array ();
						foreach ($_GET as $k => $v) {
							if (strcmp( $k, "action" ) != 0 && strcmp( $k, "controller" ) != 0) {
								$data += [ $k => $v ];
							}
						}
						$controller_class ::updated( $data );
						break;
					case "search":
                        $A = [ $_GET[ 'longitude1' ], $_GET[ 'latitude2' ] ];
                        $B = [ $_GET[ 'longitude2' ], $_GET[ 'latitude1' ] ];
						if(isset($_GET['motCle'])){
                            ControllerEvent ::search( $_GET[ 'date1' ], $_GET[ 'date2' ], $A, $B, $_GET['motCle']);
                        }else{
                            ControllerEvent ::search( $_GET[ 'date1' ], $_GET[ 'date2' ], $A, $B );
                        }
						break;
					case "connect":
						$controller_class::connect();
						break;
					case "connected":
						$controller_class::connected($_GET["login"],$_GET["mdp"]);
						break;
					case "disconnect":
						$controller_class::disconnect();
						break;
					case "generate":
						$controller_class::generate($_GET["n"]);
						break;
					case "comment":
						$controller_class::comment();
					default:
						ControllerUtilisateurs::error();
						break;
				}
			} else {
				$controller_class::readAll();
			}
		} else {
			require File ::build_path( array ( 'view', 'main', 'error.php' ) );
		}
	} else {
		ControllerEvent ::readAll();
	}
?>
