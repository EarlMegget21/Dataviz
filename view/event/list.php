<?php
// Display of the events stored in $tab_v
foreach ($tab_v as $v) {
    echo '<p><a href=\'./index.php?controller=event&action=read&'
        . ModelEvent::getPrimary()
        . '='
        . rawurlencode($v->getId())
        . '\'>'
        . htmlspecialchars($v->getId())
        . '</a>&nbsp'
        . $v->getNom();

}
?>
<form method="get" action="index.php">

    <label for="date1_id">Minimum date</label> :
    <input type="date" placeholder="Ex :00/00/00" name="date1" id="date1_id" value="0001-01-01" required/>

    <label for="date2_id">Maximum date</label> :
    <input type="date" placeholder="Ex :00/00/00" name="date2" id="date2_id" value="3000-01-01" required/>

    <br/>
    <label for="coordonneeX1_id">Coordonee X1</label> :
    <input type="number" placeholder="Ex :128" name="coordonneeX1" id="coordonneeX_id1" value="0" required/>

    <label for="coordonneeY1_id">Coordonee Y1</label> :
    <input type="number" placeholder="Ex :256" name="coordonneeY1" id="coordonneeY_id1" value="0" required/>
    <br/>

    <label for="coordonneeX2_id">Coordonee X2</label> :
    <input type="number" placeholder="Ex :128" name="coordonneeX2" id="coordonneeX_id2" value="0" required/>

    <label for="coordonneeY2_id">Coordonee Y2</label> :
    <input type="number" placeholder="Ex :256" name="coordonneeY2" id="coordonneeY_id2" value="0" required/>
    <br
    <br/>
    <input type='hidden' name='controller' value='event'>
    <input type='hidden' name='action' value='search'>
    <input type="submit" value="Envoyer"/>

</form>
</p>
<br>
<a href="index.php?controller=event&action=update">Créer évenement</a>
