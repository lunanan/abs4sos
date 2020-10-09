<?php

// INDEX PHP - ENTRY POINT
// NAN ZHANG MESSE / NICOLAS BLANCHARD PROJECT

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('controllers/ApplicationController.php');

//Si la super-globale $_REQUEST est vide, alors rediriger l'utilisateur vers l'accueil.
if(empty($_REQUEST)){
	$_REQUEST['page']='accueil';
}

$controller = ApplicationController::getInstance()->getController($_REQUEST);

//Délégation à un controleur.
if($controller != null){
	include "controllers/$controller.php";
	(new $controller())->handle($_REQUEST);
}

//Attribution de la vue.
$view = ApplicationController::getInstance()->getView($_REQUEST);
if($view != null){
	include "views/$view.php";
}

?>
