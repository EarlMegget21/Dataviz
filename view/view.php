<!DOCTYPE html>
<html>
	<head>
		<meta charset = "UTF-8">
		<title><?php echo $pagetitle; ?></title>
		<!-- CSS temporel pour redimensionner la map -->
		<style>
			#map {
				height: 400px;
				width: 100%;
			}
		</style>
	</head>
	<body>
		<header>
			<nav>
				<a href = "index.php?action=readAll&controller=event">Event</a>
				<a href = "index.php?action=readAll&controller=utilisateurs">Admin</a>
				<?php
					if (!isset( $_SESSION[ "login" ] )) {
						echo "<a href=\"index.php?action=connect\">Connect</a>";
					} else {
                        echo "<a href=\"index.php?action=disconnect\">Disconnect</a>";
					}
				?>
			</nav>
		</header>

		<div><?php
				// Si $controller='event' et $view='ListEvent',
				// alors $filepath="/chemin_du_site/view/event/list.php"
				$filepath = File ::build_path( array ( "view", $object, "$view.php" ) );
				require $filepath;
			?>
		</div>

		<div id = "map">
			<!-- affiche la map ici -->
		</div>

		<script>
            function initMap() {
                var centre = {lat: -25.363, lng: 131.044};
                var map = new google.maps.Map(document.getElementById('map'), { //créer une map
                    zoom: 4,
                    center: centre
                });
                var point1 = new google.maps.Marker({ //créer un point
                    position: centre,
                    map: map
                });
            }
		</script>

		<script async defer
		        src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCuog5LlTmtUH8-wB5IjxdJMY_Cq-CqhVU&callback=initMap"> <!-- include l'API Javascript grâce à notre Clé -->
		</script>

		<footer>
			<p style = "border: 1px solid black;text-align:right;padding-right:1em;">Copyright</p>
		</footer>
	</body>
</html>

