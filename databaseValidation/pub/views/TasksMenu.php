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
				<h1 class><strong>Select a security task of the security assistance process!</strong></h1>
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
			echo '
					<div class="container text-center center-block">

						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask5">Task5</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask6">Task6</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask7">Task7</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask8">Task8</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask9">Task9</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask10">Task10</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask13">Task 11, 12 ,13</a></button>
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=viewTask14">Task14</a></button>


					</div>';
				echo '<div class="container text-center center-block">
						<button class="btn deplace_but color_search" type="button"><a class="text-white" href="./index.php?page=helpView">HELP</a></button>

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
		?>	
				
	</body>
</html>
