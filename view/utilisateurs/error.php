<?php
if(isset($error)){
    switch ($error) {
        case "notFound":
            echo "Oups! 404 not Found";
            break;
        case "mdp":
            echo "Les deux mots de passe que vous avez rentrés sont différents !";
            break;
        case "mdp2":
            echo "Le mot de passe est incorrecte";
            break;
        default:
            echo "Vous ne pouvez pas accéder à ces informations !";
    }
}else{
    echo "Il y a eu une erreur !";
}
?>