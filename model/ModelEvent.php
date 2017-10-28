<?php

require_once File::build_path(['model', 'Model.php']);

/**
 *
 */
class ModelEvent extends Model
{

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
    private $MP3;
    /**
     * @var String
     */
    private $nom;

    /**
     * @var String
     */
    private $login;

    /**
     * @var string
     */
    static protected $object = "Event";

    /**
     * @var string
     */
    static protected $primary = "id";

    /**
     * ModelEvent constructor.
     *
     * @param null $n
     * @param null $d
     * @param null $x
     * @param null $y
     * @param null $de
     * @param null $al
     */
    public function __construct($i = NULL, $d = NULL, $x = NULL, $y = NULL, $de = NULL, $m = NULL, $n = NULL, $l = NULL)
    {
        if (!is_null($i) && !is_null($n) && !is_null($d) && !is_null($x) && !is_null($y) && !is_null($de) && !is_null(al)) {
            $this->id = $i;
            $this->nom = $n;
            $this->date = $d;
            $this->coordonneX = $x;
            $this->coordonneY = $y;
            $this->description = $de;
            $this->login = $l;
            $this->MP3 = $m;
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getCoordonneeX()
    {
        return $this->coordonneeX;
    }

    /**
     * @return float
     */
    public function getCoordonneeY()
    {
        return $this->coordonneeY;
    }

    /**
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return String
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return String
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return String
     */
    public function getMP3()
    {
        return $this->MP3;
    }


    /**
     * @return string
     */
    public static function getPrimary()
    {
        return self::$primary;
    }

    public static function getLowestDate()
    {
        $sql = "SELECT MIN(date) FROM Event;";
        $req_prep = Model::$pdo->prepare($sql);
        $req_prep->execute();
        $tab_obj = $req_prep->fetchAll(PDO::FETCH_OBJ);
        if (empty($tab_rep)) {
            return FALSE;
        }

        return $tab_rep[0];
    }

    public static function getHighestDate()
    {
        $sql = "SELECT MAX(date) FROM Event;";
        $req_prep = Model::$pdo->prepare($sql);
        $req_prep->execute();
        $tab_obj = $req_prep->fetchAll(PDO::FETCH_OBJ);
        if (empty($tab_rep)) {
            return FALSE;
        }

        return $tab_rep[0];
    }

    private static function getEventListDateCriteria($lowest, $highest)
    {
        $sql = "SELECT * FROM Event WHERE date>=:low and date<=:high;";
        $req_prep = Model::$pdo->prepare($sql);
        $match = array("low" => $lowest, "high" => $highest);
        $req_prep->execute($match);
        $req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelEvent');

        return $req_prep->fetchAll();
    }

    public static function searchEventPosDate($date1, $date2, $A, $B, $C, $D)
    {
        $filteredQuery = self::getEventListDateCriteria($date1, $date2);
        $Quadri = array($A, $B, $C, $D);

        /*Un quadrilatère est représenté par 4 points
         * ZY
         * WX
         *
         * Dans le cas présent  avec les points ABCD que nous avons ça donnera
         * DC
         * AB
         * TODO Mettre dans l'emplacement 0 de Quadri le point le plus bas à gauche
         * TODO Mettre dans l'emplacement 1 de Quadri le point le plus bas à droite
         * TODO Mettre dans l'emplacement 2 de Quadri le point le plus haut à droite
         * TODO Mettre dans l'emplacement 3 de Quadri le point le plus haut à gauche
         *
         */
        return array_filter($filteredQuery, function ($n) use (&$Quadri) {
            return self::pointInPolygon(array($n->getCoordonneeX(), $n->getCoordonneeY()), $Quadri);
        });
        return $filteredQuery;
    }


    private static function pointInPolygon($point, $poly)
    {
        $num = count($poly);
        $c = FALSE;
        for ($i = 0, $j = $num - 1; $i < $num; $j = $i, ++$i) {
            if ((($poly[$i][1] > $point[1]) != ($poly[$j][1] > $point[1])) && ($point[0] < ($poly[$j][0] - $poly[$i][0]) * ($point[1] - $poly[$i][1]) / ($poly[$j][1] - $poly[$i][1]) + $poly[$i][0])) {
                $c = !$c;
            }
        }
        return $c;
    }
}

?>