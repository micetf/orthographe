<?php
if ($vue['contenu'] == 'imprimer.php') {
	include $vue['contenu'];
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr-fr" lang="fr-fr">
	<head>
		<title>Entraînement Orthographique</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="fr" />
		<meta name="description" content="Outil d'aide à la mémorisation de l'orthographe lexicale à l'école primaire." />
		<meta name="keywords" content="orthographe, orthographique, lexicale, orthographe lexicale, entrainement" />
		<link rel="canonical" href="http://micetf.fr/Orthographe"/>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
		<script type="text/javascript" src="js/jeu.js"></script>
		<script type="text/javascript" src="js/courriel.js"></script>
	</head>
	<body>
		<div id="conteneur">
			<div id="entete">
				<div id="logo"><a href="http://www.micetf.fr/"><img src="../img-micetf/logo.png" width="146px" alt="www.micetf.fr"/></a> </div>
				<div id="courriel"><a id="contact" href="mailto:truc@machin.fr">courriel</a></div>
				<h1><span class="majuscule">E</span>ntraînement <span class="majuscule">O</span>rthographique</h1>
			</div>
			<div id="bgx">
				<?php include $vue['bgx'];?>
			</div>
			<div id="contenu">
				<h3 class="message"><?php echo $vue['message']; ?></h3>
				<?php include $vue['contenu'];?>
			</div>
			<div id="bp">
				Créé par MiCetF (2009)
			</div>
		</div>
		<div id="noscript"><h1>Vous devez activer javascript.</h1></div>
		<script type="text/javascript">
		visible();
		</script>
	</body>
</html>