<?php
    echo "Titre: "
        .htmlspecialchars($v->getNom())
        ."<br>Date: "
        .htmlspecialchars($v->getDate())
        ."<br>Coordonnée:<br>&nbsp&nbspx:"
        .htmlspecialchars($v->getCoordonneeX())
        ."<br>&nbsp&nbspy:"
        .htmlspecialchars($v->getCoordonneeY())
        ."<br>Description:<br><br>"
        .htmlspecialchars($v->getDescription())
        ."<br><br>Ajouté par :"
        .htmlspecialchars($v->getLogin())
        ."<br>ID:"
        .htmlspecialchars($v->getId())
        ."<br><a href=index.php?controller=event&action=update&"
        .ModelEvent::getPrimary ()
        .'='
        . rawurlencode ( $v -> getId () )
        .">Update</a> <a href=index.php?controller=event&action=delete&"
        .ModelEvent::getPrimary ()
        .'='
        . rawurlencode ( $v -> getId () )
        .">Delete</a> <br>";
?>