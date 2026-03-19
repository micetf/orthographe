<div class="titre">
<h2 title="Formulaire d'inscription à l'espace d'entraînement orthographique.">INSCRIPTION</h2>
</div>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"/>
  <p>
    <label for="email" title="Saisissez une adresse électronique valide. Celle-ci sera utilisée pour finaliser l'inscription.">Email : </label>
    <input type="text" id="email" name="email" value="<?php echo $vue['email']; ?>" onfocus="this.value='';" />
  </p>
  <p>
    <label for="loginP" title="Choisissez votre identifiant professeur. N'utilisez que des lettres et des chiffres, les autres caractères (points, espaces,...) ne sont pas acceptés.">Login : </label>
    <input type="text" id="loginP" name="loginP" value="<?php echo $vue['loginP']; ?>" onfocus="this.value='';" />
  </p>
  <p>
    <label for="passwordP1" title="Choisissez votre mot de passe. N'utilisez que des lettres et des chiffres, les autres caractères (points, espaces,...) ne sont pas acceptés.">Mot de passe : </label>
    <input type="password" id="passwordP1" name="passwordP1" value="<?php echo $vue['passwordP1']; ?>" onfocus="this.value='';" />
  </p>
  <p>
    <label for="passwordP2" title="Pour vérifier que vous ne vous êtes pas trompé la première fois.">Confirmation du mot de passe : </label>
    <input type="password" id="passwordP2" name="passwordP2" value="<?php echo $vue['passwordP2']; ?>" onfocus="this.value='';"/>
  </p>
  <p>
    <label for="captcha" title="Pour lutter contre les spams.">Recopiez les caractères ci-dessous en minuscules et sans les espacer (comme un seul mot) : </label>
  </p>
  <p id="captcha">
    <input type="text" name="captcha" value=""/><br/><img src="actions/Captcha.php" alt="CAPTCHA"/>
  </p>
  <p>
    <input type="hidden" name="action" value="inscription"/>
    <input type="submit" name="valider" value="OK" title="Valider l'inscription"/>
    <input type="submit" name="valider" value="Abandonner" title="Abandonner l'inscription"/>
  </p>
</form>