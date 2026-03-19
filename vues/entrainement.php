<h2 class="titre">ESPACE ELEVE</h2>
<h3 class="titre">
Phrases de <span class="majuscule"><?php echo $vue['eleve']; ?></span> élève de <span class="majuscule"><?php echo $vue['professeur']; ?></span>
</h3>
<div id="jeu_off">
<ol id="listeBis">
<?php 
foreach ($vue['phrases'] as $i => $phrase) {
?>
<li><?php echo $phrase; ?></li>
<?php 
}
shuffle($vue['phrases']);
?>
<p><input type="button" name="jouer" value="jouer" onclick="jouer();"/></p>
</ol>
</div>

<script type="text/javascript">
var mots = new Array(<?php echo '"'.implode('","',$vue['phrases']).'"'; ?>);
var iMot = 0;
var motMasque = '';
var modele = 'avec';
var idChrono;
</script>

<script type="text/javascript" src="js/jeu.js"></script>

<div id="jeu_on">
<div id="consigne">
<p><span class="majuscule">Facile : </span> Recopie le(s) mot(s) une première fois, en te servant du modèle. Le lapin a <span class="majuscule">les yeux ouverts</span>.</p>
<p><span class="majuscule">Difficile : </span> Recopie le(s) mot(s) une deuxième fois sans le modèle. Le lapin a <span class="majuscule">les yeux fermés</span>.<br/>
<span class="majuscule">Aides : </span><br/>
- Place le pointeur de la souris sur le lapin. Celui-ci ouvre les yeux, tu peux voir le(s) mot(s), mais tu ne peux plus écrire.<br/>
- Place le pointeur de la souris à côté du lapin. Celui-ci referme les yeux et tu peux à nouveau écrire.</p>
</div>
<p>Chrono : <span id="chrono">0</span> s.</p>
<p id="oeil" onmouseover="montrer()" onmouseout="cacher()"><img id="lapin" src ="img/yeux_fermes.png" alt="JE FERME LES YEUX !"/></p>
<p id="modele">?????????</p>
<form action="" name="jeu" method="post" onsubmit="return false;">
<p>
<input id="saisie" type="text" size="100" name="proposition" value="" onkeyup="verifier();"/>
<input type="hidden" name="action" value="reussite"/>
<input id="reussite" type="hidden" name="reussites" value="<?php echo $vue['reussites']; ?>"/>
<input id="record" type="hidden" name="record" value="<?php echo $vue['record']; ?>"/>
</p>
</form>
<form><input type ="submit" value="abandonner"/></form>
</div>
