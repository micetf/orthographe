<?php 
if (!function_exists('file_put_contents')) {
function file_put_contents($nom,$contenu)
{
  $fp = fopen($nom, 'w+');
  fputs($fp, $contenu);
  fclose($fp);
}
}