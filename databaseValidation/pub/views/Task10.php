<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8"/>
		<title>ABS4SOS</title>
		<link rel="stylesheet" href="./resources/css/css_bootstrap/bootstrap.min.css">
		<link rel="stylesheet" href="./resources/css/css_bootstrap/style_accueil.css">
	</head>

	<script type="text/javascript" src="verif.js"></script>
	<body>
		<div class="container-fluid blue">
			<div class="container title_head center-block">
				<h1 class><strong>Identify attack</strong></h1>
			</div>
		</div>

		<?php
			//HEADER
			require_once('./model/SqliteConnection.php');
			$width = 2141/3.9;
			$height = 500/3.9;
			echo "<div class='container irisa_img'>";
			echo '<img src="./resources/img/irisa_logo.jpg" width='.$width.' height='.$height.'>'."</br>";
			echo "</div>";
			
			//BODY
			echo '<form method="POST" action=./index.php?page=processTask10>
					<div class="container text-center center-block">
						<div class="container">
							<div class="row">
							<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
 

							<p>Choose first a VA</p>
							<select name="va_choices" id="va">
							';

							try{
								$connect = SqliteConnection::getInstance()->getConnection();
								$dbc = SqliteConnection::getInstance()->getConnection();
								$query = "select distinct name from 'public.attack_pivot','public.relation' where attack_pivot_parent_id = 'public.attack_pivot'.id and type = 'REALIZATION'";
								$stmt = $dbc->prepare($query);
								$stmt->execute();
								$elems = $stmt->fetchAll(PDO::FETCH_ASSOC);
								
							}catch(PDOException $e){
								print_r($e->getMessage());
							}

							echo '<option selected value="Select a VA">'.'Select a VA'.'</option>'.'</br>';
							for($i=0;$i<count($elems);$i++){
								echo '<option value='.$elems[$i]['name'].'>'.$elems[$i]['name'].'</option>'.'</br>';
							}
							echo '</select>';

							/*echo '<script type="text/javascript">
							console.log(document.getElementsByName("archi_choices")[0].value);
							if(document.getElementsByName("archi_choices")[0].value === "Select a VA"){
								console.log("Loaded");
								document.getElementsByName("vulne_choices")[0].style.display = "none";
							}else{
								document.getElementsByName("vuln_choices")[0].style.display = "block";
							}
							</script>';*/

						echo '</div>';
						


							echo '
							<div class="container">
							<div class="row">
							<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
							<input class="btn deplace_but color_search" name="processing6" type="submit" value="Search">
						</div>
					</div>
					</form>';

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
		?>	
				
	</body>
</html>
