<?php
	session_start();
	require_once File ::build_path( array ( 'controller', 'ControllerEvent.php' ) );
	require_once File ::build_path( array ( 'controller', 'ControllerUtilisateurs.php' ) );
	if (isset( $_GET[ 'action' ] )) {
		$action = $_GET[ "action" ];


		if (isset( $_GET[ 'controller' ] )) {
			$controller = $_GET[ 'controller' ];
			$model_class = 'Model' . ucfirst( $controller );
			$controller_class = 'Controller' . ucfirst( $controller );

			if (class_exists( $controller_class )) {
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

						//Ces conditions permettent d'ordonner les coordonnées pour que le trie marche sans vraiment
						//se préoccuper de l'ordre des coordonnées car si on se trouve de l'autre côté de la Terre, les coordonnées longitude sont inversées

						if ($_GET[ 'longitude1' ] > $_GET[ 'longitude2' ]) {
                            $A = [ $_GET[ 'longitude2' ], $_GET[ 'latitude2' ] ];
                            $B = [ $_GET[ 'longitude1' ], $_GET[ 'latitude1' ] ];
						} else {
                            $A = [ $_GET[ 'longitude1' ], $_GET[ 'latitude2' ] ];
                            $B = [ $_GET[ 'longitude2' ], $_GET[ 'latitude1' ] ];
						}
						if(isset($_GET['motCle'])){
                            ControllerEvent ::search( $_GET[ 'date1' ], $_GET[ 'date2' ], $A, $B, $_GET['motCle']);
                        }else{
                            ControllerEvent ::search( $_GET[ 'date1' ], $_GET[ 'date2' ], $A, $B );
                        }
						break;

					default:

						$object = 'main';
						$view = 'error';
						$pagetitle = 'Erreur';
						require( File ::build_path( [ 'view', 'view.php' ] ) );
						break;
				}
			} else {
				require File ::build_path( array ( 'view', 'main', 'error.php' ) );
			}
		} else {

			//Switch pour les actions en dehors des objets ControllerUtilisateurs et ControllerEvent
			//TODO: Mettre la connection et déconnection dans le ControllerUtilisateur

			switch ($action) {
				case "connect":
					//Cette action a 2 rôles: Rediriger sur la page connect, et de verification de connection.
					//Pour l'instant la connection se fait seulement avec le login
					//Vérifie que l'utilisateur est connecté: Si oui il ne peut pas se reconnecter autrement il peut se connecter.
					if (!isset( $_SESSION[ "login" ] )) {
						//Ici il vérifie si l'accès à cette action viens d'une page quelconque ou de la page connect.
						if (isset( $_GET[ "login" ] )) {
							$g = ModelUtilisateurs ::select( $_GET[ "login" ] );
							if ($g !== FALSE) {
								$_SESSION[ "login" ] = $g -> getLogin();
								$_SESSION[ "isAdmin" ] = $g -> getIsAdmin();

							}
							ControllerEvent ::readAll();
						} else {

							$object = 'main';
							$view = 'connect';
							$pagetitle = 'Connection à la page utilisateur';
							require( File ::build_path( [ 'view', 'view.php' ] ) );
						}
					} else {
						ControllerEvent ::readAll();
					}
					break;
				case "disconnect":
					if (isset( $_SESSION[ "login" ] )) {
						session_unset();     // unset $_SESSION variable for the run-time
						session_destroy();   // destroy session data in storage
						// Il faut réappeler session_start() pour accéder de nouveau aux variables de session
						setcookie( session_name(), '', time() - 1 ); // deletes the session cookie containing the session ID
					}
					ControllerEvent ::readAll();
					break;
				default:

					break;
			}
		}
	} else {
		ControllerEvent ::readAll();
	}
?>