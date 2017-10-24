<?php
// Display of the cars stored in $tab_v
foreach ( $tab_v as $v ) {
    echo '<p><a href=\'./index.php?controller=event&action=read&'
        .ModelEvent::getPrimary ()
        .'='
        . rawurlencode ( $v -> getId () )
        . '\'>'
        . htmlspecialchars ( $v -> getId () ) . '</a>&nbsp'. $v->getNom()
        .'   [<a href=\'./index.php?controller=event&action=delete&'
        . ModelEvent ::getPrimary ()
        . '='
        . rawurlencode ( $v -> getId () )
        . '\'>DELETE</a>]</p>';
}
//<a href="http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=create">Créer Event</a>
?>
