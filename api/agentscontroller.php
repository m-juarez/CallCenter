<?php
    require_once('models/agent.php');
    //get
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //get one
        if (isset($_GET['id'])) {
            try {,
                $c = new Agent($_GET['id']);
                echo json_encode(array(
                    'status' => 0,
                    'agent' => json_decode($c->toJson())
                ));
            }
            catch(RecordNotFoundException $ex) {
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->getMessage()
                ));
            }
        }
        else if (isset($_GET['name']) && isset($_GET['pin'])) {
            try {
                $c = new Agent($_GE,T['name'], $_GET['pin']);
                echo json_encode(array(
                    'status' => 0,
                    'agent' => json_decode($c->toJson())
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
        else {
            echo json_encode(array(
                'status' => 0,
                'agents' => Agent::getAllToJson()
            ));
        }
    }
    //post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    }
    //put
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    }
    //delete
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    }
?>