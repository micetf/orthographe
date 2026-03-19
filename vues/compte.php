<h2 class="titre">ESPACE PROFESSEUR</h2>
<h3 class="titre">Changer son login et/ou son mot de passe</h3>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <p>
        Login :
        <input type="text" name="loginP" value="<?php echo $vue['loginP']; ?>" onFocus="this.value='';" />
    </p>
    <p>
        Mot de passe :
        <input type="password" name="passwordP1" value="<?php echo $vue['passwordP1']; ?>" onFocus="this.value='';" />
    </p>
    <p>
        Confirmation du mot de passe :
        <input type="password" name="passwordP2" value="<?php echo $vue['passwordP2']; ?>" onFocus="this.value='';"/>
    </p>
    <p>
        <input type="hidden" name="action" value="modificationP"/>
        <input type="submit" name="valider" value="OK"/>
    </p>
</form>
