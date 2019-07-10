<?php
    require_once('models/call.php');
    //get
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //get one
        if (isset($_GET['id'])) {
            try {
                $c = new Call($_GET['id']);
                echo json_encode(array(
                    'status' => 0,
                    'call' => json_decode($c->toJson())
                ));
            }
            catch(RecordNotFoundException $ex) {
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->getMessage()
                ));
            }
        }
        //get all
        else
        {
            echo json_encode(array(
                'status' => 0,
                'calls' => json_decode(Call::getAllToJson())
            ));
        }
    }
    //post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          
        if (isset($_POST['action']) ) {
            $action = $_POST['action'];
            //receive call
            if ($action == 'receive') {
                if (isset($_POST['phoneNumber']) ) {
                   if(Call::receive($_POST['phoneNumber'])){
                        echo json_encode(array(
                            'status' => 0,
                            'message' => 'Call received successfully'
                        )); 
                   }
                   else{
                        echo json_encode(array(
                            'status' => 2,
                            'errorMessage' => 'Could not receive call'
                        )); 
                   }
                }
                else{
                    echo json_encode(array(
                        'status' => 2,
                        'errorMessage' => 'Missing parameters'
                    )); 
               }
            }
            //end call
            else if ($action == 'end') {
                if (isset($_POST['callId']) && isset($_POST['endStatusId'])) {
                    
                    try {
                        $c = new Call($_POST['callId']);
                        if(Call::end($_POST['callId'], $_POST['endStatusId'])){
                            echo json_encode(array(
                                 'status' => 0,
                                 'message' => 'Call ended successfully'
                             )); 
                        }
                        else{
                             echo json_encode(array(
                                 'status' => 2,
                                 'errorMessage' => 'Could not end call'
                             )); 
                        }
                    }
                    catch(RecordNotFoundException $ex) {
                        echo json_encode(array(
                            'status' => 1,
                            'errorMessage' => $ex->getMessage()
                        ));
                    }  
                 }
                 else{
                    echo json_encode(array(
                        'status' => 2,
                        'errorMessage' => 'Missing parameters'
                    )); 
               }
            }
            else{
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Invalid action'
                )); 
           }
        }
    }
    //put
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    }
    //delete
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    }
?>