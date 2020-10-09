<?php
require_once('/home/m3104/m3104_40/public_html/model/Utilisateur.php');
require_once('/home/m3104/m3104_40/public_html/model/SqliteConnection.php');

class UtilisateurDAO {
	
	private static $dao;
	private static $liste;
	
	/**
	 * Constructeur privé (utilisation de singleton) de UtilisateurDAO
	 */
	private function _construct() {}
	
	/**
	 * Retourne une instance unique de la classe UtilisateurDAO (singleton)
	 * @return UtilisateurDAO $dao Instance de la classe UtilisateurDAO
	 */
	public final static function getInstance() {
		if(!isset(self::$dao)) {
			self::$dao= new UtilisateurDAO();
			self::$liste = array();
		}
		return self::$dao;
	}
	
	/**
	 * Retourne l'utilisateur lié à la session courante
	 * @return Array $result Un tableau associatif contenant les informations de l'utilisateur
	 */
	public final function getUserOfSession(){
		$result = NULL;
		try{
			$dbc = SqliteConnection::getInstance()->getConnection();
	         	// Prépare notre requête SQL
			$query = "SELECT * FROM Utilisateur WHERE email= :e";
			$stmt = $dbc->prepare($query);

	         	// Attribue les valeurs
			$stmt->bindValue(':e',$_SESSION['user'],PDO::PARAM_STR);
			
	         	// Execute la phase de préparation de requête
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
		} catch(PDOException $e){
			print "Erreur: ".$e->getMessage()."<br/>";
			die();
		}

		return $result;
	}

	/**
	 * Retourne un boolean indiquant si un utilisateur correspond à l'email passé en paramètre
	 * @param String $email
	 * @return Boolean $find Retourne true si un utilisateur correspond, false sinon
	 */  
	public final function find($email){
		$find = FALSE;
		try{
			$dbc = SqliteConnection::getInstance()->getConnection();
         		// Prépare notre requête SQL
			$query = "SELECT * FROM Utilisateur WHERE email= :e";
			$stmt = $dbc->prepare($query);

         	// Attribue les valeurs
			$stmt->bindValue(':e',$email,PDO::PARAM_STR);
			
         	// Execute la phase de préparation de requête
			$stmt->execute();
			$results = $stmt->fetch();
			
			if(!empty($results)){

				$find = TRUE;
			}
		}catch(PDOException $e){
			print "Erreur: ".$e->getMessage()."<br/>";
			die();
		}
		
		return $find;
	}

	/**
	 * Retourne tous les utilisateurs de la base de données
	 * @return Object $results Tous les utilisateurs de la base
	 */
	public final function findAll(){
		$dbc = SqliteConnection::getInstance()->getConnection();
		$query = "select * from Utilisateur";
		$stmt = $dbc->query($query);
		$results = $stmt->fetchALL(PDO::FETCH_CLASS, 'Utilisateur');
		return $results;
	}

	/**
	 * Créé un nouvel utilisateur avec les attributs passés en paramètres
	 * @param String $em Un email
	 * @param String $nom Un nom
	 * @param String $pren Un prénom
	 * @param Date $dateN Une date de naissance
	 * @param String $s Un sexe
	 * @param int $t Une taille
	 * @param int $p Un poids
	 * @param String $mdp Un mot de passe
	 * @return Utilisateur $user Un nouvel utilisateur
	 */
	public final function create($em, $nom, $pren, $dateN, $s, $t, $p, $mdp){
		$user = new Utilisateur();
		$user->init($em, $nom, $pren, $dateN, $s, $t, $p, $mdp);
		return $user;
	}

	/**
	 * Insert un utilisateur passé en paramètre dans la base de données
	 * @param Utilisateur $st Un utilisateur
	 */
	public final function insert($st){
		if($st instanceof Utilisateur){
			try{
				$dbc = SqliteConnection::getInstance()->getConnection();
         		// Prépare notre requête SQL
				$query = "insert into Utilisateur(email, nom, prenom,dateNaissance,sexe,taille,poids,motdepasse) values (:e,:n,:p,:d,:s,:t,:po,:m)";
				$stmt = $dbc->prepare($query);

         		// Attribue les valeurs
				$stmt->bindValue(':e',$st->getEmail(),PDO::PARAM_STR);
				$stmt->bindValue(':n',$st->getNom(),PDO::PARAM_STR);
				$stmt->bindValue(':p',$st->getPrenom(),PDO::PARAM_STR);
				$stmt->bindValue(':d',$st->getDateNaissance(),PDO::PARAM_STR);
				$stmt->bindValue(':s',$st->getSexe(),PDO::PARAM_STR);
				$stmt->bindValue(':t',$st->getTaille(),PDO::PARAM_STR);
				$stmt->bindValue(':po',$st->getPoids(),PDO::PARAM_STR);
				$stmt->bindValue(':m',$st->getMotDePasse(),PDO::PARAM_STR);
				array_push(self::$liste, $st);
         		// Execute la phase de préparation de requête
				$stmt->execute();
				array_push(self::$liste, $st);

			}catch(PDOException $e){
				print "Erreur: ".$e->getMessage()."<br/>";
				die();
			}
		}
	}

	/**
	 * Met à jour un utilisateur avec le nouveau passé en paramètre
	 * @param Utilisateur $obj Un nouvel utilisateur
	 */
	public function update($obj){
		if($obj instanceof Utilisateur){
			$stmt = $this->delete($obj);
			$stmt = $this->insert($obj);
		}
	}  	
	
	/**
	 * Supprime un utilisateur passé en paramètre de la base de données
	 * @param Utilisateur $st Un utilisateur
	 */
	public function delete($st){
		if($st instanceof Utilisateur){
			try{
				$dbc = SqliteConnection::getInstance()->getConnection();
         		// Prépare notre requête SQL
				$query = "delete from Utilisateur where email = :s ";
				$stmt = $dbc->prepare($query);

         		// Attribue les valeurs
				$stmt->bindValue(':s',$st->getEmail(),PDO::PARAM_STR);

         		// Execute la phase de préparation de requête
				$stmt->execute();
				$id = $st->getEmail();
				$element = NULL;
				foreach(self::$liste as $value){
					if($id == $value->getEmail()){
						$element = $value;
						unset(self::$liste[array_search($element, self::$liste)]);
					}
				}
			}catch(PDOException $e){
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
}
?>
