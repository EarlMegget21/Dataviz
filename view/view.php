<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pagetitle; ?></title>
    </head>
    <body>
        <header>
            <nav>
                <a href="index.php?action=readAll&controller=event">Event</a>
                <a href="index.php?action=readAll&controller=admin">Admin</a>
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

