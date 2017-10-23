<?php


/**
 *
 */
class ModelEvent
{
    /**
     * @var int
     */
    private static $lastId=0;
    /**
     * @var int
     */
    private $id;
    /**
     * @var Date
     */
    private $date;

    /**
     * @var float
     */
    private $coordonneeX;

    /**
     * @var float
     */
    private $coordonneeY;

    /**
     * @var String
     */
    private $description;

    /**
     * @var String
     */
    private $nom;
    
    /**
     * @var String
     */
    private $adminLogin;
    

    // a constructor
    public function __construct($n = NULL, $d = NULL, $x=NULL, $y=NULL, $de=NULL, $al=NULL) {
        if (!is_null($n) && !is_null($d) && !is_null($x) && !is_null($y) && !is_null($de) && !is_null($al)) {
            $this->id = ModelEvent::$lastId;
            ModelEvent::$lastId+=1;
            $this->nom=$n;
            $this->date = $d;
            $this->coordonneX = $x;
            $this->coordonneY = $y;
            $this->description = $de;
            $this->adminLogin = $al;
        }
    }
    
    public static function getAllEvent(){
        try{
            $rep=Model::$pdo->query('SELECT * FROM Event');
            $tab_event=$rep->fetchAll(PDO::FETCH_CLASS, 'ModelEvent');
            return $tab_event;
        } catch(PDOException $e){
           echo $e->getMessage(); // affiche un message d'erreur
           die(); //supprimer equilvalent à System.exit(1); en java
        }
    }
    
    public static function getEventById($id) {
	// In the query, put tags :xxx instead of variables $xxx
    $sql = "SELECT * from Event WHERE id=:nom_tag";
        try{
            // Prepare the SQL statement
            $req_prep = Model::$pdo->prepare($sql);

            $values = array(
                "nom_tag" => $id,
                //nomdutag => valeur, ...
            );
            // Execute the SQL prepared statement after replacing tags 
            // with the values given in $values
            $req_prep->execute($values);

            // Retrieve results as previously
            $req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelEvent');
            $tab_event = $req_prep->fetchAll();
            // Careful: you should handle the special case of no results
            if (empty($tab_event))
                return false;
            return $tab_event[0];
        } catch(PDOException $e){
            echo $e->getMessage(); // affiche un message d'erreur
            die(); //supprimer equilvalent à System.exit(1); en java
        }   
    }
    
    public function save() {
        $sql = "INSERT INTO Event (date, coordonneesX, coordonneesY, description, nom, login) VALUES(:date, :coordonneesX, :coordonneesY, :description, :nom, :login)";
        try{
            $req_prep = Model::$pdo->prepare($sql);

            $values = array(
                "date" => $this->date,
                "coordonneesX" => $this->coordonneesX,
                "coordonneesY" => $this->coordonneesY,
                "description" => $this->description,
                "nom" => $this->nom,
                "login" => $this->adminLogin
            );
            $req_prep->execute($values);
            return true; //si on return pas true, la valeur retournée sera NULL
        } catch(PDOException $e){
           echo $e->getMessage(); // affiche un message d'erreur
           return false;
        }
    }
    
    public function update($id) {
        $sql = "UPDATE Event SET date=:date, coordonneesX=:coordonneesX, coordonneesY=:coordonneesY, description=:description, nom=:nom, login=:login WHERE id='$id'";
        try{
            $req_prep = Model::$pdo->prepare($sql);

            $values = array(
                "date" => $this->date,
                "coordonneesX" => $this->coordonneesX,
                "coordonneesY" => $this->coordonneesY,
                "description" => $this->description,
                "nom" => $this->nom,
                "login" => $this->adminLogin
            );
            $req_prep->execute($values);
            return true; //si on return pas true, la valeur retournée sera NULL
        } catch(PDOException $e){
           echo $e->getMessage(); // affiche un message d'erreur
           return false;
        }
    }
    
    public static function delete($id) {
        $sql = "DELETE FROM Event WHERE id='$id'";
        try{
            $req_prep = Model::$pdo->prepare($sql);
            $req_prep->execute();
            return true; //si on return pas true, la valeur retournée sera NULL
        } catch(PDOException $e){
           echo $e->getMessage(); // affiche un message d'erreur
           return false;
        }
    }
}
