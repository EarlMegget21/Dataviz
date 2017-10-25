<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pagetitle; ?></title>
    </head>
    <body>
        <header>
            <nav>
                <a href="http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=readAll">Home</a>
                <a href="http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=readAll&controller=user">Profil</a>
                <a href="http://webinfo.iutmontp.univ-montp2.fr/~sonettir/PHP/TD2/index.php?action=readAll&controller=journey">Journey</a>
            </nav>
        </header>
<?php
// Si $controller='event' et $view='ListEvent',
// alors $filepath="/chemin_du_site/view/event/list.php"
$filepath = File::build_path(array("view", $object,
    "$view.php"));
require $filepath;
?>
        <footer>
            <p style="border: 1px solid black;text-align:right;padding-right:1em;">Copyright</p>
        </footer>
    </body>
</html>

