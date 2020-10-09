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
				<h1 class><strong>Documentation & help</strong></h1>
			</div>
		</div>

		<?php
			//HEADER
			require_once('./model/SqliteConnection.php');
			$width = 2141/3.9;
			$height = 500/3.9;
			echo "<div class='container irisa_img'>";
			echo '<img src="./resources/img/irisa_logo.jpg" width='.$width.' height='.$height.'>'."</br>";
			$width = 966/2;
			$height = 720/2;
			echo '<img src="./resources/img/definitions.jpg" width='.$width.' height='.$height.'>';
			echo "</div>";
			
			//BODY
			echo '<div class="container center-block">
						<div class="container">
							<div class="row">
							<div class="col-xs-12 col-sm-12 col-4-md col-4-lg">';

							
							echo '<ul>';
							echo '<h2>'.'</br>Task 5 :'.'</strong></h2>';
								echo '</br>';
								echo 'This task matches an architecture element with an element type';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>The selected architecture element.</li>';
									echo '<li>A  repository  of  plug-gable  and  reusable  element  types,  hierar-chized with abstraction and composition (and dependency) rela-tions, represented in a B-tree form.  The roots of each tree maybe selected by their intention (goal and purpose), then each treecan be refined by more concrete element types, and each elementtypes can be decomposed by their components.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'The element type that is the most similar (syntacticallyand semantically) to the selected architecture element.';
								echo '</br><strong>Example : </strong>';
									echo 'The architecture element “Android” is syntactically similar tothe element type “Android” in the element type database.';


								echo '<h2>'.'</br>Task 6 :'.'</strong></h2>';
								echo '</br>';
								echo 'Generalize element type (IS relation).';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>The matched element type in the step 5 as a child node, togetherwith related parent nodes in the element type B-tree.</li>';
									echo '<li>CPE naming scheme dictionary (used only for concrete elementtypes). If the concrete element type is: cpe:2.3:a:google:androidapi:19.0:∗:∗:∗:∗:∗:∗:∗(part, vendor, product, version, update, edition, language, soft-ware edition, target software, target hardware, other), then theabstract element type can be: cpe:2.3:a:∗:androidapi:∗:∗:∗:∗:∗:∗:∗:∗(keep only the part and product name).</li>';
									echo '<li>The new-added element types (which are keys) in the “SelectedElement Type” list, resulted from the step 7.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'The types/generalization/parent of the matched elementtype.';
								echo '</br><strong>Example : </strong>';
									echo 'For the matched element type “Android”, its type is a “Mobile”.';



								echo '<h2>'.'</br>Task 7 :'.'</strong></h2>';
								echo '</br>';
								echo 'Define element type (HAS... (structural, sequential and behavioural composition))';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>Element types selected in the step 6, which are stored temporallyin the “Selected Element Type” list.</li>';
									echo '<li>Element type B-tree.  The keys nodes related with each elementin the “Selected Element Type” list.</li>';
									echo '<li>The new-added keys in the “Selected Element Type” list.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'New element type discovered and they are added in the“Selected Element Type” list.';
								echo '</br><strong>Example : </strong>';
									echo 'The  “Selected  Element  Type”  contains  actually  “Android”and  “Mobile”.    For  the  “Android”  child  node,  it  contains/has  a“Broadcast Receiver” key.  “Broadcast Receiver” has “Intent”.';




								echo '<h2>'.'</br>Task 8 :'.'</strong></h2>';
								echo '</br>';
								echo 'Develop Vulnerability Assets';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>Selected security property.</li>';
									echo '<li>Selected category</li>';
									echo '<li>VA B-Tree extracted from CAPEC</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'Selected VA sub-B-Tree.';
								echo '</br><strong>Example : </strong>';
									echo 'For  “data  confidentiality”,  a  sub-B-Tree  that  contains  VA,whose security breach impact on the data confidentiality.  One of thenodes  on  the  sub-VA-b-Tree  is  “Interceptable  Intent”,  whose  compromise can impact on “data confidentiality”.';




								echo '<h2>'.'</br>Task 9 :'.'</strong></h2>';
								echo '</br>';
								echo 'Develop Tactics.';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>CAPEC VA B-Tree.  More precisely, the information of tacticsrelated with VA nodes.</li>';
									echo '<li>The tactics of the selected VA to restrain the analysis surface</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'Tactics for the selected VAs.';
								echo '</br><strong>Example : </strong>';
									echo 'For  the  VA  “Interceptable  Intent”,  the  attack  tactic  can  be“Intent Intercept”, which can be refined into “Activity Hijack”.';
							



								echo '<h2>'.'</br>Task 10 :'.'</strong></h2>';
								echo '</br>';
								echo 'Identify attack : An attack is a combination of a VA and an attacktactic.';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>Selected VA.</li>';
									echo '<li>Selected Tactic</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'If the VA can be compromise, so display the possible attack.';
								echo '</br><strong>Example : </strong>';
									echo 'For the VA “Interceptable Intent” and the attack tactic “In-tent Intercept”, the attack is “Interceptable intent intercept”.';



							echo '<h2>'.'</br>Task 11 :'.'</strong></h2>';
								echo '</br>';
								echo 'Match VA with element type.';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>Each element type in the “Selected Element Type” list.</li>';
									echo '<li>Each VA in the “Selected VA” list.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'None.';
								echo '</br><strong>Example : </strong>';
									echo 'Similarity comparison between each element type in the “Se-lected  Element  Type”  list{“Android”,  “Mobile”,  “Broadcast  Re-ceiver”, “Intent”, etc}, and each VA in the “Selected VA” list{“InterceptableIntent”, etc}';



								echo '<h2>'.'</br>Task 12 :'.'</strong></h2>';
								echo '</br>';
								echo 'Highlight as VDA.';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>The result of the matching.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'VDAs which are the matched element type and VA.';
								echo '</br><strong>Example : </strong>';
									echo '"Interceptable Intent”';




									echo '<h2>'.'</br>Task 13 :'.'</strong></h2>';
								echo '</br>';
								echo 'Identify vulnerability types.';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>The vulnerability information retreived from CWE database.</li>';
									echo '<li>VDAs.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'The vulnerability types for all VDAs.';
								echo '</br><strong>Example : </strong>';
									echo 'For  the  VDA  “Interceptable  Intent”,  the  vulnerability  typecan be “Improper Verification of Intent by Broadcast Receiver”.';



									echo '<h2>'.'</br>Task 14 :'.'</strong></h2>';
								echo '</br>';
								echo 'Recommend security control.';
								echo '</br><strong>Input Data : </strong>';
								echo '<ul>';
									echo '<li>CAPEC mitigation information</li>';
									echo '<li>CWE mitigation information</li>';
									echo '<li>Vulnerability types obtained in the step 13.</li>';
								echo '</ul>';
								echo '<strong>Output Data : </strong>';
									echo 'Control types that are recommended to the architect';
								echo '</br><strong>Example : </strong>';
									echo 'CAPEC mitigation for the VA “Interceptable Intent” and thetactic “Intent Intercept” is “Explicit intents should be used wheneversensitive data is being sent”.  CWE mitigation for the vulnerabilitytype “Improper Verification of Intent by Broadcast Receiver” is “Be-fore  acting  on  the  Intent,  check  the  Intent  Action  to  make  sure  itmatches the expected System action”';


							echo '</ul>';
							echo '</br></br></br></br>';
						echo '</div>
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
