<?php
    if ($v !== FALSE) {
        echo "<p>Login:"
            . $v->getLogin()
            . "<br> MDP:"
            . $v->getMdp()
            ."<br><a href=index.php?controller=admin&action=update&"
            .ModelAdmin::getPrimary ()
            .'='
            . rawurlencode ( $v -> getLogin () )
            .">Update</a> <a href=index.php?controller=admin&action=delete&"
            .ModelAdmin::getPrimary ()
            .'='
            . rawurlencode ( $v -> getLogin () )
            .">Delete</a> <br>";
} else {
    require File::build_path(array('view', 'admin', 'error.php'));
}
?>