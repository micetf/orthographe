<?php 
class Stats
{
  var $nom = './datas/stats.txt';
  var $reussites = 0;
  var $record = 300;
  
  function Stats($record=300)
  {
    $s = file_get_contents($this->nom);
    if ($s != '') {
      $t = explode(',',$s);
      $this->reussites = $t[0];
      $this->record = $t[1];
    } else {
      $this->record=$record;
      file_put_contents($this->nom,$this->reussites.','.$this->record);
    }
  }
  function maj($reussites,$record)
  {
      $this->reussites++;
      if ($record < $this->record) $this->record = $record;
      file_put_contents($this->nom,$this->reussites.','.$this->record);
  }
}