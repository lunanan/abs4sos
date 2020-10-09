<?php
require_once('Controller.php');
require_once('./model/SqliteConnection.php');
/**
* Cette classe est utilisée pour gèrer le processus de recherche de l'utilisateur
* @author Nicolas BLANCHARD
* @version 1.1
*/
class Task13Controller implements Controller{

    /**
     * Gère la requête de l'utilisateur passée en paramètre
     * @param Array $request La requête HTTP
     */
    public function handle($request){
	//HEADER
		require_once('./model/SqliteConnection.php');
		$width = 2141/3.9;
		$height = 500/3.9;
		//PRELUDE
		echo '<html>
				<head>
					<meta charset="utf-8"/>
					<title>ABS4SOS</title>
					<link rel="stylesheet" href="./resources/css/css_bootstrap/bootstrap.min.css">
					<link rel="stylesheet" href="./resources/css/css_bootstrap/style_accueil.css">
				</head>

				<body>';
		echo '<div class="container-fluid blue">
				<div class="container title_head center-block">
					<h1 class><strong>Results of the task 11, 12 & 13</strong></h1>
				</div>
			</div>';

		function extractRecursiv($stop,$elem_id,$res,$poursui){

        	if($stop == false){

                $read = $elem_id;

                $res_parent = array();
                try {
		            $dbc = SqliteConnection::getInstance()->getConnection();
		            $query = "select abstract_vulnerability_type_id from 'public.vulnerability_type_is_relation' where concrete_vulnerability_type_id = :r";
		            $stmt = $dbc->prepare($query);
		            
		            $stmt->bindValue(':r',$read,PDO::PARAM_STR);
		            $stmt->execute();
		            $res_parent = $stmt->fetchAll();//PDO::FETCH_BOTH par défaut
		        } catch(PDOException $e){
		            $e->getMessage();
		        }
		        
		        if(!empty($res_parent)){
		        	foreach($res_parent as $result){
		        		//Cherchons le nom du concerné.
		        		try {
				            $dbc = SqliteConnection::getInstance()->getConnection();
				            $query = "select name from 'public.vulnerability_type' where id = :r";
				            $stmt = $dbc->prepare($query);
				            
				            $stmt->bindValue(':r',$result['abstract_vulnerability_type_id'],PDO::PARAM_STR);
				            $stmt->execute();
				            $name = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
				        } catch(PDOException $e){
				            $e->getMessage();
				        }
				        
				        if(is_array($name)){
				        	$name = $name['name'];
				        }
				        //On l'ajoute dans la liste des résultats :
				        array_push($res,$name);
				        //On cherche son id pour continuer
				        try {
				            $dbc = SqliteConnection::getInstance()->getConnection();
				            $query = "select id from 'public.vulnerability_type' where name = :r";
				            $stmt = $dbc->prepare($query);
				            
				            $stmt->bindValue(':r',$name,PDO::PARAM_STR);
				            $stmt->execute();
				            $myid = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
				        } catch(PDOException $e){
				            $e->getMessage();
				        }
				        $poursui++;
				        //On lance la récursivité:
				        extractRecursiv($stop,$myid,$res,$poursui);
		        	}
				}else{
					$stop = true;
				}

				
        	}
        	return $res;
        }
		function stringContained($base, $acomp){
            //Est-ce que $acomp est compris dans la base ?
            $trouve = false;
            if(strpos($base,$acomp) !== false){
                $trouve = true;
            }
            return $trouve;
        }
		
		//BODY
		$va = $_POST['va_choices'];
		$da = $_POST['da_choices'];

		echo '
				<div class="container text-center center-block">
					<div class="container">
						<div class="row">
						<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
						<h2>Tasks 11 & 12 : Verify the matching between '.$va.' and '.$da.' :</h2>
						';
		$res = false;
		if(stringContained($da,$va)){
			$res = true;
			//On lance alors la tâche 13 : cherchons les vulnerability types correspondant.
			echo '<h3>We found a match between '.$da.' and '.$va.' !</h3>';
			echo '</br>';
			echo '<h2>Task 13 : Identify Vulnerability types';
			//VA ID
			try{
				$connect = SqliteConnection::getInstance()->getConnection();
				$dbc = SqliteConnection::getInstance()->getConnection();
				$query = "select id from 'public.va','public.attack_pivot' where 'public.va'.attack_pivot_id = 'public.attack_pivot'.id and 'public.attack_pivot'.name = :va";
				$stmt = $dbc->prepare($query);
				$stmt->bindValue(':va',$va,PDO::PARAM_STR);
				$stmt->execute();
				$vaid = $stmt->fetch(PDO::FETCH_ASSOC);
				
			}catch(PDOException $e){
				print_r($e->getMessage());
			}
			//DA ID
			try{
				$connect = SqliteConnection::getInstance()->getConnection();
				$dbc = SqliteConnection::getInstance()->getConnection();
				$query = "select id from 'public.architecture_element' where name = :da";
				$stmt = $dbc->prepare($query);
				$stmt->bindValue(':da',$da,PDO::PARAM_STR);
				$stmt->execute();
				$daid = $stmt->fetch(PDO::FETCH_ASSOC);
				
			}catch(PDOException $e){
				print_r($e->getMessage());
			}

			try{
				$connect = SqliteConnection::getInstance()->getConnection();
				$dbc = SqliteConnection::getInstance()->getConnection();
				$query = "select name from 'public.vda' where architecture_element_id = :daid and attack_pivot_id = :vaid";
				$stmt = $dbc->prepare($query);
				$stmt->bindValue(':daid',$daid['id'],PDO::PARAM_STR);
				$stmt->bindValue(':vaid',$vaid['id'],PDO::PARAM_STR);
				$stmt->execute();
				$vda = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
			}catch(PDOException $e){
				print_r($e->getMessage());
			}

			try{
				$connect = SqliteConnection::getInstance()->getConnection();
				$dbc = SqliteConnection::getInstance()->getConnection();
				$query = "select vulnerability_type_id from 'public.vda' where architecture_element_id = :daid and attack_pivot_id = :vaid";
				$stmt = $dbc->prepare($query);
				$stmt->bindValue(':daid',$daid['id'],PDO::PARAM_STR);
				$stmt->bindValue(':vaid',$vaid['id'],PDO::PARAM_STR);
				$stmt->execute();
				$vda_vuln_ID = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
			}catch(PDOException $e){
				print_r($e->getMessage());
			}

			if(!empty($vda)){
				$vuln_list = array();
				echo '<h2> The vda concerned is '.$vda['name'].'</h2>';

				$stop = false;
				$poursui = 0;
				$res = array();
				$res = extractRecursiv($stop,$vda_vuln_ID,$res,$poursui);
				

				foreach($res as $r){
		        	//var_dump($r);
					echo '<ul>';
					echo '<li><strong>'.$r['name'].'</strong></li>';
					echo '</ul>';
				}

			}else{
				echo '<h2>No VDA has been found...</h2>';
			}

		}else{
			echo '<h3>No Match between '.$da.' and '.$va.'</h3>';
		}


						echo '
					</div>
				</div>';

				//FOOTER
				$width = 1200/15;
			    $height = 1237/15;

			    $width_iri = 512/2;
			    $height_iri = 159/2;
				echo "<div class='container-fluid blue_footer'>
			    <div class='container'>
			    <div class='row'>
				
			    <div class='col-xs-4 col-md-4 col-lg-4'>
			    <img class='img-responsive' src='./resources/img/irisa_logo_transparent.png' width=".$width_iri."height=".$height_iri."'>
			    </div>

			    <div class='col-xs-4 col-md-4 col-lg-4'>
			    <p><em>Developped at IRISA</em></p>
			    <p><em>@Nan MESSE @Nicolas BLANCHARD</em></p>
			    </div>

			    <div class='col-xs-4 col-md-4 col-lg-4'>
			    <img class='img-responsive' src='./resources/img/dga_logo.png' width=".$width."height=".$height."'>
			    </div>

			    </div>
			    </div>
			    </div>";
			    echo '</body> </html>';
			
		}
	}
?>