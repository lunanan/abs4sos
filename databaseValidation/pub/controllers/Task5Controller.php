<?php
require_once('Controller.php');
require_once('./model/SqliteConnection.php');
/**
* Cette classe est utilisée pour gèrer le processus de recherche de l'utilisateur
* @author Nicolas BLANCHARD
* @version 1.1
*/
class Task5Controller implements Controller{

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
					<h1 class><strong>Results of the task 5</strong></h1>
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
		$archi = $_POST['archi_choices'];
		echo '
				<div class="container text-center center-block">
					<div class="container">
						<div class="row">
						<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
						<h2>Element types which matches with the architecture '.$archi.'  :</h2>
						';

						try{
							$connect = SqliteConnection::getInstance()->getConnection();
							$dbc = SqliteConnection::getInstance()->getConnection();
							$query = "select name from 'public.element_type'";
							$stmt = $dbc->prepare($query);
							$stmt->execute();
							$element_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
						}catch(PDOException $e){
							print_r($e->getMessage());
						}

						//Compare strings and creation of the results list
						$results = array();
						foreach($element_types as $et){

							//var_dump($et);
							if(stringContained($et['name'],$archi)){
								//Doit être container
								try{
									$connect = SqliteConnection::getInstance()->getConnection();
									$dbc = SqliteConnection::getInstance()->getConnection();
									$query = "select element_type_container_id from 'public.element_type', 'public.element_type_has_relation' where id = element_type_container_id and name = :n";
									$stmt = $dbc->prepare($query);
									$stmt->bindValue(':n',$archi,PDO::PARAM_STR);
									$stmt->execute();
									$element_types = $stmt->fetch(PDO::FETCH_ASSOC);
								}catch(PDOException $e){
									print_r($e->getMessage());
								}

								try{
									$connect = SqliteConnection::getInstance()->getConnection();
									$dbc = SqliteConnection::getInstance()->getConnection();
									$query = "select element_type_component_id from 'public.element_type', 'public.element_type_has_relation' where id = element_type_component_id and name = :n";
									$stmt = $dbc->prepare($query);
									$stmt->bindValue(':n',$archi,PDO::PARAM_STR);
									$stmt->execute();
									$component = $stmt->fetch(PDO::FETCH_ASSOC);
								}catch(PDOException $e){
									print_r($e->getMessage());
								}
								
								if(!empty($element_types) && empty($component)){
									array_push($results,$et['name']);
								}

								
							}
						}

						//Display results
						foreach($results as $r){
							echo '<ul>';
							echo '<li><strong>'.$r.'</strong></li>';
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