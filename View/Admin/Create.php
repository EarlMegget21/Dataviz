<form method="get" action="../TD2/index.php"> <!-- action determine le fichier dans lequel on est redirigé avec les variables rentrées après Submit -->
  <fieldset>
    <legend>My form:</legend>
    <p>
      <input type='hidden' name='action' value='created'> <!-- ajoute un input caché qui défini la variable GET action -->
        
      <label for="immat_id">License number</label> : <!-- for permet de renvoyer vers la zone test ayant l'id indiqué en cliquant sur le label -->
      <input type="text" placeholder="Ex : 256AB34" name="immatriculation" id="immat_id" required/>

      <label for="marque_id">Make</label> :
      <input type="text" placeholder="Ex : BMW" name="marque" id="marque_id" required/>

      <label for="color_id">Color</label> :
      <input type="text" placeholder="Ex : noir" name="couleur" id="color_id" required/>

    </p>
    <p>
      <input type="submit" value="Submit" />
    </p>
  </fieldset> 
</form>
