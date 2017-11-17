<?php
/**
 * Created by PhpStorm.
 * User: Julien
 * Date: 14/11/2017
 * Time: 13:01
 */

class ModelCommentaire
{
    private $idEvent;
    private $login;
    private $texte;
    private $note;

    static protected $object = "Commentaire";
    static protected $primary = "id";

    /**
     * ModelCommentaire constructor.
     * @param $id
     * @param $idEvent
     * @param $login
     * @param $texte
     */
    public function __construct($idEvent=NULL, $login=NULL, $texte=NULL, $note=NULL)
    {
        if ( !is_null ( $idEvent ) && !is_null ( $login ) && !is_null ( $texte ) && !is_null($note)) {
            $this->idEvent = $idEvent;
            $this->login = $login;
            $this->texte = $texte;
            $this->note = $note;
        }
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function getIdEvent()
    {
        return $this->idEvent;
    }

    /**
     * @return null
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return null
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    public static function getAllComments($idEvent){
        $sql = "SELECT * FROM Commentaire WHERE idEvent = :id";
        $req_prep = Model ::$pdo -> prepare ( $sql );
        $match = ["id" => $idEvent];
        $req_prep -> execute ( $match );
        $req_prep -> setFetchMode ( PDO::FETCH_CLASS , 'ModelCommentaire' );
        return $req_prep -> fetchAll ();
    }

    public function save(){
        $sql = "INSERT INTO Commentaire(idEvent, login, texte, note) VALUES(:id, :login, :texte, :note)";
        $req_prep = Model::$pdo->prepare($sql);
        $values = array(
            "id"=>$this->idEvent,
            "login"=>$this->login,
            "texte"=>$this->texte,
            "note"=>$this->note,
        );
        $req_prep->execute($values);
    }

}