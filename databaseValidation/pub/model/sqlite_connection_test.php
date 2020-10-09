<?php

	require_once('/home/m3104/m3104_40/public_html/model/UtilisateurDAO.php');
	require_once('/home/m3104/m3104_40/public_html/model/ActiviteDAO.php');
	require_once('/home/m3104/m3104_40/public_html/model/DataGPSDAO.php');
	require_once('/home/m3104/m3104_40/public_html/model/Utilisateur.php');
	require_once('/home/m3104/m3104_40/public_html/model/Activite.php');
	require_once('/home/m3104/m3104_40/public_html/model/DataGPS.php');
	require_once('/home/m3104/m3104_40/public_html/model/SqliteConnection.php');


	//Etablissement de la connexion.
	$connexion = SqliteConnection::getInstance();

	//Creation d'un utilisateurDAO-
	$utilisateurDAO_1 = UtilisateurDAO::getInstance();


	try{
	    $dbc = SqliteConnection::getInstance()->getConnection();
	    //Remarque : en PHPMyAdmin, il parait qu'il y a une fonction de delete on cascade qui permet par exemple en prenant la classe mère, de supprimer toutes les autres associés à elle...
	    $query = "DELETE FROM DataGPS";
	    $stmt = $dbc->prepare($query);
	    $stmt->execute();

	    $query = "DELETE FROM Activite";
	    $stmt = $dbc->prepare($query);
	    $stmt->execute();

	    $query = "DELETE FROM Utilisateur";
	    $stmt = $dbc->prepare($query);
	    $stmt->execute();

	    echo "Préparation du test effectué !"."<br/>";
	}catch(PDOException $e){
	    print "Erreur: ".$e->getMessage()."<br/>";
		die();
	}


	//Creation d'un utilisateur
	$user_1 = $utilisateurDAO_1->create('testBdd@gmail.com','inconnu','inconnu','2000-04-23','male',178,68,'monMotDePasse');
	
	echo "<br/>"."<p><strong>Creation d'un utilisateur terminé</strong></p>"."<br/>";

	//Insertion de cet utilisateur dans la base de données
	$utilisateurDAO_1->insert($user_1);
	print_r($utilisateurDAO_1->findAll());
	echo "<br/>"."<p><strong>Succès de l'insertion utilisateur</strong></p>"."<br/>";

	echo "----------------------------------------------------"."<br/>";

	$act_DAO_1 = ActiviteDAO::getInstance();
	$act_1 = $act_DAO_1->create(2,'2019-09-11','IUT -> RU','testBdd@gmail.com');

	//Insertion de cette activité dans la base de données
	$act_DAO_1->insert($act_1);

	echo "<br/>"."<p><strong>Creation d'une activité terminé</strong></p>"."<br/>";
	print_r($act_DAO_1->findAll());
	echo "<br/>"."<p><strong>Succès de l'insertion d'une activité</strong></p>"."<br/>";

	echo "----------------------------------------------------"."<br/>";

	$data_DAO_1 = DataGPSDAO::getInstance();

	$data_1 = $data_DAO_1->create(2,'08:12:50',68,-2.756,47.852,19,2);
	//$data_1->init(2,'08:12:50',68,-2.756,47.852,19,2);
	//Insertion de cette activité dans la base de données
	$data_DAO_1->insert($data_1);

	echo "<br/>"."<p><strong>Creation d'une nouvelle donnée GPS terminé</strong></p>"."<br/>";
	print_r($data_DAO_1->findAll());
	echo "<br/>"."<p><strong>Succès de l'insertion données GPS</strong></p>"."<br/>";

	echo "----------------------------------------------------"."<br/>";
	//
	echo "<br/>"."<p><strong>Update de données GPS</strong></p>"."<br/>";
	$data_2 = $data_DAO_1->create(2,'09:55:12',120,-2.756,47.852,19,2);
	//Le principe c'est que l'on met en paramètre un dataGPS avec les modifications mais avec la même clé primaire que l'objet à update. Ainsi on peut supprimer l'objet en question déjà dans la base et insérer la même data (même id) avec les modifications.
	//$data_2->init(2,'09:55:12',120,-2.756,47.852,19,2);
	$data_DAO_1->update($data_2);
	print_r($data_DAO_1->findAll());

	echo "<br/>"."<p><strong>Succès de l'update de données GPS</strong></p>"."<br/>";
	//
	echo "<br/>"."<p><strong>Supression de données GPS</strong></p>"."<br/>";
	$data_DAO_1->delete($data_1);
	print_r($data_DAO_1->findAll());

	echo "<br/>"."<p><strong>Succès de la supression de données GPS</strong></p>"."<br/>";

	echo "----------------------------------------------------";

	echo "<br/>"."<p><strong>Update d'une activité </strong></p>"."<br/>";
	$act_2 = $act_DAO_1->create(2,'2019-09-14','IUT -> BU','testBdd@gmail.com');
	
	$act_DAO_1->update($act_2);
	print_r($act_DAO_1->findAll());

	echo "<br/>"."<p><strong>Succès de l'update d'une activité</strong></p>"."<br/>";
	//
	echo "<br/>"."<p><strong>Supression d'une activité</strong></p>"."<br/>";
	$act_DAO_1->delete($act_1);
	print_r($act_DAO_1->findAll());

	echo "<br/>"."<p><strong>Succès de la supression d'une activité</strong></p>"."<br/>";


	echo "----------------------------------------------------";

	echo "<br/>"."<p><strong>Update d'un utilisateur </strong></p>"."<br/>";
	$user_2 = $utilisateurDAO_1->create('testBdd@gmail.com','inconnuOuPas','connu','2000-04-23','male',178,68,'monMotDePasseAChangé');
	//$user_2->init('testBdd@gmail.com','inconnuOuPas','connu','2000-04-23','male',178,68,'monMotDePasseAChangé');
	$utilisateurDAO_1->update($user_2);
	print_r($utilisateurDAO_1->findAll());

	echo "<br/>"."<p><strong>Succès de l'update d'un utilisateur</strong></p>"."<br/>";
	//
	echo "<br/>"."<p><strong>Supression d'un utilisateur</strong></p>"."<br/>";
	$utilisateurDAO_1->delete($user_2);
	print_r($utilisateurDAO_1->findAll());

	echo "<br/>"."<p><strong>Succès de la supression d'un utilisateur</strong></p>"."<br/>";

	echo "<p><strong>Rechercher les activités d'un utilisateur particulier</strong></p>";
	
	$user_3 = $utilisateurDAO_1->create('test3Bdd@gmail.com','inconnu','inconnu','2000-04-23','male',178,68,'monMotDePasse');
	$user_4 = $utilisateurDAO_1->create('test4Bdd@gmail.com','inconnu','inconnu','2000-04-23','male',178,68,'monMotDePasse');

	$utilisateurDAO_1->insert($user_3);
	$utilisateurDAO_1->insert($user_4);

	$act_test1 = $act_DAO_1->create(6,'2019-09-11','IUT -> RU','test3Bdd@gmail.com');
	$act_DAO_1->insert($act_test1);

	$data_DAO_1->insert($data_DAO_1->create(6,'10:45:10',140,-2.756,47.852,19,6));
	$act_DAO_1->insert($act_DAO_1->create(7,'2019-09-11','IUT -> RU','test4Bdd@gmail.com'));
	$data_DAO_1->insert($data_DAO_1->create(7,'10:45:10',140,-2.756,47.852,19,7));

	print_r($act_DAO_1->searchForUser($user_3));

	echo "<br/>"."<p><strong>Rechercher les données cardiaques d'une activité particulière</strong></p>";

	print_r($data_DAO_1->searchForActivity($act_test1));

	echo "</br>"."----------"."</br>";

	$email = "test3Bdd@gmail.com";
	$find = UtilisateurDAO::getInstance()->find($email);
            $result = NULL;
            if($find){
                //S'il a trouvé la correspondance du mail, alors on cherche le mdp:
                try{
                    $dbc = SqliteConnection::getInstance()->getConnection();
                    // Prépare notre requête SQL
                    $query = "SELECT * FROM Utilisateur";
                    $stmt = $dbc->prepare($query);

                    // Attribue les valeurs
                    //$stmt->bindValue(':e',$email,PDO::PARAM_STR);
                
                    // Execute la phase de préparation de requête
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                }catch(PDOException $e){
                    print "Erreur: ".$e->getMessage()."<br/>";
                    die();
                }
            }
            var_dump($result['nom']);
?>