<?php

/**
 * Cette classe reçoit les ordres du contrôleur de façade, ainsi, il va analyser le paramètre CGI et rediriger en suivant les routes lui sont indiquées.
 * @author Nicolas BLANCHARD
 */
class ApplicationController{
    private static $instance;
    private $routes;
    
    private function __construct(){
        // Sets the controllers and the views of the application.
        $this->routes = [
            '/' => ['controller'=>'MainController', 'view'=>'MainView'],
            'accueil' => ['controller'=>null, 'view'=>'Accueil'],
            'error' => ['controller'=>null, 'view'=>'ErrorView'],
            'errorSub' => ['controller'=>null, 'view'=>'ErrorViewSub'],
            'process' => ['controller'=>'ProcessController', 'view'=>null],
            'processTask' => ['controller'=>null, 'view'=>'TasksMenu'],
            'processTask5' => ['controller'=>'Task5Controller', 'view'=>null],
            'processTask6' => ['controller'=>'Task6Controller', 'view'=>null],
            'processTask7' => ['controller'=>'Task7Controller', 'view'=>null],
            'processTask8' => ['controller'=>'Task8Controller', 'view'=>null],
            'processTask9' => ['controller'=>'Task9Controller', 'view'=>null],
            'processTask10' => ['controller'=>'Task10Controller', 'view'=>null],
            'processTask11' => ['controller'=>'Task11Controller', 'view'=>null],
            'processTask12' => ['controller'=>'Task12Controller', 'view'=>null],
            'processTask13' => ['controller'=>'Task13Controller', 'view'=>null],
            'processTask10_1' => ['controller'=>'Task10Controller_1', 'view'=>null],
            'processTask14' => ['controller'=>'Task14Controller', 'view'=>null],
            'viewTask5' => ['controller'=>null, 'view'=>'Task5'],
            'viewTask6' => ['controller'=>null, 'view'=>'Task6'],
            'viewTask7' => ['controller'=>null, 'view'=>'Task7'],
            'viewTask8' => ['controller'=>null, 'view'=>'Task8'],
            'viewTask9' => ['controller'=>null, 'view'=>'Task9'],
            'viewTask10' => ['controller'=>null, 'view'=>'Task10'],
            'viewTask11' => ['controller'=>null, 'view'=>'Task11'],
            'viewTask12' => ['controller'=>null, 'view'=>'Task12'],
            'viewTask13' => ['controller'=>null, 'view'=>'Task13'],
            'helpView' => ['controller'=>null, 'view'=>'Help'],
            'viewTask14' => ['controller'=>null, 'view'=>'Task14']
        ];
    }

    /**
     * Returns the single instance of this class.
     * @return ApplicationController the single instance of this class.
     */
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new ApplicationController;
        }
        return self::$instance;
    }

    /**
     * Returns the controller that is responsible for processing the request
     * specified as parameter. The controller can be null if their is no data to
     * process.
     * @param Array $request The HTTP request.
     * @param Controller The controller that is responsible for processing the
     * request specified as parameter.  
     */
    public function getController($request){
        //Si le paramètre de requête de page est valide dans le tableau routes, alors il retourne cette page via le tableau associatif.
        if(array_key_exists($request['page'], $this->routes)){
            
            return $this->routes[$request['page']]['controller']; 

        }else{
            return null;
        }
    }

    /**
     * Returns the view that must be return in response of the HTTP request
     * specified as parameter.  
     * @param Array $request The HTTP request.
     * @param Object The view that must be return in response of the HTTP request
     * specified as parameter. 
     */
    public function getView($request){
        //Si le paramètre de requête de page est valide dans le tableau routes, alors il retourne cette page via le tableau associatif.
        if( array_key_exists($request['page'], $this->routes)){
            return $this->routes[$request['page']]['view'];
        }else{
            return $this->routes['error']['view'];
        }
    }
}
?>
