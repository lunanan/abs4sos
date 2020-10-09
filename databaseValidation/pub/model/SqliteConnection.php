<?php

/**
* Cette classe gère la connexion à notre base de données pour pouvoir gèrer les données.
* @author Nicolas BLANCHARD
*/

class SqliteConnection{

	private static $instance;
	private static $db;

	/**
	 * Constructeur de SqliteConnection, se connecte à la base de données
	 */
	private function __construct(){

		try{
			self::$db = new PDO('sqlite:./model/db.sqlite');
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//echo "Connexion effectuée !"."<br/>";
		} catch(PDOException $e){
			print "Erreur: ".$e->getMessage()."<br/>";
			die();
		}
	}

	/**
	 * Retourne une instance unique de SqliteConnection (singleton)
	 * @return PDO $instance Une instance unique de SqliteConnection
	 */
	public final static function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new SqliteConnection();
		}
		return self::$instance;
	}

	/**
	 * Retourne la connexion à la base de données
	 * @return PDO $db La connexion à la base de données
	 */
	public final static function getConnection(){
		return self::$db;
	}
}

?>