<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8"/>
		<title>ABS4SOS</title>
		<link rel="stylesheet" href="./resources/css/css_bootstrap/bootstrap.min.css">
		<link rel="stylesheet" href="./resources/css/css_bootstrap/style_accueil.css">
	</head>

	<body>
		<div class="container-fluid blue">
			<div class="container title_head center-block">
				<h1 class><strong>Develop VA</strong></h1>
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
			echo '<form method="POST" action=./index.php?page=processTask8>
					<div class="container text-center center-block">
					<div class="container">
						<div class="row">
						<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">';

						try{
							$connect = SqliteConnection::getInstance()->getConnection();
							$dbc = SqliteConnection::getInstance()->getConnection();
							
							$query = "select type from 'public.category'";
							$stmt = $dbc->prepare($query);
							$stmt->execute();
							$categ = $stmt->fetchAll();

							$query = "select name from 'public.security_property'";
							$stmt = $dbc->prepare($query);
							$stmt->execute();
							$prop_secu = $stmt->fetchAll();
						}catch(PDOException $e){
							print_r($e->getMessage());
						}

					echo '</div>
						
					<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
					<p>Security property</p>
					<select name="prop_security_choices" id="prop_security">';
						//Boucle qui récupère chaques propriétés d'architecture et fait un ajout d'option dans la liste.
						//$prop_secu , tableau de valeurs --> METHODE QUI RETOURNE UN TABLEAU DE TOUS LES NOMS DE PROP_SECU.
						for($p=0;$p<count($prop_secu);$p++){
							echo '<option value='.$prop_secu[$p]['name'].'>'.$prop_secu[$p]['name'].'</option>'.'</br>';
						}
						echo '</select></div>
					<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">
					<p>Category</p>
					<select name="category_choices" id="category">';
						//Boucle qui récupère chaques catégories de sécurité et fait un ajout d'option dans la liste.
						//$categ , tableau de valeurs --> METHODE QUI RETOURNE UN TABLEAU DE TOUS LES NOMS DE CATEGORIE.
						for($c=0;$c<count($categ);$c++){
							echo '<option value='.$categ[$c]['type'].'>'.$categ[$c]['type'].'</option>'.'</br>';
						}
						echo '</select>
						</div>
						</div>
						</div>
						</div>
						<div class="container text-center center-block">
							<input class="btn deplace_but color_search" name="processing" type="submit" value="Search">
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
