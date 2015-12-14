<?php

/* *********************************************************/
/* Produit Piston : Gestion Matos et Membres ***************/
/* Créé par Raphaël Kichot' MOULIN *************************/
/* raphael.moulin@ecl13.ec-lyon.fr *************************/
/* *********************************************************/
/* includes/_functions.php *********************************/
/* Plusieurs fonctions très utilisées **********************/
/* *********************************************************/
/* Dernière modification : le 06/11/14 *********************/
/* *********************************************************/


//Fonctions relatives à l'état de l'utilisateur
function isConnected() {
	return defined('LOGIN'); 
}

function isAdmin() {
	global $ADMINS, $SUPER_ADMINS;
	if (!defined('LOGIN')) return false;
	return in_array(LOGIN, $ADMINS) || in_array(LOGIN, $SUPER_ADMINS);
}

function isSuperAdmin() {
	global $SUPER_ADMINS;
	if (!defined('LOGIN')) return false;
	return in_array(LOGIN, $SUPER_ADMINS);
}


//Fonction relative à l'état de l'interface
function isCLI() {
	return in_array(php_sapi_name(), array('cli', 'cli-server'));
}


//Fonctions relatives aux chaines de caractères et aux contenus
function onlyLetters($string) {
	return preg_replace('/[^\p{L}\p{N} \'.-]+/', '', $string);
}

function secure($string) {
	return trim(htmlspecialchars(addslashes($string)));
}

function printDate($s, $sentence = false) {
	$t = time();
	if (empty($s)) return '';
	else if ($t - $s < 60) return $sentence ? 'À l\'instant' : 'maintenant';
	else if($t - $s < 3600 && date('d') == date('d', $s)) return ($sentence ? 'Il y a ' : null).(int) (($t - $s) / 60).'min';
	else if($t - $s < 86400 && date('d') == date('d', $s)) return ($sentence ? 'Il y a ' : null).(int) (($t - $s) / 3600).'h';
	else if($t - $s < 86400) return 'Hier à '.date('h:i', $s);
	else return ($sentence ? 'Le ' : 'le ').date('d\/m\/y à h:i', $s);
}

function url($route = '', $abs = false, $echo = true) {
	$secure = $_SERVER['SERVER_PORT'] == 443;
	$server = $_SERVER['SERVER_NAME'];
	$dir = DIR_APP.'/';
	$return = ($abs ? 'http'.($secure ? 's' : '').'://'.$server : '').$dir.$route;
	if (!$echo)
		return $return;
	echo $return;
}


function isValidEmail($email_address) {
	//Norme RFC 5322
    return preg_match('/^(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){255,})(?!(?>(?1)"?(?>\\\[ -~]|[^"])"?(?1)){65,}@)'.
		'((?>(?>(?>((?>(?>(?>\x0D\x0A)?[\t ])+|(?>[\t ]*\x0D\x0A)?[\t ]+)?)'.
		'(\((?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-\'*-\[\]-\x7F]|\\\[\x00-\x7F]|(?3)))*(?2)\)))+(?2))|(?2))?)'.
		'([!#-\'*+\/-9=?^-~-]+|"(?>(?2)(?>[\x01-\x08\x0B\x0C\x0E-!#-\[\]-\x7F]|\\\[\x00-\x7F]))*(?2)")'.
		'(?>(?1)\.(?1)(?4))*(?1)@(?!(?1)[a-z\d-]{64,})(?1)(?>([a-z\d](?>[a-z\d-]*[a-z\d])?)'.
		'(?>(?1)\.(?!(?1)[a-z\d-]{64,})(?1)(?5)){0,126}|\[(?:(?>IPv6:(?>([a-f\d]{1,4})'.
		'(?>:(?6)){7}|(?!(?:.*[a-f\d][:\]]){8,})((?6)(?>:(?6)){0,6})?::(?7)?))|'.
		'(?>(?>IPv6:(?>(?6)(?>:(?6)){5}:|(?!(?:.*[a-f\d]:){6,})(?8)?::(?>((?6)(?>:(?6)){0,4}):)?))?'.
		'(25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)(?>\.(?9)){3}))\])(?1)$/isD', $email_address);
}


//Fonctions pour le filtrage des fiches
//On découpe en groupes séparés par des guillemets
//Les groupes non entourés par les " sont découpés en mots
//La recherche peut être limitée à une recherche stricte,
//dès qu'un élement du filtrage est trouvé on le supprime de la chaine étudiée
function filter_empty($str) {
	return $str != '';
}

function make_filtres($filtre) {

	//Découpe du filtre en sous-filtres
	$filtre = strtolower(preg_replace('/\s+/', ' ', secure($filtre)));
	$groupes = explode(secure('"'), $filtre);
	$filtres = array();
	
	foreach ($groupes as $i => $groupe) {
		$groupe = trim($groupe);

		//Si le groupe n'est pas entre guillemets ou si c'est le dernier groupe
		//On découpe alors le groupe à l'aide des espaces 
		if (!($i % 2) ||
			end($groupes) == $groupe)
			$filtres = array_merge($filtres, explode(' ', $groupe));


		//Sinon on conserve le groupe en entier
		else
			$filtres[] = $groupe;
	}

	//On supprime les filtres vides
	return array_filter($filtres, 'filter_empty');
}

function search_filtres($haystack, $filtres, $limitsearch = false) {
	$print = true;

	//Pour chacun des filtres...
	foreach ($filtres as $filtre) {

		//Si le filtre est non vide et qu'il n'est pas trouvé, alors on ne doit pas afficher l'élément concerné
		if ($filtre != '' &&
			strpos($haystack, $filtre) === false) {
			$print = false;
			break;
		}

		
		//Si le filtrage est limité en nombre d'occurence, on supprime la partie correspondant au filtre
		if ($limitsearch)
			$haystack = str_replace($filtre, '', $haystack);
	}

	return $print;
}