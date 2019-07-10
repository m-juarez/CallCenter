<?php
    require_once('models/callendstatus.php');
    //get
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //get one
        if (isset($_GET['id'])) {
            try {
                $c = new CallEndStatus($_GET['id']);
                echo json_encode(array(
                    'status' => 0,
                    'callEndStatus' => json_decode($c->toJson())
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
                'callEndStatus' => json_decode(CallEndStatus::getAllToJson())
            ));     
        }
    }
    //post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //check is post data is complete
        if (isset($_POST['id']) && isset($_POST['description']) ) {
            //create empty object
            $c = new CallEndStatus();
            //set attributes
            $c->setId($_POST['id']);
            $c->setDescription($_POST['description']);
            //add
            if ($c->add()) {
                echo json_encode(array(
                    'status' => 0,
                    'message' => 'Record added successfully'
                )); 
            }
            else {
                echo json_encode(array(
                    'status' => 2,
                    'errorMessage' => 'Could not add record'
                )); 
            }
        }
        else {
            echo json_encode(array(
                'status' => 1,
                'errorMessage' => 'Missing POST data'
            )); 
        }
    }
    //put
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    }
    //delete
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    }
?>