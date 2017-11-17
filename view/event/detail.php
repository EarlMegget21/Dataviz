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
    echo
        "<form method=\"get\" action=\"index.php\">
            <fieldset>
	<legend>Commenter :</legend>
	<p>
		<label for=\"note_id\">Note</label> :
		<input type=\"number\" step=\"1\" min=\"0\" max=\"5\" name=\"note\" id=\"mdp_id\" value=\"0\" required/>
		
		<label for='commentaire_id'>Votre Message</label>
		<textarea placeholder='Laissez votre message ici !' name='commentaire' id='commentaire_id' rows='2' cols='30' required/></textarea>
		
		<input type='hidden' name='login' value='".rawurlencode($_SESSION[ "login" ])."'>
		<input type='hidden' name='action' value='comment'>
		<input type='hidden' name='id' value='".rawurlencode($v->getId())."'>
		<input type='hidden' name='controller' value='event'>
	</p>
	<p>
		<input type=\"submit\" value=\"Envoyer\"/>
	</p>
</fieldset><br>";

}




?>