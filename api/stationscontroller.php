<?php
    require_once('models/station.php');
    //get
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //get one
        if (isset($_GET['id'])) {
            try {
                $c = new Station($_GET['id']);
                echo json_encode(array(
                    'status' => 0,
                    'station' => json_decode($c->toJson())
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
                'stations' => json_decode(Station::getAllToJson())
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