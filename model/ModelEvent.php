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
        /*$Quadri = array($A, $B, $C, $D);

        return array_filter($filteredQuery, function ($n) use (&$Quadri) {
            return self::pointInPolygon(array($n->getCoordonneeX(), $n->getCoordonneeY()), $Quadri);
        });*/
        return $filteredQuery;
    }


    //TODO Refaire la fonction ci dessous.


    private static function pointInPolygon($point, $listEdges)
    {
    }
    /*
            if (self::pointOnPolygonEdges($point, $listEdges) == true) {
                return true;
            }

            $inter = 0;
            $edges_count = count($listEdges);

            for ($i=1; $i < $edges_count; $i++) {
                $A = $listEdges[$i-1];
                $B = $listEdges[$i];
                $minX=min($A[0], $B[0]);
                $maxX=max($A[0], $B[0]);
                $minY=min($A[1], $B[1]);
                $maxY=max($A[1], $B[1]);
                if ($A[1] == $B[1] and $A[1] == $point[1] and $point[0] > $minX and $point[0] <  $maxX){
                    return true;
                }
                if ($point[1] > $minY and $point[1] <= $maxY and $point[0] <= $maxX and $A[1] != $B[1]) {
                    $e = ($point[1] - $A[1]) * ($B[0] - $A[0]) / ($B[1] - $A[1]) + $A[0];
                    if ($e == $point[0]) {
                        return true;
                    }
                    else if ($A[0] == $B[0] || $point[0] <= $e) {
                        $inter++;
                    }
                }
            }
            return $inter % 2 != 0;
        }

        private static function pointOnPolygonEdges($point, $points) {
            foreach($points as $ptsPoly) {
                if ($point[0] == $ptsPoly[0]&&$point[1]==$ptsPoly[1]) {
                    return true;
                }
            }
        }*/
}

?>