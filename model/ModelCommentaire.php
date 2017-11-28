<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 14/11/2017
 * Time: 13:01
 */

class ModelCommentaire extends Model{
    private $idCommentaire;
    private $idEvent;
    private $login;
    private $texte;
    private $note;

    static protected $object = "Commentaire";
    static protected $primary = "idCommentaire";

    /**
     * ModelCommentaire constructor.
     * @param $idEvent
     * @param $login
     * @param $texte
     */
    public function __construct($idCommentaire=NULL, $idEvent=NULL, $login=NULL, $texte=NULL, $note=NULL)
    {
        if ( !is_null ( $idCommentaire ) && !is_null ( $idEvent ) && !is_null ( $login ) && !is_null ( $texte ) && !is_null($note)) {
            $this->id=$idCommentaire;
            $this->idEvent = $idEvent;
            $this->login = $login;
            $this->texte = $texte;
            $this->note = $note;
        }
    }

    public function getIdCommentaire()
    {
        return $this->idCommentaire;
    }

    public function getIdEvent()
    {
        return $this->idEvent;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getTexte()
    {
        return $this->texte;
    }

    public function getNote()
    {
        return $this->note;
    }

    public static function getPrimary ()
    {
        return self ::$primary;
    }

    public static function getAllComments(){
        $sql = "SELECT * FROM Commentaire ORDER BY idEvent";
        $req_prep = Model ::$pdo -> prepare ( $sql );
        $req_prep -> execute ();
        $req_prep -> setFetchMode ( PDO::FETCH_CLASS , 'ModelCommentaire' );
        $tab_v=$req_prep -> fetchAll ();

        if (file_exists("./xml/comments.xml")) { //si le fichier XML existe déjà
            unlink("./xml/comments.xml"); //supprime le fichier XML
        }
        $doc = new DOMDocument("1.0", "UTF-8"); //créer un objet de type document DOM(format de balises comme XML, html, ...)
        $node = $doc->createElement("comments"); //créer une balise <comments> contenant tous les points
        $parnode = $doc->appendChild($node); //ajoute cette balise au document
        foreach ($tab_v as $comment) { //pour chaque event retourné par la requête
            // Add to XML document node
            $node = $doc->createElement("comment"); //créer une balise <comment> représentant un point
            $newnode = $parnode->appendChild($node); //ajoute cette balise en enfant à <comments>
            $newnode->setAttribute("idCommentaire", $comment->getIdCommentaire());
            $newnode->setAttribute("idEvent", $comment->getIdEvent()); //ajoute chaque attribut
            $newnode->setAttribute("login", $comment->getLogin());
            $newnode->setAttribute("texte", $comment->getTexte());
            $newnode->setAttribute("note", $comment->getNote());
        }
        $xmlfile = $doc->save("./xml/comments.xml"); //sauvegarde le document en fichier physique à l'adresse suivante et sous le nom comments.xml sur le serveur

        return $tab_v;
    }

    /*public function save(){
        $sql = "INSERT INTO Commentaire(idEvent, login, texte, note) VALUES(:id, :login, :texte, :note)";
        $req_prep = Model::$pdo->prepare($sql);
        $values = array(
            "id"=>$this->idEvent,
            "login"=>$this->login,
            "texte"=>$this->texte,
            "note"=>$this->note,
        );
        $req_prep->execute($values);
    }*/

}