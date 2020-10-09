<?php

/**
* Cette classe représente une activité qu'un utilisateur pratique.
* @author Nicolas BLANCHARD & Alan GASSINE
*/

class Activite{

	private $idAct;
	private $laDate;
	private $description;
	private $unUtilisateur;

	/**
	 * Constructeur de Utilisateur
	 */
	public function __construct(){}

	/**
	 * Initialise les informations d'une Activite
	 * @param int $id Un id
	 * @param Date $ld Une date
	 * @param String $d Une description
	 * @param Utilisateur $user Un utilisateur
	 */
	public function init($id, $ld, $d, $user){
			//initialise une activité
		$this->idAct = $id;
		$this->laDate = $ld;
		$this->description = $d;
		$this->unUtilisateur = $user;
	}

	/**
	 * Retourne la date de l'Activite
	 * @return String $this->laDate La date de l'Activite
	 */
	public function getDate(){
		return $this->laDate;
	}

	/**
	 * Retourne la desription de l'Activite
	 * @return String $this->description La description de l'Activite
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * Retourne l'Utilisateur de l'Activite
	 * @return String $this->unUtilisateur L'Utilisateur de l'Activite
	 */
	public function getUnUtilisateur(){
		return $this->unUtilisateur;
	}

	/**
	 * Retourne l'id de l'Activite
	 * @return String $this->idAct L'id de l'Activite
	 */
	public function getIdAct(){
		return $this->idAct;
	}

	/**
	 * Retourne l'Activite sous forme de chaîne de caractères
	 * @return String Les attributs de l'Activite
	 */
	public function  __toString() { return $this->idAct. " ". $this->laDate. " ". $this->description. " ". $this->unUtilisateur;}
}
?>