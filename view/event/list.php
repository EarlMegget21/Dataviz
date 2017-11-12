<?php
// Affiche les events stockés dans $tab_v
foreach ($tab_v as $v) {
    echo '<p><a href=\'./index.php?controller=event&action=read&'
        . ModelEvent::getPrimary()
        . '='
        . rawurlencode($v->getId())
        . '\'>'
        . $v->getNom()
        ."</a>";

}
	if (isset( $_SESSION[ "login" ] )&&$_SESSION["isAdmin"]==1) {
		echo "<br>\n<br>\n<a href=\"index.php?controller=event&action=update\">Créer évenement</a>";
	}
?>
<form method="get" action="index.php">

    <label for="date1_id">Minimum date</label> :
    <input type="date" placeholder="Ex :00/00/00" name="date1" id="date1_id" value="0001-01-01" required/>

    <label for="date2_id">Maximum date</label> :
    <input type="date" placeholder="Ex :00/00/00" name="date2" id="date2_id" value="3000-01-01" required/>

    <input type="hidden" name="longitude1" id="longitude_id1" required/> <!-- envoie le Get de x1 en fonction de la position de la map-->

    <input type="hidden" name="latitude1" id="latitude_id1" required/> <!-- envoie le Get de y1 -->

    <input type="hidden" name="longitude2" id="longitude_id2" required/> <!-- envoie le Get de x2 -->

    <input type="hidden" name="latitude2" id="latitude_id2" required/> <!-- envoie le Get de y2 -->

    <input type='hidden' name='controller' value='event'> <!-- envoie le Get du controller event -->

    <input type='hidden' name='action' value='search'> <!-- envoie le Get de l'action search -->

    <input type="submit" value="Rechercher" id="sub"/>



</form>
