<h2 class="titre">ESPACE PROFESSEUR</h2>
<h3 class="titre"><?php echo ucfirst($vue['action']); ?> d'un élève <?php if ($vue['login'] != '') {
    echo '(<span class="majuscule">'.$vue['login'].'</span>)';
} ?></h3>
<form action="" method="post">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"/>
  <p>Login élève : <input type="hidden" name="login" value="<?php echo $vue['login']; ?>"/> <input type="text" name="loginE" value="<?php echo $vue['loginE']; ?>" onFocus="this.value='';"/></p>
  <p>Mot de passe : <input type="password" name="passwordE1" value="<?php echo $vue['passwordE1']; ?>" onFocus="this.value='';"/></p>
  <p>Confirmation du mot de passe : <input type="password" name="passwordE2" value="<?php echo $vue['passwordE1']; ?>" onFocus="this.value='';"/></p>
  <p><input type="hidden" name="action" value="<?php echo $vue['action']; ?>E"/><input type="submit" name="valider" value="OK"/></p>
</form>