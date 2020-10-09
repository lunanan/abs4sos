<?php
require_once('/home/m3104/m3104_40/public_html/model/CalculDistance.php');

class CalculDistanceImpl implements CalculDistance{

	/**
     * Retourne la distance en mètres entre 2 points GPS exprimés en degrés.
     * @param float $lat1 Latitude du premier point GPS
     * @param float $long1 Longitude du premier point GPS
     * @param float $lat2 Latitude du second point GPS
     * @param float $long2 Longitude du second point GPS
     * @return float $distance La distance entre les deux points GPS
     */
    public function calculDistance2PointsGPS($lat1, $long1, $lat2, $long2){
   		//radius vaut 6378.137 km.
        $radius = 6378.137;
        
        $delta_Rad_Lat = deg2rad($lat2 - $lat1);  //Latitude delta en radians
        $delta_Rad_Lon = deg2rad($long2 - $long1);  //Longitude delta en radians
        $rad_Lat1 = deg2rad($lat1);  //Latitude 1 en radians
        $rad_Lat2 = deg2rad($lat2);  //Latitude 2 en radians

        $sq_Half_Chord = sin($delta_Rad_Lat / 2) * sin($delta_Rad_Lat / 2) + cos($rad_Lat1) * cos($rad_Lat2) * sin($delta_Rad_Lon / 2) * sin($delta_Rad_Lon / 2);  //Square of half the chord length
        $ang_Dist_Rad = 2 * asin(sqrt($sq_Half_Chord));  //Distance angulaire en radians
        $distance = round($radius * $ang_Dist_Rad , 2); 

        //A NOTER : le facteur 2, est défini du fait que arcos est défini entre -1 et 1, or les delta provoquent des cos et sin compris entre -2 et 2.
        return $distance; 
    }

    /**
     * Retourne la distance en metres du parcours passé en paramètres. Le parcours est
     * défini par un tableau ordonné de points GPS.
     * @param Array $parcours Le tableau contenant les points GPS
     * @return float $ret La distance du parcours
     */
    public function calculDistanceTrajet(Array $parcours,bool $depuisJson){
    	$ret = 0; //distance totale trajet
        if($depuisJson){
           for($i = 1; $i < count($parcours); $i++){
                $lat1 = $parcours[$i-1][0];
                $long1 = $parcours[$i-1][1];

                $lat2 = $parcours[$i][0];
                $long2 = $parcours[$i][1];

                $ret += $this->calculDistance2PointsGPS($lat1,$long1,$lat2,$long2);
            }
        } else{
            for($i = 1; $i < count($parcours); $i++){
                $lat1 = $parcours[$i-1]['latitude'];
                $long1 = $parcours[$i-1]['longitude'];

                $lat2 = $parcours[$i]['latitude'];
                $long2 = $parcours[$i]['longitude'];

                $ret += $this->calculDistance2PointsGPS($lat1,$long1,$lat2,$long2);
            }
        }
        return $ret;
    }

    /**
     * Retourne un tableau de parcours ordonné de points GPS à partir
     * d'un fichier JSON en récupérant la latitude et la longitude de chaque ligne.
     * @param String l'url qui pointe sur le fichier JSON.
     * @return Array $parcours le tableau parcours.
     */
    public function recupJsonLatLong(String $url){
    	try{
            $json_source = file_get_contents($url);
			//json_data devient un tableau associatif avec toutes les valeurs GPS.
            $json_data = json_decode($json_source, true);
    		//Or ce qui nous intéresse c'est la latitude et la longitude uniquement.
    		//On va donc récupérer seulement ce qui nous intéresse dans un tableau parcours.
            $parcours = NULL;
            for($i = 0 ; $i<count($json_data['data']); $i++){
                $lat = $json_data['data'][$i]['latitude'];
                $long = $json_data['data'][$i]['longitude'];

                $parcours[$i][0] = $lat;
                $parcours[$i][1] = $long;
            }
        } catch(Exception $e){
            echo 'Exception reçue : ', $e->getMessage(), "\n";
        }
      return $parcours;
    }
}
?>