<?php

/**
* Cette classe représente les données GPS d'une activité.
* @author Nicolas BLANCHARD & Alan GASSINE
*/

class DataGPS {
	private $idData;
	private $temps;
	private $cardio;
	private $longitude;
	private $latitude;
	private $altitude;
	private $uneActivite;

	/**
	 * Constructeur de Utilisateur
	 */
	public function _construct() {}

	/**
	 * Initialise les informations d'un utilisateur
	 * @param int $id Un id
	 * @param Date $temps Un temps
	 * @param int $cardio Une fréquence cardiaque
	 * @param double $longitude Une longitude
	 * @param double $latitude Une latitude
	 * @param int $altitude Une altitude
	 * @param Activite $uneActivite Une activite
	 */
	public function init($idData, $temps, $cardio, $longitude, $latitude, $altitude, $uneActivite) {
		$this->idData = $idData;
		$this->temps = $temps;
		$this->cardio = $cardio;
		$this->longitude = $longitude;
		$this->latitude = $latitude;
		$this->altitude = $altitude;
		$this->uneActivite = $uneActivite;
	}

	/**
	 * Retourne l'id de la DataGPS
	 * @return String $this->idData L'id de la DataGPS
	 */
	public function getIdData() {
		return $this->idData;
	}

	/**
	 * Retourne le temps de la DataGPS
	 * @return String $this->temps Le temps de la DataGPS
	 */
	public function getTemps() {
		return $this->temps;
	}

	/**
	 * Retourne la fréquence cardiaque de la DataGPS
	 * @return String $this->cardio La fréquence cardiaque de la DataGPS
	 */	public function getCardio() {
		return $this->cardio;
	}

	/**
	 * Retourne la longitude de la DataGPS
	 * @return String $this->longitude La longitude de la DataGPS
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Retourne la latitude de la DataGPS
	 * @return String $this->latitude La latitude de la DataGPS
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Retourne l'altitude de la DataGPS
	 * @return String $this->altitude L'altitude de la DataGPS
	 */
	public function getAltitude() {
		return $this->altitude;
	}

	/**
	 * Retourne l'Activite de la DataGPS
	 * @return String $this->uneActivite L'Activite de la DataGPS
	 */
	public function getUneActivite() {
		return $this->uneActivite;
	}

	/**
	 * Retourne la DataGPS sous forme de chaîne de caractères
	 * @return String Les attributs de la DataGPS
	 */
	public function  __toString() { 
		return $this->idData." ". $this->temps." ".$this->cardio." ".$this->longitude." ".$this->latitude." ".$this->altitude." ".$this->uneActivite;}
}
?>
