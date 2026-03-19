<div class="titre">
    <h2>ESPACE PROFESSEUR</h2>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <p>Si vous pensez que cet outil le mérite...</p>
    <p>
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="ZXVEXH5392YTY">
        <input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !" title="Si vous pensez que cet outil le mérite..." >
        <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
    </p>
    </form>
    <h3>Gérer ses élèves</h3>
</div>

<p>
    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?bg=eleve">Ajouter un élève</a>
</p>
<form action="" method="post">
<p>
    Editer les listes de phrases au format
    <input type="hidden" name="action" value="pdf"/>
    <input type="submit" value="pdf"/>
</p>
</form>
<table>
<?php
foreach ($vue['eleves'] as $eleve) {
?>
<tr>
<form action="" method="post">
    <td class="vingt bord"><?php echo $eleve[0]; ?>
    <input type="hidden" name="action" value="suppressionE"/>
    <input type="hidden" name="loginE" value="<?php echo $eleve[0]; ?>"/></td>
    <td class="bord">
        Réussites : <?php echo $eleve[1]; ?>  -
        Record : <?php echo $eleve[2]; ?> sec.
    </td>
    <td class="bord">
        <input type="submit" name="valider" value="supprimer"/>
    </td>
    <td class="bord">
        <a href = "<?php echo $_SERVER['PHP_SELF'] . '?bg=eleve&login=' . $eleve[0]; ?>">modifier</a>
    </td>
    <td class="vingt bord">
        gérer les
        <a href = "<?php echo $_SERVER['PHP_SELF'] . '?bg=phrases&login=' . $eleve[0]; ?>">
            phrases
        </a>
    </td>
</form>
</tr>
<?php
}
?>
</table>
