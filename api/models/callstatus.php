<?php
    //require external files
    require_once('connection.php');
    require_once('exceptions/recordnotfoundexception.php');
    
    class CallStatus {

        //attributes
        private $id;
        private $description;
        private $availableToAnswer;

        //getters and setters
        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }
        public function getDescription() { return $this->description; }
        public function setDescription($description) { $this->description = $description; }
        public function isAvailableToAnswer() { return $this->availableToAnswer; }
        public function setIsAvailableToAnswer($availableToAnswer) { $this->availableToAnswer = $availableToAnswer; }

        //constructor
        public function __construct() {
            //0 arguments received : creates an empty object
            if (func_num_args()) {
                $this->id = 0;
                $this->description = '';
                $this->availableToAnswer = true;
            }
            //1 argument received : gets data from database
            if (func_num_args() == 1) {
                $connection = MySqlConnection::getConnection(); //get connection
                $query = 'select id, description, availableToAnswer from statusCall where id = ?'; //query
                $command = $connection->prepare($query); //prepare statement
                $command->bind_param('i', func_get_arg(0)); //parameters
                $command->execute(); //execute
                $command->bind_result($id, $description, $availableToAnswer); //bind results
                //record was found
                if ($command->fetch()) {
                    //pass values to the attributes
                    $this->id = $id;
                    $this->description = $description;
                    $this->availableToAnswer = $availableToAnswer;
                }
                else
                    throw new RecordNotFoundException(func_get_arg(0));
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //multiple arguments received : gets data from arguments
            if (func_num_args() == 3) {
                $this->id = func_get_arg(0);
                $this->description = func_get_arg(1);
                $this->availableToAnswer = func_get_arg(2);
            }
        }

        //instance methods
        
        //represents the object header in JSON format
        public function toJson() {
            return json_encode(array(
                'id' => $this->id,
                'description' => $this->description,
                'isAvailableToAnswer' => (bool)$this->availableToAnswer
            ));
        }

        //add
        public function add() {
            $connection = MySqlConnection::getConnection(); //get connection
            $query = 'insert into statusCall (id, description, availableToAnswer) values (?, ?, ?)'; //query
            $command = $connection->prepare($query); //prepare statement
            //parameters
            $command->bind_param('isi', 
                $this->id, 
                $this->description,
                $this->availableToAnswer
            ); 
            $result = $command->execute(); //execute
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            return $result;
        }

        //class methods
        
        //returns a contact list
        public static function getAll() {
            $list = array(); //create list
            //get connection
			$connection = MySqlConnection::getConnection();
			//query
			$query = 'select id, description, availableToAnswer from statusCall';
			//prepare statement
			$command = $connection->prepare($query);
			//execute
			$command->execute();
			//bind results
			$command->bind_result($id, $description, $availableToAnswer);
			//fetch data
			while ($command->fetch()) {
				//add contact to list
				array_push($list, new CallStatus($id, $description, $availableToAnswer));
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