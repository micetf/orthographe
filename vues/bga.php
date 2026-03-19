<div id="professeur">
<p class="categorie">Professeur</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<p><input type="text" name="loginP" value="login" onfocus="this.value='';"/></p>
<p><input type="password" name="passwordP" value="          " onfocus="this.value='';"/></p>
<p><input type="hidden" name="action" value="connexionP"/><input type="submit" name="valider" value="OK"/></p>
<p><a href="index.php?bg=inscription">s'inscrire</a></p>
</form>
</div>

<div id="eleve">
<p class="categorie">Elève</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<p><input type="text" name="loginP" value="professeur" onfocus="this.value='';"/></p>
<p><input type="text" name="loginE" value="login" onfocus="this.value='';"/></p>
<p><input type="password" name="passwordE" value="          " onfocus="this.value='';"/></p>
<p><input type="hidden" name="action" value="connexionE"/><input type="submit" name="valider" value="OK"/></p>
</form>
</div>

<div id="activite">
<p class="categorie">Activité sur</p>
<p>
<span class="gras souligne">dernières 24 heures</span><br/>
<?php echo $activite->actifs['p']['J']; ?> professeurs<br/>
<?php echo $activite->actifs['e']['J']; ?> élèves
</p>
<p>
<span class="gras souligne">7 derniers jours</span><br/>
<?php echo $activite->actifs['p']['H']; ?> professeurs<br/>
<?php echo $activite->actifs['e']['H']; ?> élèves
</p>
<p>
<span class="gras souligne">30 derniers jours</span><br/>
<?php echo $activite->actifs['p']['M']; ?> professeurs<br/>
<?php echo $activite->actifs['e']['M']; ?> élèves
</p>
<p>
<span class="gras souligne">365 derniers jours</span><br/>
<?php echo $activite->actifs['p']['A']; ?> professeurs<br/>
<?php echo $activite->actifs['e']['A']; ?> élèves
</p>

</div>
