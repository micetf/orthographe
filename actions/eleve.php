<?php
function is_eleve($infos)
{
  if (isset($infos['loginP']) && isset($infos['loginE']) && isset($infos['passwordE'])) {
    if (file_exists('./datas/eleves/eo_'.md5($infos['loginP']).'_'.md5($infos['loginE']).'_'.md5($infos['passwordE']).'.txt')) {
      return true;
    } else if (file_exists('./datas/eleves/eo_'.md5($infos['loginP']).'_'.md5($infos['loginE']).'_'.md5(strtolower($infos['passwordE'])).'.txt')) {
      return true;
    }  
  }
  return false;
}
