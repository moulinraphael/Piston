<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_init.php **************************************/
/* Réalisation des actions initales importantes  ***********/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Lancement de la session
session_save_path(DIR.'sessions');
session_start();


//Base de donnée en UTF8
$pdo->exec('SET NAMES `utf8`');


//Si l'utilisateur est super admin et qu'il cherche à visualiser les erreurs
//Alors on le redirige vers le fichier correctement construit
if (isset($_GET['errlog']) &&
	DEBUG_ACTIVE) {

	//On récupère les erreurs dans le log dans l'ordre décroissant
	$erreurs = !file_exists(DIR.'.hterrlog') ? '' :
		file_get_contents(DIR.'.hterrlog');
	$erreurs = explode("\n[", $erreurs);
	$erreurs = array_reverse($erreurs);


	//Pour chaque erreur, on affiche son contenu avec son niveau de criticité
	foreach ($erreurs as $k => $erreur) {
		echo '<div style="background:'.(!preg_match('`(Fatal|Warning)`', $erreur) ? '#EEE' : '#F77').'; border:1px solid #000; margin:10px; padding:5px;">'.
			preg_replace('`^\[([^\]]+)\](.+)`', '<b>$1</b><br />$2', 
				($k < count($erreurs) - 1 ? '[' : '').nl2br($erreur)) .' </div>';
	}


	//On stoppe l'éxecution du script
	die;

}


//De même s'il est super admin et qu'il demande à vider le log
else if (isset($_GET['dellog'])) {

	//On supprime le fichier de log
	@unlink(DIR.'.hterrlog');
}
