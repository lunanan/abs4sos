<?php
require_once('Controller.php');
require_once('./model/SqliteConnection.php');
/**
* Cette classe est utilisée pour gèrer le processus de recherche de l'utilisateur
* @author Nicolas BLANCHARD
* @version 1.1
*/
class Task6Controller implements Controller{

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
					<h1 class><strong>Results of the task 6</strong></h1>
				</div>
			</div>';

		function stringContained($base, $acomp){
            //Est-ce que $acomp est compris dans la base ?
            $trouve = false;
            if(strpos($base,$acomp) !== false){
                $trouve = true;
            }
            return $trouve;
        }
		
		//BODY
		$elem = $_POST['archi_choices'];
		echo '
				<div class="container text-center center-block">
					<div class="container">
						<div class="row">
						<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
						<h2>IS Relations with '.$elem.'  :</h2>
						';

		$results = array();
		$parent = null;
		$parent_id = null;


        $stop = false;
        $elem_name = $elem;
        $res = array();
        $poursui = 0;
        //poursui est utile uniquement pour le deboggage.
        function extractRecursiv($stop,$elem_name,$res,$poursui){

        	if($stop == false){
        		//On cherche l'id de l'élément dans $read
        		try {
		            $dbc = SqliteConnection::getInstance()->getConnection();
		            $query = "select id from 'public.element_type' where name = :r";
		            $stmt = $dbc->prepare($query);
		            
		            $stmt->bindValue(':r',$elem_name,PDO::PARAM_STR);
		            $stmt->execute();
		            $read = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
		        } catch(PDOException $e){
		            $e->getMessage();
		        }

                $read = $read['id'];

                $res_parent = array();
                try {
		            $dbc = SqliteConnection::getInstance()->getConnection();
		            $query = "select abstract_element_type_id from 'public.element_type_is_relation' where concrete_element_type_id = :r";
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
				            $query = "select name from 'public.element_type' where id = :r";
				            $stmt = $dbc->prepare($query);
				            
				            $stmt->bindValue(':r',$result['abstract_element_type_id'],PDO::PARAM_STR);
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
				        $poursui++;
				        //On lance la récursivité:
				        extractRecursiv($stop,$name,$res,$poursui);
		        	}
				}else{
					$stop = true;
				}

				
        	}
        	return $res;
        }

        $res = extractRecursiv($stop,$elem_name,$res,$poursui);
        //var_dump($res);

        if(!empty($res)){
			//Display results
			foreach($res as $r){
				//var_dump($r);
				echo '<ul>';
				echo '<li><strong>'.$r.'</strong></li>';
				echo '</ul>';
			}
		}


			/*//Display results
			foreach($results as $r){
				//var_dump($r);
				echo '<ul>';
				echo '<li><strong>'.$r['name'].'</strong></li>';
				echo '</ul>';
			}*/

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