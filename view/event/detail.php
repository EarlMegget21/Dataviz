<?php
require_once File ::build_path( array ( 'model', 'ModelCommentaire.php' ) );

echo "Titre: "
    . htmlspecialchars( $v -> getNom() )
    . "<br>Date: "
    . htmlspecialchars( $v -> getDate() )
    . "<br>Coordonnée:<br>&nbsp&nbspLongitude:" //&nbsp fait une tabulation
    . htmlspecialchars( $v -> getLongitude() )
    . "<br>&nbsp&nbspLatitude:"
    . htmlspecialchars( $v -> getLatitude() )
    . "<br>Description:<br><br>"
    . htmlspecialchars( $v -> getDescription() )
    . "<br><br>Auteur :"
    . htmlspecialchars( $v -> getLogin() )
    . "<br>";

if (isset( $_SESSION[ "login" ] ) && ( $_SESSION[ "isAdmin" ] == 1 && strcmp( $v -> getLogin(), $_SESSION[ "login" ] ) == 0 )) {
    echo "<br><a href=index.php?controller=event&action=update&"
        . ModelEvent ::getPrimary()
        . '='
        . rawurlencode( $v -> getId() )
        . ">Update</a> <a href=index.php?controller=event&action=delete&"
        . ModelEvent ::getPrimary()
        . '='
        . rawurlencode( $v -> getId() )
        . ">Delete</a> <br>";
}

$comments = ModelCommentaire::getAllComments($v -> getId()); //TODO: Mettre ça dans le select de ControllerEvent

if(empty($comments)){
    echo 'Aucun commentaire';
}else {
    echo "<h2>Commentaires</h2>";
    foreach ($comments as $c) {   //Affiche tous les commentaires
        echo '<p>Note: ' . $c->getNote() . '/5 <br>'
            . 'Commentaire: ' . $c->getTexte() . '<br>'
            . 'Utilisateur: ' . $c->getLogin() . '</p>';
    }
}

if(isset($_SESSION["login"])){  //Bouton pour commenter
    //TODO: Changer ce bouton en form intégré au reste des infos
    echo "<a href=index.php?controller=event&action=comment&"
        . ModelEvent::getPrimary()
        .'='
        .rawurldecode($v -> getId())
        .">Commenter et noter</a> <br>";
    //TODO: Faire en sorte de noter et de laisser un commentaire concernant un event
}




?>