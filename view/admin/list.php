<?php
    foreach ($tab_v as $v){
        echo
            "<p>Login: <a href=index.php?controller=admin&action=read&"
            .ModelAdmin::getPrimary ()
            ."="
            .rawurlencode($v->getLogin())
            .">"
            .htmlspecialchars($v->getLogin())
            ."</a></p>";
    }
?>
<br>
<a href="index.php?controller=admin&action=update">Créer Admin</a>
