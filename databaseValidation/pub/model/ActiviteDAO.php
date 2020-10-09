<?php
require_once('/home/m3104/m3104_40/public_html/model/Utilisateur.php');
require_once('/home/m3104/m3104_40/public_html/model/Activite.php');

/**
* Cette classe, issu du patron DAO, gère et créer des instances d'activités.
* @author Nicolas BLANCHARD & Alan GASSINE
*/

class ActiviteDAO{
	
	private static $dao;
	private static $liste;
	
	/**
	 * Constructeur privé (utilisation de singleton) de ActiviteDAO
	 */
	private function _construct() {}
	
	/**
	 * Retourne une instance unique de la classe ActiviteDAO (singleton)
	 * @return ActiviteDAO $dao Instance de la classe ActiviteDAO
	 */
	public final static function getInstance() {
		if(!isset(self::$dao)) {
			self::$dao= new ActiviteDAO();
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
		$query = "select * from Activite";
		$stmt = $dbc->query($query);
		$results = $stmt->fetchALL(PDO::FETCH_CLASS, 'Activite');
		return $results;
	}

	/**
	 * Créé une nouvel activite avec les attributs passés en paramètres
	 * @param int $id Un id d'activite
	 * @param Date $ld Une date
	 * @param String $d Une description
	 * @param Utilisateur $user Un utilisateur
	 * @return Activite $activite Une nouvelle activite
	 */
	public final function create($id, $ld, $d, $user){
		$activite = new Activite();
		$activite->init($id, $ld, $d, $user);
		return $activite;
	}

	/**
	 * Insert une activite passée en paramètre dans la base de données
	 * @param Activite $st Une activite
	 */
	public final function insert($st){
		if($st instanceof Activite){
			try{
				$dbc = SqliteConnection::getInstance()->getConnection();
	         	// Prépare notre requête SQL
				$query = "insert into Activite(idAct,laDate,description,unUtilisateur) values (:id,:ld,:d,:us)";
				$stmt = $dbc->prepare($query);

	         	// Attribue les valeurs
				$stmt->bindValue(':id',$st->getIdAct(),PDO::PARAM_STR);
				$stmt->bindValue(':ld',$st->getDate(),PDO::PARAM_STR);
				$stmt->bindValue(':d',$st->getDescription(),PDO::PARAM_STR);
				$stmt->bindValue(':us',$st->getUnUtilisateur(),PDO::PARAM_STR);

	         	// Execute la phase de préparation de requête
				$stmt->execute();
				array_push(self::$liste, $st);
			} catch(PDOException $e){
				print "Erreur: ".$e->getMessage()."<br/>";
				die();
			}
		}
	}

	/**
	 * Met à jour une Activite avec la nouvelle passée en paramètre
	 * @param Activite $obj Une nouvelle Activite
	 */
	public function update($obj){
		if($obj instanceof Activite){
			$stmt = $this->delete($obj);
			$stmt = $this->insert($obj);
		}
	}  	
	
	/**
	 * Supprime une Activite passée en paramètre de la base de données
	 * @param Activite $st Une Activite
	 */
	public function delete($st){
		if($st instanceof Activite){
			try{
				$dbc = SqliteConnection::getInstance()->getConnection();
	         	// Prépare notre requête SQL
				$query = "delete from Activite where idAct = :s ";
				$stmt = $dbc->prepare($query);

	         	// Attribue les valeurs
				$stmt->bindValue(':s',$st->getIdAct(),PDO::PARAM_STR);

	         	// Execute la phase de préparation de requête
				$stmt->execute();
				$id = $st->getIdAct();
				$element = NULL;
				foreach(self::$liste as $value){
					if($id == $value->getIdAct()){
						$element = $value;
						unset(self::$liste[array_search($element, self::$liste)]);
					}
				}
	         	//A noter que cette fonction peut produire des trous dans la liste !
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
	 * Recherche les Activite de l'Utilisateur passé en paramètre
	 * @param Utilisateur $user L'Utilisateur à rechercher
	 * @return Array $results Les Activite trouvées
	 */
	public final function searchForUser($user){
		$results = NULL;
		if($user instanceof Utilisateur){
			try{
				$userMail = $user->getEmail();
				$dbc = SqliteConnection::getInstance()->getConnection();
	         		// Prépare notre requête SQL
				$query = "SELECT * FROM Activite WHERE unUtilisateur='$userMail' ";
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
