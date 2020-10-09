<?php
require_once('Controller.php');
require_once('./model/SqliteConnection.php');
/**
* Cette classe est utilisée pour gèrer le processus de recherche de l'utilisateur
* @author Nicolas BLANCHARD
* @version 1.1
*/
class Task9Controller implements Controller{

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
					<h1 class><strong>Results of the task 9</strong></h1>
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
						<h2>Tactics which matches with '.$elem.' :</h2>
						';
        $res = array();

				//On cherche le parent premier
				try {
		            $dbc = SqliteConnection::getInstance()->getConnection();
		            $query = "select id from 'public.attack_pivot' where name = :n";
		            $stmt = $dbc->prepare($query);
		            
		            $stmt->bindValue(':n',$elem,PDO::PARAM_STR);
		            $stmt->execute();
		            $parent_prems = $stmt->fetchAll(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
		        } catch(PDOException $e){
		            $e->getMessage();
		        }

		        function unique_multidim_array($array, $key) {
				    $temp_array = array();
				    $i = 0;
				    $key_array = array();
				   
				    foreach($array as $val) {
				        if (!in_array($val[$key], $key_array)) {
				            $key_array[$i] = $val[$key];
				            $temp_array[$i] = $val;
				        }
				        $i++;
				    }
				    return $temp_array;
				}

		        function fullDeep($kid, &$result,$allinone, $stop,$poursui){
		        	if(!$stop){
		        		//VERIFIER SI LE KID EST DANS TACTIC SINON ON AJOUTE PAS.
		        		//var_dump($kid);
		        		try {
				            $dbc = SqliteConnection::getInstance()->getConnection();
				            $query = "select attack_pivot_id from 'public.tactic' where attack_pivot_id = :id";
				            $stmt = $dbc->prepare($query);   
				            $stmt->bindValue(':id',$kid["attack_pivot_children_id"],PDO::PARAM_STR);
				            $stmt->execute();
				            $verify = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
				        } catch(PDOException $e){
				            $e->getMessage();
				        }
				        //var_dump($verify);
				        //echo '</br>';
		        		if(!empty($verify)){
		        			array_push($result,$kid);
		        			//var_dump($result);
				        	//echo '</br>';
		        		}
		        		array_push($allinone,$kid);

		        		//var_dump($allinone);
		        		$enfantAlreadyTested = false;
		        		$s = array();
		        		$find = false;
		        		//On cherche ses enfants
		        		//var_dump($kid);
		        		try {
				            $dbc = SqliteConnection::getInstance()->getConnection();
				            if($poursui = 0){
						        $query = "select id from 'public.relation' where attack_pivot_parent_id = :p and type = 'REALIZATION'";
						    }else{
						       	$query = "select id from 'public.relation' where attack_pivot_parent_id = :p";
						    }
				            $stmt = $dbc->prepare($query);   
				            $stmt->bindValue(':p',$kid["attack_pivot_children_id"],PDO::PARAM_STR);
				            $stmt->execute();
				            $idrelation = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
				        } catch(PDOException $e){
				            $e->getMessage();
				        }
				        //var_dump($idrelation);
				        $children = array();
				        if($idrelation){
			        		try {
					            $dbc = SqliteConnection::getInstance()->getConnection();
					            $query = "select attack_pivot_children_id from 'public.attack_pivot_children_relation' where relation_id = :r";
					            $stmt = $dbc->prepare($query);   
					            $stmt->bindValue(':r',$idrelation['id'],PDO::PARAM_STR);
					            $stmt->execute();
					            $children = $stmt->fetchAll(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
					        } catch(PDOException $e){
					            $e->getMessage();
					        }
					    }
					    
				        /*echo '</br>';
				        var_dump($children);
				        echo '</br>';*/
				        //On ajoute dans la liste s tous les enfants non encore traités.
				        if(!empty($children)){
				        	foreach($children as $c){
				        		foreach($allinone as $r){
				        			if($c == $r){
				        				$find = true;
				        			}
				        		}
				        		if($find == false){
				        			array_push($s,$c);
				        		}
				        		$find = false;
				        	}
				        }

				        if(!empty($s)){
				        	$poursui++;
				        	fullDeep($s[0],$result,$allinone,$stop,$poursui);
				        	//echo 'Continue'.'</br>';
				        	//var_dump($s[0]);
				        }else{
				        	$enfantAlreadyTested = true;
				        }

				        if($enfantAlreadyTested == true){
				        	//On revient en arrière en cherchant le parent. Et ainsi de suite si nécessaire...
				        	try {
					            $dbc = SqliteConnection::getInstance()->getConnection();
					            $query = "select relation_id from 'public.attack_pivot_children_relation' where attack_pivot_children_id = :k";
					            $stmt = $dbc->prepare($query);   
					            $stmt->bindValue(':k',$kid['attack_pivot_children_id'],PDO::PARAM_STR);
					            $stmt->execute();
					            $relationID = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
					        } catch(PDOException $e){
					            $e->getMessage();
					        }

					        //var_dump($relationID);
					        $the_parent = array();

					        if($relationID){
						        try {
						            $dbc = SqliteConnection::getInstance()->getConnection();
						            //$query = "select attack_pivot_parent_id from 'public.relation','public.va' where 'public.va'.attack_pivot_id = attack_pivot_parent_id and id = :i";
						            if($poursui = 0){
						            	$query = "select attack_pivot_parent_id from 'public.relation' where id = :i and type = 'REALIZATION'";
						            }else{
						            	$query = "select attack_pivot_parent_id from 'public.relation' where id = :i";
						        	}
						            $stmt = $dbc->prepare($query);   
						            $stmt->bindValue(':i',$relationID['relation_id'],PDO::PARAM_STR);
						            $stmt->execute();
						            $the_parent = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
						        } catch(PDOException $e){
						            $e->getMessage();
						        }
						    }
					        /*if($relationID['relation_id'] == '1'){
					        	echo 'cocou';
					        	var_dump($the_parent);
					        }*/
					        //var_dump($the_parent);
					        if(!empty($the_parent)){
					        	$the_parent = array("attack_pivot_children_id" => $the_parent['attack_pivot_parent_id']);
					        	//var_dump($the_parent);

					        	$poursui++;
					        	fullDeep($the_parent,$result,$allinone,$stop,$poursui);
					        	//echo 'on remonte'.'</br>';
					        }else{
					        	//Alors il n'y a pas plus haut dans l'arbre, nous avons tout parcouru.
					        	$stop = true;
					        	//echo 'FINISH'.'</br>';
					        }
				        }

		        	}
		        	//var_dump($result);
		        	return $result;
		        }

		        $allinone = array();
		        $stop = false;
		        $poursui = 0;
		        //var_dump($parent_prems);
		        if(!empty($parent_prems)){
		        	$parent_prems = $parent_prems[0]["id"];
		        	$parent = array("attack_pivot_children_id" => $parent_prems);
		        	$res = fullDeep($parent,$res,$allinone,$stop,$poursui);
		        	//var_dump($res);
		        }else{
		        	echo '<h1>Nothing is matching with your requirements.</h1>';
		        }
		        
		        

				$res_poli = unique_multidim_array($res,'attack_pivot_children_id');
				$res_final = array();
				foreach($res_poli as $r){
					try {
			            $dbc = SqliteConnection::getInstance()->getConnection();
			            $query = "select name from 'public.attack_pivot' where id = :i";
			            $stmt = $dbc->prepare($query);   
			            $stmt->bindValue(':i',$r['attack_pivot_children_id'],PDO::PARAM_STR);
			            $stmt->execute();
			            $attackpivot = $stmt->fetch(PDO::FETCH_ASSOC);//PDO::FETCH_BOTH par défaut
			        } catch(PDOException $e){
			            $e->getMessage();
			        }
			        //var_dump($attackpivot);
			        array_push($res_final,$attackpivot);
				}
		        
		        //var_dump($res_poli);
		        foreach($res_final as $r){
		        	//var_dump($r);
					echo '<ul>';
					echo '<li><strong>'.$r['name'].'</strong></li>';
					echo '</ul>';
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