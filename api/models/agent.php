<?php
    require_once('connection.php');
    require_once('exceptions/recordnotfoundexception.php');
    require_once(__DIR__.'/../config/config.php');

    class Agent {

        //attributes
        private $id;
        private $name;
        private $photo;
        private $pin;

        //getters and setters
        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }
        public function getName() { return $this->name; }
        public function setName($name) { $this->name = $name; }
        public function getPhoto() { return $this->photo; }
        public function setPhoto($photo) { $this->photo = $photo; }
        public function setPin($pin) { $this->pin = $pin; }

        //constructor
        public function __construct() {
            //get arguments
            $arguments = func_get_args();
            //1 argument received : gets data from database
            if (func_num_args() == 1) {
                $connection = MySqlConnection::getConnection(); //get connection
                $query = 'select id, name, photo from agents where id = ?'; //query
                $command = $connection->prepare($query); //prepare statement
                $command->bind_param('i',$arguments[0]); //parameters
                $command->execute(); //execute
                $command->bind_result($id, $name, $photo); //bind results
                //record was found
                if ($command->fetch()) {
                    //pass values to the attributes
                    $this->id = $id;
                    $this->name = $name;
                    $this->photo = $photo;
                }
                else
                    throw new RecordNotFoundException($arguments[0]);
                mysqli_stmt_close($command); //close command
                $connection->close(); //close connection
            }
            //multiple arguments received : gets data from arguments
            if (func_num_args() == 3) {
                $this->id = $arguments[0];
                $this->name = $arguments[1];
                $this->photo = $arguments[2];
            }
        }

        //instance methods
        
        //represents the object header in JSON format
        public function toJson() {
            return json_encode(array(
                'id' => $this->id,
                'name' => $this->name,
                'photo' => Config::getFileUrl('agents').$this->photo
            ));
        }

        //class methods
        
        //returns a contact list
        public static function getAll() {
            $list = array(); //create list
            $connection = MySqlConnection::getConnection();//get connection
			$query = 'select id, name, photo from agents order by id';//query
			$command = $connection->prepare($query);//prepare statement
			$command->execute();//execute
            $command->bind_result($id, $name, $photo);//bind results
            //fetch data
			while ($command->fetch()) {
				array_push($list, new Agent($id, $name, $photo));//add contact to list
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
            return $jsonArray; //return JSON array
        }
    }
?>