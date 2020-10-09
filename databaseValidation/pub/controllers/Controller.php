<?php
interface Controller{

	/**
	 * Gère la requête passée en paramètre
	 * @param Array $request La requête HTTP
	 */
	public function handle($request);

}
?>
