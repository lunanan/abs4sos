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
				<h1 class><strong>Select a task of ICSA's processes !</strong></h1>
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

						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task5" value="Task 5">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task6" value="Task 6">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task7" value="Task 7">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task8" value="Task 8">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task9" value="Task 9">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task10" value="Task 10">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task11" value="Task 11">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task12" value="Task 12">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task13" value="Task 13">
						<input class="btn deplace_but color_search" name="processing_tasks" type="submit" formaction="./index.php?page=task14" value="Task 14">

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
