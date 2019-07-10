<?php
    require_once('connection.php');
    require_once('exceptions/recordnotfoundexception.php');

    class Station {

        //attributes
        private $id;
        private $rowNumber;
        private $deskNumber;
        private $ipAddress;
        private $active;

        //getters and setters
        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }
        public function getRowNumber() { return $this->rowNumber; }
        public function setRowNumber($rowNumber) { $this->rowNumber = $rowNumber; }
        public function getDeskNumber() { return $this->deskNumber; }
        public function setDeskNumber($deskNumber) { $this->deskNumber = $deskNumber; }
        public function getIpAddress() { return $this->ipAddress; }
        public function setIpAddress($ipAddress) { $this->ipAddress = $ipAddress; }
        public function getActive() { return $this->active; }
        public function setActive($active) { $this->active = $active; }

        //constructor
        public function __construct() {
            //get arguments
            $arguments = func_get_args();
            //1 argument received : gets data from database
            if (func_num_args() == 1) {
                $connection = MySqlConnection::getConnection(); //get connection
                $query = 'select id, rowNumber, deskNumber, ipAddress, active from stations where id = ?'; //query
                $command = $connection->prepare($query); //prepare statement
                $command->bind_param('i',$arguments[0]); //parameters
                $command->execute(); //execute
                $command->bind_result($id, $rowNumber, $deskNumber, $ipAddress, $active); //bind results
                //record was found
                if ($command->fetch()) {
                    //pass values to the attributes
                    $this->id = $id;
                    $this->rowNumber = $rowNumber;
                    $this->deskNumber = $deskNumber;
                    $this->ipAddress = $ipAddress;
                    $this->active = $active;
                }
                else
                    throw new RecordNotFoundException($arguments[0]);
                mysqli_stmt_close($command); //close command
                $connection->close(); //close connection
            }
            //multiple arguments received : gets data from arguments
            if (func_num_args() == 5) {
                $this->id = $arguments[0];
                $this->rowNumber = $arguments[1];
                $this->deskNumber = $arguments[2];
                $this->ipAddress = $arguments[3];
                $this->active = $arguments[4];
            }
        }

        //instance methods
        
        //represents the object header in JSON format
        public function toJson() {
            return json_encode(array(
                'id' => $this->id,
                'location' => array(
                    'rowNumber' => $this->rowNumber,
                    'deskNumber' => $this->deskNumber
                ),
                'ipAddress' => $this->ipAddress,
                'isActive' => $this->active
            ));
        }

        //class methods
        
        //returns a contact list
        public static function getAll() {
            $list = array(); //create list
            $connection = MySqlConnection::getConnection();//get connection
			$query = 'select id, rowNumber, deskNumber, ipAddress, active from stations order by id';//query
			$command = $connection->prepare($query);//prepare statement
			$command->execute();//execute
            $command->bind_result($id, $rowNumber, $deskNumber, $ipAddress, $active);//bind results
            //fetch data
			while ($command->fetch()) {
				array_push($list, new Station($id, $rowNumber, $deskNumber, $ipAddress, $active));//add contact to list
			}
            return $list; //return list
        }

        //returs a JSON array with all the contacts
        public static function getAllToJson() {
            $jsonArray = array(); //create JSON array
            //read items
            foreach(self::getAll() as $item) {
                array_push($jsonArray, json_decode($item->toJson()));
            }
            return json_encode($jsonArray); //return JSON array
        }
    }
?>