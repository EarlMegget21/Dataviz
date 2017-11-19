<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $pagetitle; ?></title>
		<!-- CSS temporel pour redimensionner la map -->
		<style>
			#map {
				height: 400px;
				width: 100%;
			}
        </style>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script> //script pour le slider
            $(function () {
                $("#slider").slider({
                    range: true,
                    min: 1900,
                    max: 2017,
                    values: [1900, 2017],
                    slide: function (event, ui) {
                        $("#date1_id").val(ui.values[0] + "-01-01");
                        $("#date2_id").val(ui.values[1] + "-31-12");
                        $("#date").val("Du 01/01/"+ui.values[0] +" au 31/12/"+ui.values[1]);
                    }
                });
                $("#date1_id").val($("#slider").slider("values", 0)+"-01-01");
                $("#date2_id").val($("#slider").slider("values", 1)+"-31-12");
                $("#date").val("Du 01/01/"+$("#slider").slider("values", 0)+" au 31/12/"+$("#slider").slider("values", 1));
                $(".ui-widget-header").css("background-color", "grey");
                $(".ui-widget-content").css("background", "#dddddd");
                $(".ui-state-default").css("background-color", "#5E5DFF");
            });

        </script>
        <?php
            if(isset($_GET['controller'])&&$_GET['controller']=='event') {
                echo '<script async defer
                    src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCuog5LlTmtUH8-wB5IjxdJMY_Cq-CqhVU&language=fr&callback=initMap"></script>'; // include l'API Javascript grâce à notre Clé
                echo '<script>
                    var bounds = {'; //définit les bords de la map
                echo 'north: ' . $tab_minmax['maxLat'] . ',
                        south: ' . $tab_minmax['minLat'] . ',
                        east: ' . $tab_minmax['maxLong'] . ',
                        west: ' . $tab_minmax['minLong'] .
                    '};
                    </script>
                    <script src="./script/map.js">
                        // importe les fonction pour créer la map et les marqueurs
                    </script>';
            }
        ?>
	</head>
	<body>
		<header>
			<nav>
				<a href="index.php?controller=event">Event</a>
				<a href="index.php?controller=utilisateurs">Admin</a>
				<?php
					if ( !isset( $_SESSION[ "login" ] ) ) {
						echo "<a href=\"index.php?action=connect&controller=utilisateurs\">Connect</a>";
					}
					else {
						echo "<a href=\"index.php?action=disconnect&controller=utilisateurs\">Disconnect</a>";
					}
				?>
			</nav>
		</header>
		<div>
            <?php
				// Si $controller='event' et $view='ListEvent',
				// alors $filepath="/chemin_du_site/view/event/list.php"
				$filepath = File ::build_path( array ( "view", $object, "$view.php" ) );
				require $filepath;
			?>
		</div>

<?php
if(isset($_GET['controller'])&&$_GET['controller']=='event') {
		echo '<div id = "map">
			<!-- affiche la map ici -->
		</div> <br>

        <input type="text" id="date" style="border:0; width: 200px; font-weight:bold;"> <br>

        <div id="slider">
            <!-- afficher le slider ici -->
        </div> <br>

        <div id="detail">
            <!-- affiche détails de l\'event ici -->
        </div>';}
?>







        <!--<script src='https://code.jquery.com/jquery-3.1.0.min.js'></script> <!-- importe la bibliotèque JQuery -->

		<footer>
			<p style = "border: 1px solid black;text-align:right;padding-right:1em;">Copyright</p>
		</footer>
	</body>
</html>

