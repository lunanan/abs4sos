<?php
require_once('Controller.php');
require_once('./model/SqliteConnection.php');
/**
* Cette classe est utilisée pour gèrer le processus de recherche de l'utilisateur
* @author Nicolas BLANCHARD
* @version 1.1
*/
class ProcessController implements Controller{

    /**
     * Gère la requête de l'utilisateur passée en paramètre
     * @param Array $request La requête HTTP
     */
    public function handle($request){
        //On cherche à récupérer les éléments passés lors de la recherche
        $archi = $_POST['archi_choices'];
    	$prop_secu = $_POST['prop_security_choices'];
    	$category = $_POST['category_choices'];
       
        //echo "POST = ";
        //var_dump($_POST);
        //echo "<br/>";
        /*
        echo "Archi = ";
        var_dump($archi);
        echo "<br/>";

        echo "prop_secu = ";
        var_dump($prop_secu);
        echo "<br/>";

        echo "category = ";
        var_dump($category);
        echo "<br/>";*/

        //---------------------
        //TEST OK
        //---------------------

    	//Requête---------
        //On crée tout d'abord un tableau de résultat de la requête.
        //On est censé avoir 3 colonnes, une pour le bien vulnérable, une qui décrit la vulnérabilité et l'autre qui liste les contrôles associés, un tableau à triple dimensions donc.
        
        //ON VA RECUPERER UN TABLEAU POUR CHAQUE REQUETES ET BESOINS (LS : Elements types, VT : Vulnérabilités, CT : Contrôles, SU_refine : Supporting asset)

        //-----------------------
        //echo "<br/>"."<strong>------ Requête 1 : ligne 3 ------</strong>"."<br/>";
        $LS = array();
        try {
            $dbc = SqliteConnection::getInstance()->getConnection();
            $query = "select * from 'public.element_type' where name = :a";
            $stmt = $dbc->prepare($query);
            //On match avec l'architecture précisé par le client.
            $stmt->bindValue(':a',$archi,PDO::PARAM_STR);
            $stmt->execute();
            $LS = $stmt->fetchAll();//PDO::FETCH_BOTH par défaut
        } catch(PDOException $e){
            $e->getMessage();
        }

        //-------------------------
        //   TEST DE LS (Element type).
        /*
        echo "<br/>"."LS de base :"."<br/>";
        var_dump($LS);
        echo "<br/>"."SUCCESS"."<br/>";
        */
        //-------------------------
        //TEST OK
        //-------------------------

        /*
        * ######################
        *		REMAKE
        * ######################
        */
        

        //echo "<br/>"."<strong>------ Requête 2 : ligne 7 - Is-A relation generalisation ------</strong>"."<br/>";
        if(!empty($LS)){
            //Read est initialisé par un element type, (read va lire chacun d'eux et permettre de parcourir les parents)
            $read = $LS[0];
            //poursui est utilisé pour deux raisons, l'une pour rendre le debug plus facile, l'autre, permet de combler l'architecture du tableau(lors du premier tour, on récupère un id dans la premiere dimension, au-delà, on doit parcourrir la deuxième dimension).
            $poursui = 0;
            //variable indicative d'arrêt de recherche de parents.
            $stop = false;

            while($stop == false){
                //On commence par remplacer read par le parent du read courant.
                if($poursui == 0){
                    $read = $LS[$poursui];
                    //echo "La valeur du read".$poursui."est :".$read[$poursui]."<br/>";

                    $res_parent = array();
			        try {
			            $dbc = SqliteConnection::getInstance()->getConnection();
			            $query = "select abstract_element_type_id from 'public.element_type_is_relation' where concrete_element_type_id = :r";
			            $stmt = $dbc->prepare($query);
			            
			            $stmt->bindValue(':r',$read["id"],PDO::PARAM_STR);
			            $stmt->execute();
			            $res_parent = $stmt->fetch();//PDO::FETCH_BOTH par défaut
			        } catch(PDOException $e){
			            $e->getMessage();
			        }
                    /*
                    concrete_elem_type_id == read[id] get le abstract(request)=res
                    Si res != null : parent = res
                    */
			        if(!empty($res_parent)){
                    	$parent = $res_parent;
			        }else{
			        	$parent = null;
			        }

                    //Si poursui indique que nous ne sommes pas au premier tour, alors ...
                }else{
                    $read = $LS[$poursui];
                    //echo "La valeur du read".$poursui."est :".$read[0]["id"]."<br/>";

                    $res_parent = array();
			        try {
			            $dbc = SqliteConnection::getInstance()->getConnection();
			            $query = "select abstract_element_type_id from 'public.element_type_is_relation' where concrete_element_type_id = :r";
			            $stmt = $dbc->prepare($query);
			            //Le changement est ici, on prends $read[0]["id"] et non pas $read['id'].
			            $stmt->bindValue(':r',$read[0]["id"],PDO::PARAM_STR);
			            $stmt->execute();
			            $res_parent = $stmt->fetch();//PDO::FETCH_BOTH par défaut
			        } catch(PDOException $e){
			            $e->getMessage();
			        }

                    if(!empty($res_parent)){
                    	$parent = $res_parent;
			        }else{
			        	$parent = null;
			        }
                }

                $essai = array();
                if(!isset($parent)){
                    if(isset($read[0]["id"])){
                    	$parent = $read[0]["id"];
                    }else{
                    	$parent = $read[0];
                    }
                }

                //--------OUTIL DE DEBUG---------
                //echo "<br/>";
                //var_dump($read);

                /*echo "2 - parent vaut : ";
                var_dump($parent);
                echo "<br/>";*/
                //-------------------------------

                try {
                    $dbc = SqliteConnection::getInstance()->getConnection();
                    $query = "select * from 'public.element_type' where id = :parent";
                    $stmt = $dbc->prepare($query);
                    $stmt->bindValue(':parent',$parent[0],PDO::PARAM_STR);
                    $stmt->execute();
                    //FETCH ALL CHANGE !
                    $essai = $stmt->fetchAll();//PDO::FETCH_BOTH par défaut
                } catch(PDOException $e){
                    $e->getMessage();
                }

                
                $read = $essai;

                //Si son parent != null alors on peut l'ajouter à notre LS.
            
                //On insère donc cet élement en faisant attention aux doublons.
                //Si un doublon est trouvé, alors on met defaut à vrai...
                $defaut = false;
                
                //--------OUTIL DE DEBUG---------
                /*
                $myread = "";
                if(isset($read[0]["parent_id"])){
                    $myread = $read[0]["parent_id"];
                }else{
                    $myread = $read["parent_id"];
                }
                */
                //-------------------------------

                //Recherche des parents récursivement
                $res_parent_recurs = array();
		        try {
		            $dbc = SqliteConnection::getInstance()->getConnection();
		            $query = "select abstract_element_type_id from 'public.element_type_is_relation' where concrete_element_type_id = :r";
		            $stmt = $dbc->prepare($query);
		            
		            $stmt->bindValue(':r',$read[0]["id"],PDO::PARAM_STR);
		            $stmt->execute();
		            $res_parent_recurs = $stmt->fetch();//PDO::FETCH_BOTH par défaut
		        } catch(PDOException $e){
		            $e->getMessage();
		        }

                //Raffinage de LS
                if($res_parent_recurs != "" && $res_parent_recurs && $poursui != 0){
                    for($i=0;$i<count($LS);$i++){
                        
                        //--------OUTIL DE DEBUG---------
                        //var_dump($LS);
                        //echo "<br/>";
                        /*echo "L'index est ".$i."<br/>";
                        var_dump($LS);
                        echo "<br/>";
                        echo "voici LS id : ";
                        var_dump($LS[$i]["id"]);
                        echo "<br/>";
                        echo "voici read : ";
                        var_dump($read[0]["id"]);*/
                        //------------------------------

                        //Verification de l'existance de LS[i]["id"] afin de le traiter
                        //var_dump($LS[$i]);
                        if(isset($LS[$i]["id"])){
                            if($LS[$i]["id"] === $read[0]["id"]){
                                /*echo "<br/>";
                                echo "defaut trouvé !";
                                echo "<br/>";*/
                                $defaut = true;
                            }
                        }
                    }
                    if($defaut == false){
                        /*echo "<br/>";
                        echo "On push !";
                        echo "<br/>";*/
                        //read de 0
                        array_push($LS, $read);
                        
                        $poursui = $poursui+1;
                        $read = $LS[$poursui];

                        //--------OUTIL DE DEBUG---------
                        //echo "<br/>";
                        //echo "####";
                        //echo "<br/>";
                        //var_dump($read);
                        //echo "<br/>";
                        //echo "####";
                        //echo "<br/>";
                        //-------------------------------
                    }
                //var_dump($read);
                }else if($res_parent_recurs == "" && $poursui != 0){
                    //C'est donc le dernier ajout :
                    /*echo "<br/>";
                    echo "C'est le dernier push!";
                    echo "<br/>";*/
                    //read de 0
                    array_push($LS, $read);
                    $stop = true;
                }else if($res_parent_recurs == "" && $poursui == 0){
                    //C'est donc le dernier ajout :
                    /*echo "<br/>";
                    echo "C'est le dernier push!";
                    echo "<br/>";*/
                    //read de 0
                    array_push($LS, $read[0]);
                    $stop = true;
                }else if($res_parent_recurs != "" && $res_parent_recurs && $poursui == 0){
                    for($i=0;$i<count($LS);$i++){
                        //var_dump($LS);
                        //echo "<br/>";
                        /*echo "L'index est ".$i."<br/>";
                        var_dump($LS);
                        echo "<br/>";
                        echo "voici LS id : ";
                        var_dump($LS[$i]["id"]);
                        echo "<br/>";
                        echo "voici read : ";
                        var_dump($read[0]["id"]);*/
                        //var_dump($res_parent_recurs);
                        if($LS[$i]["id"] === $res_parent_recurs){
                            /*echo "<br/>";
                            echo "defaut trouvé !";
                            echo "<br/>";*/
                            $defaut = true;
                        }
                    }
                    if($defaut == false){
                        /*echo "<br/>";
                        echo "On push !";
                        echo "<br/>";*/
                        //read de 0
                        array_push($LS, $read);
                        
                        $poursui = $poursui+1;
                        $read = $LS[$poursui];
                    }
                }
            }
        }
        //-----------------------
        //TEST FINAL DE LS:
        /*echo "<br/>"."Test final de LS"."<br/>";
        var_dump($LS);
        echo "<br/>"."SUCCESS"."<br/>";
        */
        //-----------------------

        //--------
        //TEST OK
        //--------

        //echo "<br/>"."<strong>------ Requête 3 : ligne 12 Has_a_relation ------</strong>"."<br/>";
        //1 - récupère CC.contener dans LS
        
        $CC = array();
        try {
                $dbc = SqliteConnection::getInstance()->getConnection();
                $query = "select * from 'public.element_type_has_relation'";
                $stmt = $dbc->prepare($query);
                $stmt->execute();
                $CC = $stmt->fetchAll();//PDO::FETCH_BOTH par défaut
            } catch(PDOException $e){
                $e->getMessage();
            }
        /*echo "<br/>"."La table CC est : "."<br/>";
        var_dump($CC);
        echo "<br/>";
        echo "--------";
        echo "<br/>";*/

        function componentINcontainer($cc, $component){
            $ret = false;
            foreach($cc as $element){
                if($element["element_type_container_id"] == $component){
                    $ret = true;
                }
            }
            return $ret;
        }

        function returnMyComponentID($container){
            $ret = null;
            try {
                $dbc = SqliteConnection::getInstance()->getConnection();
                $query = "select element_type_component_id from 'public.element_type_has_relation'
                     where element_type_container_id = :c";
                $stmt = $dbc->prepare($query);
                $stmt->bindValue(':c',$container,PDO::PARAM_STR);
                $stmt->execute();
                $ret = $stmt->fetch();//PDO::FETCH_BOTH par défaut
                //var_dump($container);
                //var_dump($ret);
            } catch(PDOException $e){
                $e->getMessage();
            }
            //var_dump($ret);
            return $ret[0];
        }
        
        function returnMyComponentAsElement($componentID){
            $ret = null;
            try {
                $dbc = SqliteConnection::getInstance()->getConnection();
                $query = "select id,name,type_name from 'public.element_type', 'public.element_type_has_relation' where element_type_component_id = id and element_type_component_id = :c group by id,name,type_name";
                $stmt = $dbc->prepare($query);
                $stmt->bindValue(':c',$componentID,PDO::PARAM_STR);
                $stmt->execute();
                $ret = $stmt->fetch();//PDO::FETCH_BOTH par défaut
            } catch(PDOException $e){
                $e->getMessage();
            }
            return $ret;
        }
        
        function doublons_LS($LS,$componentID){
            $ret = false;
            foreach($LS as $ls){
                if(isset($ls[0]['id'])){
                    if($ls[0]['id'] == $componentID){
                        $ret = true;
                    }
                }else{
                    if($ls['id'] == $componentID){
                        $ret = true;
                    }
                }
            }
            return $ret;
        }
       
        $nbTour = 0;
        foreach($LS as $element){
            foreach($CC as $cc_elem){
                //var_dump($cc_elem);
                //var_dump($element);
                if($nbTour == 0){
                    //var_dump($element["id"]);
                    if($cc_elem["element_type_container_id"] == $element["id"]){
                        //Ce qui veut dire qu'il y faut ajouter son composant et vérifier si son composant n'est pas lui aussi un container...
                        $stop = false;
                        //On récupère le composant:
                        $componentID = returnMyComponentID($element["id"]);

                        array_push($LS, returnMyComponentAsElement($componentID));
                        
                        //On boucle sur ce composant si lui aussi en a d'autres :
                        while(!$stop){
                            //1 - Savoir si le composant est dans container
                            if(componentINcontainer($CC,$componentID)){
                                // SI OUI : Ajouter le composant de son composant... et ainsi de suite.
                                //On prend l'id composant du composant précédent.
                                $componentID = returnMyComponentID($componentID);
                                //Et on ajoute ce nouveau composant dans LS...
                                array_push($LS, returnMyComponentAsElement($componentID));
                            }else{
                                // SI NON : stop = true
                                $stop = true;
                            }
                        }
                    }
                }else{
                    //var_dump($element[0]["id"]);
                    //Debug ok jusqu'ici
                    //var_dump($element);
                    $atest = "";
                    if(isset($element[0]["id"])){
                        $atest = $element[0]["id"];
                    }else{
                        $atest = $element["id"];
                    }

                    if($cc_elem["element_type_container_id"] == $atest){
                        //Ce qui veut dire qu'il y faut ajouter son composant et vérifier si son composant n'est pas lui aussi un container...
                        $stop = false;
                        //On récupère le composant:
                        //var_dump($element[0]['id']); //ATTENTION ICI
                        if(isset($element[0]["id"])){
                            $componentID = returnMyComponentID($element[0]["id"]);
                            //var_dump($componentID);
                            //BUG TROUVE ICI !
                            /* Description:
                             * l'element 55 est fils de 4 containers à l'heure actuelle. Par conséquent, on le double 4 fois... Ce qui pollue LS. 
                            */

                            //Découplement des doublons
                            //On parcours LS et on voit si le composant que l'on s'apprête à récupéré est un doublon ou non. (il s'agit ici d'éviter le multi-héritage).
                            if(!doublons_LS($LS,$componentID)){
                                array_push($LS, returnMyComponentAsElement($componentID));
                            }

                        }else{
                            $componentID = returnMyComponentID($element["id"]);
                            array_push($LS, returnMyComponentAsElement($componentID));
                        }
                        
                        //On boucle sur ce composant si lui aussi en a d'autres :
                        while(!$stop){
                            //1 - Savoir si le composant est dans container
                            if(componentINcontainer($CC,$componentID)){
                                // SI OUI : Ajouter le composant de son composant... et ainsi de suite.
                                //On prend l'id composant du composant précédent.
                                $componentID = returnMyComponentID($componentID);
                                //Et on ajoute ce nouveau composant dans LS...
                                array_push($LS, returnMyComponentAsElement($componentID));
                            }else{
                                // SI NON : stop = true
                                $stop = true;
                            }
                        }
                    }
                }
            }
            $nbTour = $nbTour+1;
        }

        //var_dump($LS);
        //-----------------------
        
        //  TEST DU NOUVEAU LS

            //echo "<br/>"."Voici le nouveau LS : "."<br/>";
            //var_dump($LS);

            //echo "<br/>";
            /*
            foreach($LS as $ls){
                echo "<br/>";
                var_dump($ls);
                echo "<br/>";
                if(isset($ls["name"])){
                    echo $ls["name"];
                }else{
                    echo $ls[0]["name"];
                }
                
                
                echo "<br/>";
            }*/
        //-----------------------
        

        //-----------------------
        //echo "<br/>"."<strong>------ Requête 3 : ligne 17 - SA.root <-- c+sp ------</strong>"."<br/>";
        //-----------------------
        $SAroot = array();
        $SA = array();
        //SAroot contient la racine des SA.
        try {
            $dbc = SqliteConnection::getInstance()->getConnection();
            $query = "select * from 'public.attack_pivot', 'public.goal'as goalTable, 'public.root' as mainRoot where goalTable.attack_pivot_id = id and mainRoot.attack_pivot_id = id and category_type = :c and security_property_name = :s and is_root='1'";
            $stmt = $dbc->prepare($query);
            $stmt->bindValue(':c',$category,PDO::PARAM_STR);
            $stmt->bindValue(':s',$prop_secu,PDO::PARAM_STR);
            $stmt->execute();
            $SAroot = $stmt->fetch();//PDO::FETCH_BOTH par défaut
        } catch(PDOException $e){
            $e->getMessage();
        }

        //-------------------------
        function simpleRequest(&$tab,$query){
            try {
                $dbc = SqliteConnection::getInstance()->getConnection();
                $stmt = $dbc->prepare($query);
                $stmt->execute();
                $tab = $stmt->fetchAll();
            } catch(PDOException $e){
                $e->getMessage();
            }
        }
        //--------------------------

        /*echo "<br/>"."Voici le root correspondant à la recherche"."<br/>";
        var_dump($SAroot);
        echo "<br/>";*/


        // 1 - On chope les enfants de la root.
        //Premier tour
        /*echo "<br/>"."SAroot :"."<br/>";
        var_dump($SAroot);
        echo "<br/>";
        */
        //var_dump($SAroot);
        if(is_array($SAroot)){
            $root = $SAroot["id"];
        }
        $relation = NULL;
        $SA = array();

        function recursiveSA($SAroots, &$SAtab){
            $ret = array();
            $relation = NULL;
            //On récupère l'id de relation que le parent découle. 
            //ref 1.
            try {
                $dbc = SqliteConnection::getInstance()->getConnection();
                $query = "select id from 'public.relation' where attack_pivot_parent_id = :r";
                $stmt = $dbc->prepare($query);
                $stmt-> bindvalue(":r",$SAroots["id"],PDO::PARAM_STR);
                $stmt->execute();
                $relation = $stmt->fetchAll();
            } catch(PDOException $e){
                $e->getMessage();
            }
            /*
            echo "<br/>"."Relation :"."<br/>";
        	var_dump($relation);
        	echo "<br/>";
        	*/

            //ref 2
            if(!empty($relation)){

                //Aller dans attack pivot children et ajouter à SA tous les children.
                try {
                    $dbc = SqliteConnection::getInstance()->getConnection();
                    $query = "select * from 'public.attack_pivot', 'public.attack_pivot_children_relation' where attack_pivot_children_id = id and relation_id = :r 
                        and attack_goal = '1'
                        group by id";
                    $stmt = $dbc->prepare($query);
                    $stmt-> bindvalue(":r",$relation[0]["id"],PDO::PARAM_STR);
                    $stmt->execute();
                    $SAroots = $stmt->fetchAll();
                    
                    /*echo "<br/>"."enfants trouvés avant le push :"."<br/>";
                    var_dump($SAroots);
                    echo "<br/>";*/

                    foreach($SAroots as $sa){
                        array_push($SAtab, $sa);
                    }

                    //Recursivité !
                    foreach($SAroots as $sa){
                        //"sa" est un enfant de SAroots, on va donc le faire devenir parent et étudier ses enfants. donc chaque enfant de SAroots deviennent des parents. Ils remplacent ainsi SAroots.
                        //On tournera récursivement sur chacun de ses éléments...
                        recursiveSA($sa,$SAtab);
                    }
                } catch(PDOException $e){
                    $e->getMessage();
                }
            }
        }

        if(is_array($SAroot)){
            recursiveSA($SAroot,$SA);
        }

        /*echo "<br/>"."SA :"."<br/>";
        var_dump($SA);
        echo "<br/>";*/
        //-----------------------
        /* TEST SA

        echo "<br/>"."SA correspondant (fin de test SA) : "."<br/>";
        var_dump($SA);

        echo "<br/>";
        foreach($SA as $sa){
            echo $sa["name"];
            echo "<br/>";
        }*/
        //-----------------------

        //TEST OK !
        //A partir d'ici, nous sommes capable d'en tirer les Supporting assets en parcourant l'attack tree.
        
        //-----------------------
        //echo "<br/>"."<strong>------ Requête 4 : ligne 18 - matching SA & LS ------</strong>"."<br/>";
        //-----------------------

        function stringContained($base, $acomp){
            //Est-ce que $acomp est compris dans la base ?
            //acomp -> ET.name
            //base -> SA.name
            $trouve = false;
            if(strpos($base,$acomp) !== false){
                $trouve = true;
            }
            return $trouve;
        }

        $SU = array();

        /*echo "<br/>"."LS :"."<br/>";
        var_dump($LS);
        echo "<br/>";*/
        foreach($SA as $sa){
            foreach($LS as $ls){
                $lsokay = "";
                $lsET = array();
                if(isset($ls["name"])){
                    $lsokay = $ls["name"];
                }else{
                    $lsokay = $ls[0]["name"];
                }
                /*echo "<br/>";
                var_dump($lsokay);
                echo "<br/>";
                */
                if(stringContained($lsokay,$sa["name"])){
                    //var_dump($lsokay);
                    try {
                        $dbc = SqliteConnection::getInstance()->getConnection();
                        $query = "select * from 'public.element_type' where name= :name";
                        $stmt = $dbc->prepare($query);
                        $stmt->bindValue(':name',$lsokay,PDO::PARAM_STR);
                        $stmt->execute();
                        $lsET = $stmt->fetch();//PDO::FETCH_BOTH par défaut
                    }catch(PDOException $e){
                        $e->getMessage();
                    }
                    array_push($SU,$lsET);
                    /*echo "<br/>";
                    var_dump($SU);
                    echo "<br/>";
                    */
                }
            }
        }

        //------------
        //TEST SU
        
        /*echo "<br/>"."Tests de SU : "."<br/>";
        var_dump($SU);
        //--------
        //TEST OK
        //--------
        echo "<br/>";*/
        
        
        $SU_refine = array();

        //Elimination des doublons dans SU_refine
        function doublons_SU_refined($SU_refined,$name){
            $ret = false;
            foreach($SU_refined as $su){
                if(isset($su[0])){
                    if($su[0] == $name){
                        $ret = true;
                    }
                }
            }
            return $ret;
        }

        //On raffine SU vers SU_refine
        foreach($SU as $su){

            //1 - On renseigne le les vulnérabilités associées pour cet ET.
            $VCT = array();
            $VT = array();
            $VT_tmp = array();
            try {
                $dbc = SqliteConnection::getInstance()->getConnection();
                $query = "select 'public.vulnerability_type'.name from 'public.vulnerability_type', 'public.element_type_has_vulnerability_type' where element_type_id = :idElem and vulnerability_type_id = id";
                $stmt = $dbc->prepare($query);
                $stmt->bindValue(':idElem',$su["id"],PDO::PARAM_STR);
                $stmt->execute();
                $VT_tmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                $e->getMessage();
            }
            foreach($VT_tmp as $tmp){
                array_push($VT,$tmp["name"]);
            }


            //------------
            //TEST VT
            
            /*echo "<br/>"."Vulnérabilités trouvées :"."<br/>";
            var_dump($VT);
            echo "<br/>";*/
            //-----------------

             //2 - On récupère les contrôles associés :


            $CT = array();
            foreach($VT as $vt){
                $CT_tmp = array();
                try {
                    $dbc = SqliteConnection::getInstance()->getConnection();
                    $query = "select 'public.control_type'.name from 'public.control_type', 'public.vulnerability_type_control', 'public.vulnerability_type' where control_type_id = 'public.control_type'.id and vulnerability_type_id = 'public.vulnerability_type'.id and 'public.vulnerability_type'.name = :vulnN";
                    $stmt = $dbc->prepare($query);
                    $stmt->bindValue(':vulnN',$vt,PDO::PARAM_STR);
                    $stmt->execute();
                    $CT_tmp = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }catch(PDOException $e){
                    $e->getMessage();
                }

                //On rempli le tableau $CT.
                foreach($CT_tmp as $tmp){
                    array_push($CT,$tmp["name"]);
                }
                //Ainsi on a un tableau qui lie vulnerabilités et controles.
                $VCT_tmp = array($vt,$CT_tmp);
                array_push($VCT,$VCT_tmp);
            }

            //------------
            //TEST CT
            /*
            echo "<br/>"."Contrôles trouvées :"."<br/>";
            var_dump($CT);
            echo "<br/>";  
            ---------------*/

            // 3 - On a maintenant, le nom de l'ET, les vulnerabilités et les contrôles associés.
            //On va former un tableau temporaire de su_refine.


            if(!doublons_SU_refined($SU_refine,$su["name"])){
                $SU_tmp = array(
                    $su["name"],
                    $VCT);

                array_push($SU_refine, $SU_tmp);
            }

        }

        //var_dump($SU_refine[0][0]);
        

        //Exemple d'utilisation de SU_refine
        /*echo "<br/>";
        echo $SU_refine[1][0];
        echo "<br/>";
        //[1][0] -> Nom de l'ET.
        //[1][1] -> Vulnérabilités.
        //[]
        var_dump($SU_refine[1][1][0][0]);
        echo "<br/>";

        echo "<br/>"."SU_REFINE :"."<br/>";
        var_dump($SU_refine);
        echo "<br/>";
        echo "<br/>";*/

        //On va tester l'utilisation du tableau avant d'afficher :
        /*echo "<br/>"." tests :"."<br/>";
        var_dump($SU_refine[0][1][0][1][0]);
        echo "<br/>";*/
        
        //----------------
        /* TEST SU REFINE
        $x = 0;
        $y = 0;
        $z = 0;
        $t = 0;

        while(isset($SU_refine[$x][0])){
            //On affiche le ET:
            echo "<br/>"."<strong>Element type name :</strong> "."<br/>";
            echo $SU_refine[$x][0];
            echo "<br/>";
            while(isset($SU_refine[$x][1][$z][0])){
                //A partir de la vulnérabilité :
                //On check tous ces controles:
                //On affiche tout d'abord la vulnérabilité:
                echo "<br/>"."<strong>vulnérabilité :</strong> "."<br/>";
                echo $SU_refine[$x][1][$z][0];
                echo "<br/>";

                //Affichons ses controles à LUI !
                echo "<br/>"."<strong>Ses contrôles : </strong>"."<br/>";
                foreach($SU_refine[$x][1][$z][1] as $ct_tmp){
                    echo $ct_tmp["name"];
                    echo "<br/>";
                }
                $z++;
                $t++;
            }
            $x++;
        }
        ----------------------*/
        //echo "<br/>"."<h2>Test Supporting Assets</h2>";
        //var_dump($SU_refine);

        //Affichage
        function displayResults(&$SU_refine){
            echo "<link rel='stylesheet' href='./resources/css/css_bootstrap/bootstrap.min.css'>
        <link rel='stylesheet' href='./resources/css/css_bootstrap/style_accueil.css'>";

            echo "<div class='container-fluid blue'>
                <div class='container title_head'>
                <h1><strong>Results of architecture modeling</strong></h1>
                </div>
                </div>";

            echo "<br/>";
            echo "<style type='text/css'>
            .tftable {font-size:20px;color:black;width:100%;border-width: 1px;border-color: #207cca;border-collapse: collapse;}
            .tftable th {font-size:20px;background-color:#7db9e8;border-width: 1px;padding: 8px;border-style: solid;border-color: #207cca;text-align:center;}
            .tftable tr {background-color:#e6f3ff;}
            .tftable td {font-size:16px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;}
            .tftable tr:hover {background-color:#ffffff;}
            </style>

            <div class='table-responsive'>
            <table class='tftable' border='1'>

            <tr>
            <th scope='col'>VDA</th>
            <th scope='col'>VULNERABILITY</th>
            <th scope='col'>CONTROL</th>
            </tr>";
            $x = 0;
            $y = 0;
            $z = 0;
            $t = 0;

            $total_rows = count($SU_refine);
            //echo "total rows = ".$total_rows;

            while(isset($SU_refine[$x][0])){
                $z = 0;
                //var_dump($SU_refine[$x][0]);
                //On affiche le ET:
                //echo "<br/>"."<strong>Element type name :</strong> "."<br/>";
                $taille = count($SU_refine[$x][1]);
                if($taille < 1){
                    // 1 line minimum
                    $taille = 1;
                }
                echo '<tr><th scope="row" rowspan='.$taille.'>'.$SU_refine[$x][0].'</th>';
                /*
                echo "<br/>"."x = ".$x."<br/>";
                echo "<br/>"."z = ".$z."<br/>";
                */

                if(!empty($SU_refine[$x][1])){
                    //echo "<br/>"."SuppAsset = ".$SU_refine[$x][0]."<br/>";
                    while(isset($SU_refine[$x][1][$z][0])){
                        //A partir de la vulnérabilité :
                        //On check tous ces controles:
                        //On affiche tout d'abord la vulnérabilité:
                        //echo "<br/>"."<strong>vulnérabilité :</strong> "."<br/>";
                        if(!empty($SU_refine[$x][1][$z][0])){
                            echo '<td>'.$SU_refine[$x][1][$z][0].'</td>';
                        }else{
                            echo '<td>'.' X '.'</td>';
                        }
                        //echo '<td>'.$SU_refine[$x][1][$z][0].'</td>';
                        //echo $SU_refine[$x][1][$z][0];
                        //echo "<br/>";

                        //Affichons ses controles à LUI !
                        //echo "<br/>"."<strong>Ses contrôles : </strong>"."<br/>";
                        echo "<td>";
                        foreach($SU_refine[$x][1][$z][1] as $ct_tmp){  
                            echo "&#x2022; ".$ct_tmp["name"]."<br/>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        $z++;
                        $t++;
                    }
                }else{
                    echo '<td>'.' X '.'</td>';
                    echo '<td>'.' X '.'</td>';
                    echo '</tr>';
                }
                //echo "</tr>";
                $x++;
            }
            echo "</tr>";
            echo "</div>";

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
        }

        //RAFINAGE UTILISATEUR PROCHAINE RELEASE 1.2 (actuel 1.1):
        /*
        echo "<form action='./index.php?page=process' method='POST'>";
        if(!empty($SU_refine)){
            echo "<h3 align='center'> Data has been found ! Please refine your research thanks its selection :</h3>";
            echo '<center><table><tr><td><div style="text-align: center">
                <tr><p>Supporting Asset :</p></tr>
            <select name="refine_SU" id="refine_SU">';

            foreach($SU as $su){
                echo '<option value="'.$su["name"].'">'.$su["name"].'</option>';
            }
            
            echo "</select></div></td></tr>";

            echo "<td><input type='submit' value='Refine more'></td>";
            echo "</table></center>";
        */

            //ne laisser que displayResult pour release 1.2
            //Penser a enlever la conditionnelle pour le lancement du raffinage
            if(!empty($SU_refine)){
                displayResults($SU_refine);
            }else{
                echo "<h1 align='center'> There is no results at the moment. </h1>";
                echo "<div style='text-align: center'><img src='./resources/img/no_data_found.jpg' align='center'></div>";
                echo "<h2 align='center'> Don't forget that data would be refined regularly, please try again later.</h2>";
            }
            
        /*
        }else{
            echo "<h1 align='center'> There is no results at the moment. </h1>";
            echo "<div style='text-align: center'><img src='./resources/img/no_data_found.jpg' align='center'></div>";
            echo "<h2 align='center'> Don't forget that data would be refined regularly, please try again later.</h2>";
        }

        echo "</form>";
		*/
    }
}
?>