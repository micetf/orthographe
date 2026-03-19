function $(el)
{
	return document.getElementById(el);	
}
function visible()
{
	$('conteneur').style.visibility ='visible';
	$('noscript').style.visibility ='hidden';
}
function chrono() {
	$('chrono').innerHTML++;	
	idChrono=setTimeout("chrono()",1000);	
}
function jouer() {
	if (mots.length==1 && mots[0]=="") return false; 
	$('jeu_off').style.visibility = 'hidden';
	$('jeu_off').style.display = 'none';
	$('jeu_off').style.height = 0;
	$('jeu_on').style.visibility = 'visible';
	$('jeu_on').style.display = 'block';
	$('lapin').src="img/yeux_ouverts.png";	
	motMasque = $('modele').innerHTML;	
	$('modele').innerHTML = mots[iMot];	
	$('saisie').value='';
	$('saisie').focus();
	idChrono=setTimeout("chrono()",1000);	
}
function montrer() {
	if (modele == 'sans') {
		$('lapin').src="img/yeux_ouverts.png";	
		$('modele').innerHTML = (mots[iMot]==undefined) ? "&nbsp;" : mots[iMot];	
		$('saisie').blur();	
	}
}
function cacher() {
	if (modele == 'sans') {
		$('lapin').src="img/yeux_fermes.png";	
		$('modele').innerHTML = motMasque;	
		$('saisie').focus();	
	}
}
function verifier() {
	if ($('saisie').value == mots[iMot]) {
		iMot += (modele == 'avec') ? 0 : 1;
		if (iMot!=mots.length) {
			modele = (modele == 'avec') ? 'sans' : 'avec';
			$('lapin').src = (modele == 'avec') ? "img/yeux_ouverts.png" : "img/yeux_fermes.png";
			$('modele').innerHTML = (modele == 'avec') ? mots[iMot] : motMasque;
			$('modele').style.background='#536E7D';
			$('saisie').value='';
		}
	} else {
		$('modele').style.background='#786056';
	}
	if (iMot==mots.length) {
		clearTimeout(idChrono);
		if (parseInt($('chrono').innerHTML) < $('record').value) {
			alert('Bravo, tu as amélioré ton record en '+$('chrono').innerHTML+' s.');
			$('record').value = parseInt($('chrono').innerHTML);
		} else {
			alert('Tu as terminé en '+$('chrono').innerHTML+' s.');
		}
		$('reussite').value = parseInt($('reussite').value)+1;
		document.jeu.submit();
	}
}
