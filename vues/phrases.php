<h2 class="titre">ESPACE PROFESSEUR</h2>
<h3 class="titre">Phrases de <span class="majuscule"><?php echo $vue['login']; ?></span></h3>
<table>
<?php
if (count($vue['phrases']) > 1) {
    ?>
<tr>
<form action="" method="post">
<td class="centre" colspan="2">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"/>
<input type="hidden" name="action" value="suppressionP"/>
<input class="supprimer" type="submit" name="valider" value="Supprimer toutes les phrases" onclick="return confirm('Êtes-vous certain de vouloir supprimer toutes les phrases ?');"/>
</td>
</form>
</tr>
<?php
}
foreach ($vue['phrases'] as $phrase) {
    ?>
<tr>
<form action="" method="post">
<td class="quatrevingts bord"><?php echo $phrase; ?> <input type="hidden" name="phrase" value="<?php echo $phrase; ?>"/></td>
<td class="vingt bord centre">
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"/>
<input type="hidden" name="action" value="suppressionP"/>
<input class="supprimer" type="submit" name="valider" value="supprimer"/>
</td>
</form>
</tr>
<?php
}
?>
</table>
<form action="" method="post">
<p>
<textarea name="phrases" rows="10" cols="105" onFocus="if (this.value=='<?php echo $vue['phrase']; ?>') this.value='';"><?php echo $vue['phrase']; ?></textarea>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"/>
<input type="hidden" name="action" value="ajoutP"/>
<input type="submit" name="valider" value="ajouter"/>
</p>
</form>