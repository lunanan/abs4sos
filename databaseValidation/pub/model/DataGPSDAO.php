<?php
//Cette classe représente ActivityEntryDAO
require_once('/home/m3104/m3104_40/public_html/model/DataGPS.php');
require_once('/home/m3104/m3104_40/public_html/model/Activite.php');

/**
* Cette classe, issu du patron DAO, gère et créer des instances de données GPS.
* @author Nicolas BLANCHARD & Alan GASSINE
*/

class DataGPSDAO {
	
	private static $dao;
	private static $liste;
	
	/**
	 * Constructeur privé (utilisation de singleton) de DataGPSDAO
	 */
	private function _construct() {}
	
	/**
	 * Retourne une instance unique de la classe DataGPSDAO (singleton)
	 * @return DataGPSDAO $dao Instance de la classe DataGPSDAO
	 */
	public final static function getInstance() {
		if(!isset(self::$dao)) {
			self::$dao= new DataGPSDAO();
			self::$liste = array();
		}
		return self::$dao;
	}

	/**
	 * Retourne tous les DataGPS de la base de données
	 * @return Object $results Tous les DataGPS de la base
	 */
	public final function findAll(){
		$dbc = SqliteConnection::getInstance()->getConnection();
		$query = "select * from DataGPS";
		$stmt = $dbc->query($query);
		$results = $stmt->fetchALL(PDO::FETCH_CLASS, 'DataGPS');
		return $results;
	}

	/**
	 * Créé une nouvelle DataGPS avec les attributs passés en paramètres
	 * @param int $idData Un id de donnée
	 * @param Date $temps Une date
	 * @param int $cardio Une fréquence cardiaque
	 * @param double $longitude Une longitude
	 * @param double $latitude Une latitude
	 * @param int $altitude Une altitude
	 * @param Activite $uneActivite Une activite
	 * @return DataGPS $datagps Une nouvelle DataGPS
	 */
	public final function create($idData, $temps, $cardio, $longitude, $latitude, $altitude, $uneActivite){
		$datagps = new DataGPS();
		$datagps->init($idData, $temps, $cardio, $longitude, $latitude, $altitude, $uneActivite);
		return $datagps;
	}

	/**
	 * Insert une DataGPS passée en paramètre dans la base de données
	 * @param DataGPS $st Une DataGPS
	 */
	public final function insert($st){
		if($st instanceof DataGPS){
			try{
				$dbc = SqliteConnection::getInstance()->getConnection();
	         	// Prépare notre requête SQL
				$query = "insert into DataGPS(idData, temps, cardio,longitude,latitude,altitude,uneActivite) values (:id,:t,:c,:lo,:la,:a,:uA)";
				$stmt = $dbc->prepare($query);

	         	// Attribue les valeurs
				$stmt->bindValue(':id',$st->getIdData(),PDO::PARAM_STR);
				$stmt->bindValue(':t',$st->getTemps(),PDO::PARAM_STR);
				$stmt->bindValue(':c',$st->getCardio(),PDO::PARAM_STR);
				$stmt->bindValue(':lo',$st->getLongitude(),PDO::PARAM_STR);
				$stmt->bindValue(':la',$st->getLatitude(),PDO::PARAM_STR);
				$stmt->bindValue(':a',$st->getAltitude(),PDO::PARAM_STR);
				$stmt->bindValue(':uA',$st->getUneActivite(),PDO::PARAM_STR);
				array_push(self::$liste, $st);
	         	// Execute la phase de préparation de requête
				$stmt->execute();
			} catch(PDOException $e){
				print "Erreur: ".$e->getMessage()."<br/>";
				die();
			}
		}
	}

	/**
	 * Met à jour une DataGPS avec la nouvelle passée en paramètre
	 * @param DataGPS $obj Une nouvelle DataGPS
	 */
	public function update($obj){
		if($obj instanceof DataGPS){
			$stmt = $this->delete($obj);
			$stmt = $this->insert($obj);
		}
	}  	 	
	
	/**
	 * Supprime une DataGPS passée en paramètre de la base de données
	 * @param DataGPS $st Une DataGPS
	 */
	public function delete($st){
		if($st instanceof DataGPS){
			try{
				$dbc = SqliteConnection::getInstance()->getConnection();
	         	// Prépare notre requête SQL
				$query = "delete from DataGPS where idData = :s ";
				$stmt = $dbc->prepare($query);

	         	// Attribue les valeurs
				$stmt->bindValue(':s',$st->getIdData(),PDO::PARAM_STR);

	         	// Execute la phase de préparation de requête
				$stmt->execute();
				$id = $st->getIdData();
				$element = NULL;
				foreach(self::$liste as $value){
					if($id == $value->getIdData()){
						$element = $value;
						unset(self::$liste[array_search($element, self::$liste)]);
					}
				}
			} catch(PDOException $e){
				print "Erreur: ".$e->getMessage()."<br/>";
				die();
			}
		}
	}	

	/**
	 * Retourne la variable globale liste
	 * @return Array $liste La variable liste
	 */
	public function getListe(){
		return self::$liste;
	}

	/**
	 * Recherche les DataGPS de l'Activite passé en paramètre
	 * @param Activite $act L'Activite à rechercher
	 * @return Array $results Les DataGPS trouvées
	 */
	public final function searchForActivity($act){
		$results = NULL;
		if($act instanceof Activite){
			try{
				$idact = $act->getIdAct();
				$dbc = SqliteConnection::getInstance()->getConnection();
	         		// Prépare notre requête SQL
				$query = "SELECT * FROM DataGPS WHERE uneActivite=$idact";
				$stmt = $dbc->prepare($query);
	         		// Execute la phase de préparation de requête
				$stmt->execute();

				$results = $stmt->fetchAll();

			}catch(PDOException $e){
				print "Erreur: ".$e->getMessage()."<br/>";
				die();
			}
		}
		return $results;
	}

}
?>
