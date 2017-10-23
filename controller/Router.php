<?php

require_once File::build_path(array('controller','ControllerEvent.php'));
require_once File::build_path(array('controller','ControllerAdmin.php'));

if(array_key_exists("controller", $_GET)){ 
    $controller = $_GET["controller"];    
}else{
    $controller = 'event';
}
$controller_class = 'controller'.ucfirst($controller);
if(class_exists($controller_class)){
    if(array_key_exists("action", $_GET)){
        $action = $_GET["action"];
        $actions=get_class_methods($controller_class);
        $valide=false;
        foreach ($actions as $act){
            if($action==$act){
                $valide=true;
                break;
            }
        }
        if($valide){
           $controller_class::$action();
        }else{
           $pagetitle='Error!';
           $view='Error';
           $controller='main';
           require File::build_path(array('view','view.php'));
       }
    }else{
        $controller_class::readAll();
    }
}else{
    $pagetitle='Error!';
    $view='Error';
    $controller='main';
    require File::build_path(array('view','view.php'));
}
