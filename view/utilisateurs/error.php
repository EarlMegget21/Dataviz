<?php
switch ($error) {
    case "mdp":
        echo "Les deux mots de passe que vous avez rentrés sont différents !";
        break;
    default:
        echo "Vous ne pouvez pas accéder à ces informations ! :)";
}
require File::build_path(array('view','utilisateurs','list.php'));
?>