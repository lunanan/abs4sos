<?php

/**
* Cette classe représente les utilisateur de l'application Sport_Track.
* @author Nicolas BLANCHARD & Alan GASSINE
*/

class Utilisateur{

	private $email;
	private $nom;
	private $prenom;
	private $dateNaissance;
	private $sexe;
	private $taille;
	private $poids;
	private $motdepasse;

	/**
	 * Constructeur de Utilisateur
	 */
	public function __construct(){}

	/**
	 * Initialise les informations d'un utilisateur
	 * @param String $em Un email
	 * @param String $nom Un nom
	 * @param String $pren Un prénom
	 * @param Date $dateN Une date de naissance
	 * @param String $s Un sexe
	 * @param int $t Une taille
	 * @param int $p Un poids
	 * @param String $mdp Un mot de passe
	 */
	public function init($em, $nom, $pren, $dateN, $s, $t, $p, $mdp){
		$this->email = $em;
		$this->nom = $nom;
		$this->prenom = $pren;
		$this->dateNaissance = $dateN;
		$this->sexe = $s;
		$this->taille = $t;
		$this->poids = $p;
		$this->motdepasse = $mdp;
	}

	/**
	 * Retourne l'email de l'utilisateur
	 * @return String $this->email L'email de l'utilisateur
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * Retourne le nom de l'utilisateur
	 * @return String $this->nom Le nom de l'utilisateur
	 */
	public function getNom(){
		return $this->nom;
	}

	/**
	 * Retourne le prenom de l'utilisateur
	 * @return String $this->prenom Le prenom de l'utilisateur
	 */
	public function getPrenom(){
		return $this->prenom;
	}

	/**
	 * Retourne la date de naissance de l'utilisateur
	 * @return Date $this->dateNaissance La date de naissance de l'utilisateur
	 */
	public function getDateNaissance(){
		return $this->dateNaissance;
	}

	/**
	 * Retourne le sexe de l'utilisateur
	 * @return String $this->sexe Le sexe de l'utilisateur
	 */
	public function getSexe(){
		return $this->sexe;
	}

	/**
	 * Retourne la taille de l'utilisateur
	 * @return String $this->taille La taille de l'utilisateur
	 */
	public function getTaille(){
		return $this->taille;
	}

	/**
	 * Retourne le poids de l'utilisateur
	 * @return String $this->poids Le poids de l'utilisateur
	 */
	public function getPoids(){
		return $this->poids;
	}

	/**
	 * Retourne le mot de passe de l'utilisateur
	 * @return String $this->motdepasse Le mot de passe de l'utilisateur
	 */
	public function getMotdepasse(){
		return $this->motdepasse;
	}

	/**
	 * Retourne l'utilisateur sous forme de chaîne de caractères
	 * @return String Les attributs de l'utilisateur
	 */
	public function  __toString() { return $this->email. " ". $this->nom. " ". $this->prenom. " ". $this->dateNaissance. " ". $this->sexe. " ". $this->taille. " ". $this->poids. " ". $this->motdepasse;}
}
?>