<div id="jeu_off">
<h2 class="titre">ACCUEIL</h2>
<p>Bienvenue sur <span class="majuscule gras">Entraînement Orthographique</span>.</p>
<p>Cet espace vous propose un outil pour aider les enfants à mémoriser l'orthographe d'usage.<br/>Il s'inspire des propositions faites par Michel Barrios, dans <a href="http://www.icem-pedagogie-freinet.org/node/16004" target="_blank">« Le Nouvel Educateur »</a> n° 67</p>
<p>Vous pouvez vous inscrire comme "<span class="professeur">professeur</span>" et ensuite vous connecter avec ce profil.</p>
<p>- Un professeur peut créer des profils "élève".</p>
<p>- Un professeur peut enregistrer une liste de mots pour chacun de ses élèves.</p>
<ol id="liste">
<?php
$vue['phrases'] = array('papa','maman','école','maison','matin','soir','midi','hier',"aujourd'hui",'demain');
foreach ($vue['phrases'] as $i => $phrase) {
?>
<li><?php echo $phrase; ?></li>
<?php
}
shuffle($vue['phrases']);
?>
</ol>
<p>Une personne connectée avec le profil "<span class="eleve">élève</span>" peut s'entraîner à copier les mots de sa liste :</p>
<p>- D'abord "<span class="titre">les yeux ouverts</span>", avec un modèle.</p>
<p>- Ensuite, avec "<span class="titre">les yeux fermés</span>", sans modèle.</p>
<p>Vous voulez essayer cet outil ? <input type="button" name="jouer" value="essayer" onclick="javascript:jouer();"/> ou ... à partir de l’Echelle DUBOIS-BUYSE :</p>
<p>Se connecter avec le profil "<span class="eleve">élève</span>" :</p>
<ul>
<li>Professeur : dubois</li>
<li>login : db01</li>
<li>Mot de passe : db01</li>
</ul>
<p>Remarque : 01 correspond au numéro de l'échelon (CP : 01 à 07, CE1 : 08 à 11, CE2 : 12 à 15, CM1 : 16 à 19, CM2 : 20 à 23).</p>
</div>

<script type="text/javascript">
var mots = new Array(<?php echo '"'.implode('","',$vue['phrases']).'"'; ?>);
var iMot = 0;
var motMasque = '';
var modele = 'avec';
</script>

<script type="text/javascript" src="js/jeu.js"></script>

<div id="jeu_on">
<h2 class="titre">DEMONSTRATION</h2>
<div id="consigne">
<p><span class="majuscule">Facile : </span> Recopie le(s) mot(s) une première fois, en te servant du modèle. Le lapin a <span class="majuscule">les yeux ouverts</span>.</p>
<p><span class="majuscule">Difficile : </span> Recopie le(s) mot(s) une deuxième fois sans le modèle. Le lapin a <span class="majuscule">les yeux fermés</span>.<br/>
<span class="majuscule">Aides : </span><br/>
- Place le pointeur de la souris sur le lapin. Celui-ci ouvre les yeux, tu peux voir le(s) mot(s), mais tu ne peux plus écrire.<br/>
- Place le pointeur de la souris à côté du lapin. Celui-ci referme les yeux et tu peux à nouveau écrire.</p></div>
<p>Nombre de réussites : <?php echo $vue['reussites']; ?> - Record : <?php echo $vue['record']; ?> s.</p>
<p>Chrono : <span id="chrono">0</span> s.</p>
<p id="oeil" onmouseover="javascript:montrer()" onmouseout="javascript:cacher()"><img id="lapin" src ="img/yeux_fermes.png" alt="JE FERME LES YEUX !"/></p>
<p id="modele">?????????</p>
<form action="" name="jeu" method="post" onsubmit="return false;">
<p>
<input type="hidden" name="action" value="demonstration"/>
<input id="saisie" type="text" size="100" name="proposition" value="" onkeyup="verifier();" />
<input id="reussite" type="hidden" name="reussites" value="<?php echo $vue['reussites']; ?>" />
<input id="record" type="hidden" name="record" value="<?php echo $vue['record']; ?>" />
</p>
</form>
<form action=""><input type ="submit" value="abandonner"/></form>
</div>
