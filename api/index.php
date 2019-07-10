<?php
    //headers
    header('Access-Control-Allow-Origin: *');
    
    //get request URI
    $requestUri = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF']))); 
    //split uri parts
    $uriParts = explode('/', $requestUri);
    //correct URI
    if (sizeof($uriParts) == 3 || sizeof($uriParts) == 4) {
        //get URI info
        $controller = $uriParts[1];
        //special action
        if (sizeof($uriParts) == 4) $action = $uriParts[2]; else $action = '';
        $parameters = $uriParts[sizeof($uriParts) - 1]; 
        //send to controllers
        switch ($controller) {
            case strtolower('calls') : require_once('callscontroller.php'); break;
            case strtolower('callstatus') : require_once('callstatuscontroller.php'); break;
            case strtolower('callendstatus') : require_once('callendstatuscontroller.php'); break;
            case strtolower('agents') : require_once('agentscontroller.php'); break;
            case strtolower('stations') : require_once('stationscontroller.php'); break;
            default: 
                echo json_encode(array(
                    'status' => 998, 
                    'errorMessage' => 'Invalid Controller'
                ));
        }
    }
    else
        echo json_encode(array(
            'status' => 999, 
            'errorMessage' => 'Invalid URI'
        ));
?>