<?php
require('../common/fpdf/fpdf.php');

class PDF extends FPDF
{
	var $identifiant='';
	var $phrases=array();
	
	function setEleve($identifiant)
	{
      $this->identifiant=$identifiant;
	}
	function setPhrases($phrases)
	{
	    $this->phrases=array();
      $this->phrases=$phrases;
	}
	
	function liste() 
	{
      foreach ($this->phrases as $i => $phrase) {
	      $this->SetFont('Arial','',12);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,15,($i+1).'. '.utf8_decode($phrase),1,1,'L');
      }
	}

	function Header()
	{
	    $this->SetFont('Arial','B',16);
	    $this->SetTextColor(0,0,100);
		  $this->Cell(0,14,'',1,1,'C');
		  $this->Ln(-13);
		  $this->Cell(0,12,utf8_decode('Entraînement Orthographique'),1,1,'C');
	    $this->SetFont('Arial','',13);
      $this->SetTextColor(0,0,0);
      $this->Cell(0,15,utf8_decode('Liste de mots/phrases de : '.ucfirst($this->identifiant)),0,1,'C');
	    $this->SetFont('Arial','B',12);
	    $this->SetTextColor(0,0,0);
	}

	function Footer()
	{
	  $micetf='http://micetf.fr/orthographe';
	  $wmicetf=$this->GetStringWidth($micetf);
    $this->setXY(-200,-25);
    $this->SetFont('Arial','',12);
    $this->SetFont('Times','I',12);
    $this->Cell(85,15,'Liste du '.date('d/m/Y'),0,0,'L');
    $this->Cell(105,15,$micetf,0,0,'R');
    $this->Link(200-$wmicetf,277,$wmicetf,15,$micetf);
    $this->Image('../common/logos/CreativeCommons80x15.png',95,277,20,4,'png','http://creativecommons.org/licenses/by-nc/2.0/fr/');
	}
}

$pdf=new PDF();

foreach ($vue['eleves'] as $eleve) {
  $pdf->setEleve($eleve[0],$eleve[1]);
  $pdf->setPhrases($eleve[1]);
  $pdf->AddPage();
  $pdf->liste();
}


$pdf->Output('orthographe.pdf','D');
