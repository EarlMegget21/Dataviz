<?php
    foreach ($tab_v as $v) // Display of the cars stored in $tab_v
    echo "Car <a href=http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=read&immatriculation=".rawurlencode($v->getImm()).">".htmlspecialchars($v->getImm())."</a> <a href=http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=delete&immatriculation=".rawurlencode($v->getImm()).">Delete Car</a> <br>";
//rawurlencode() permet d'eviter URL injection, htmlspecialchars permet d'éviter SQL injection
?>
<a href="http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=create">Créer Event</a>
