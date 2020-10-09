<?php
require_once('Controller.php');

/**
 * Cette classe permet de tester le fonctionnement des contrôleur.
 * On négligera alors son utilisation dans l'application.
 * @author Nicolas BLANCHARD
 */
class MainController implements Controller{

	/**
     * Gère la requête d'affichage de la page d'accueil passée en paramètre
     * @param Array $request La requête HTTP
     */
	public function handle($request){
		$_SESSION['message']= 'Welcome on //////// !';
	}
}
?>
