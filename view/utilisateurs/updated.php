<?php
    if(Session::is_admin()){
        echo '<p>L\'utilisateurs a bien été modifié !</p>';
        $tab_v = ModelUtilisateurs::selectAll();
        require File::build_path(array('view','utilisateurs','list.php'));
    }else{
        echo '<p>Compte mis à jour !</p>';
        $v = ModelUtilisateurs ::select ( $_GET['login'] );
        require File::build_path(array('view','utilisateurs','detail.php'));
    }

