<div id="eleve">
<p class="categorie">Elève</p>
<p>Bonjour<br/><span class="majuscule gras"><?php echo htmlspecialchars($vue['eleve']); ?></span><br/>élève de<br/><span class="majuscule gras"><?php echo htmlspecialchars($vue['professeur']); ?></span></p>
<p>Réussites : <?php echo $vue['reussites']; ?></p>
<p>Record : <?php echo $vue['record']; ?> s.</p>
<p><a class="deconnexion" href="?bg=deconnexion">Déconnexion</a></p>
</div>