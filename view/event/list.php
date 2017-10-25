<?php
// Display of the cars stored in $tab_v
foreach ( $tab_v as $v ) {
    echo '<p><a href=\'./index.php?controller=event&action=read&'
        .ModelEvent::getPrimary ()
        .'='
        . rawurlencode ( $v -> getId () )
        . '\'>'
        . htmlspecialchars ( $v -> getId () )
        . '</a>&nbsp'
        . $v->getNom();

}
?>
<br>
<a href="index.php?controller=event&action=update">Créer évenement</a>
