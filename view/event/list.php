<?php
echo '<div id="contenu">
        <div id="button"> <!-- bouton <<Retour -->
            <input type="button" id="retour" value="<<Retour" style="display:none; padding:0px 1px;">
        </div>
        <div id="detail">
            <!-- affiche détails de l\'event ici -->
        </div>
    
   
        <form method="get" action="index.php"> <!-- formulaire pour générer les events -->
            <fieldset>
                <legend>Generate events:</legend>
                <p>
                    <label for="n_id">Number</label> :
                    <input type="number"  name="n" id="n_id" required/>
        
                    <input type=\'hidden\' name=\'action\' value=\'generate\'>
                    <input type=\'hidden\' name=\'controller\' value=\'event\'>
                </p>
                <p>
                    <input type="submit" value="Generate"/>
                </p>
            </fieldset>
        </form>
    </div>
    <input type=\'text\' placeholder=\'Rentrez un mot-clé\' name=\'keyword\' id=\'keyword_id\'/>
    <input id=\'keywordButton_id\' type="submit" value="Rechercher"/>
    <div id="mapslider">
        <div id = "map">
            <!-- affiche la map ici -->
        </div> <br>

        <p id="date"><!-- affiche les bornes du curseur en temps reel(map) --></p><br>

        <div id="slider">
            <!-- afficher le slider ici -->
        </div>
    </div>';