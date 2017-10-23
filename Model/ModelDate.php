<?php


/**
 *
 */
class ModelDate
{
    /**
     * @var int
     */
    private $jour;
    /**
     * @var int
     */
    private $mois;

    /**
     * @var int
     */
    private $annee;
    

    // a constructor
    public function __construct($j = NULL, $m = NULL, $a=NULL) {
        if (!is_null($j) && !is_null($m) && !is_null($a)) {
            $this->jour=$j;
            $this->mois = $m;
            $this->annee = $a;
        }
    }
}

