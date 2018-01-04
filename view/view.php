<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $pagetitle; ?></title>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="./css/style.css">

		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <?php
            if(isset($affiche)) {
                $filepath = File::build_path(array("view", "event", "ScriptMap.php"));
                require $filepath;
            }
        ?>
	</head>
	<body>
		<header>
			<nav>
				<a href="index.php?controller=event">Event</a>

				<?php
					if ( !isset( $_SESSION[ "login" ] ) ) {
						echo "<a href='index.php?action=connect&controller=utilisateurs'>Connect</a>";
					}
					else {
                        if ( $_SESSION[ "isAdmin" ]==1 ) {
                            echo "<a href=\"index.php?controller=utilisateurs\">Utilisateurs</a>";
                        }
                        echo "<a href='index.php?controller=utilisateurs&action=read&login=".$_SESSION[ "login" ]."'>Mon Profil</a>";
						echo "<a href='index.php?action=disconnect&controller=utilisateurs'>Disconnect</a>";
					}
				?>
			</nav>
		</header>
        <?php
                echo "<div id='main'>";
                $filepath = File ::build_path( array ( "view", $object, "$view.php" ) );
                require $filepath;
                echo "</div>";
        ?>
		<footer>
			<p style = "border: 1px solid black;text-align:right;padding-right:1em;">Copyright</p>
		</footer>
	</body>
</html>

