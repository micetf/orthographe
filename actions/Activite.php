<?php

class Activite
{
    public $nom = DIR_STATS;
    public $actifs = array(
      'p' => array(
        'J' => 0,
        'H' => 0,
        'M' => 0,
        'A' => 0
      ),
      'e' => array(
        'J' => 0,
        'H' => 0,
        'M' => 0,
        'A' => 0
      )
    );
    public function actifs($qui)
    {
        $nbJours = array('J' => 1,'H' => 7,'M' => 30,'A' => 365);
        $fichier = ($qui == 'p') ? DIR_PROFESSEURS : DIR_ELEVES;
        $dir = dir($fichier);
        while ($nom = $dir->read()) {
            foreach (array('J','H','M','A') as $d) {
                if (strpos($nom, 'eo_') !== false && time() - fileatime($fichier.$nom) < 3600 * 24 * $nbJours[$d]) {
                    $this->actifs[$qui][$d] += 1;
                }
            }
        }
    }
    public function Activite()
    {
        $this->actifs('p');
        $this->actifs('e');
    }

}
