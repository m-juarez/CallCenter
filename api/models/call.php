<?php
    require_once('connection.php');
    require_once('callstatus.php');
    require_once('callendstatus.php');
    require_once('exceptions/recordnotfoundexception.php');
    
    class Call {

        //attributes
        private $id;
        private $dateTimeReceived;
        private $dateTimeAnswered;
        private $dateTimeEnded;
        private $phoneNumber;
        private $session;
        private $status;
        private $statusEnd;
        private $handleTime;
        private $waitTime;

        //getters and setters
        public function getId() { return $this->id; }
        public function getDateTimeReceived() { return $this->dateTimeReceived; }
        public function getDateTimeAnswered() { return $this->dateTimeAnswered; }
        public function getDateTimeEnded() { return $this->dateTimeEnded; }
        public function getPhoneNumber() { return $this->phoneNumber; }
        public function getSession() { return $this->session; }
        public function getStatus() { return $this->status; }
        public function getStatusEnd() { return $this->statusEnd; }
        public function getHandleTime() { return $this->handleTime; }
        public function getWaitTime() { return $this->waitTime; }

        //constructor
        public function __construct() {
            //get arguments
            $arguments = func_get_args();
            //1 argument received : gets data from database
            if (func_num_args() == 1) {
                $connection = MySqlConnection::getConnection(); //get connection
                $query = 'select * from viewCalls where callId = ?'; //query
                $command = $connection->prepare($query); //prepare statement
                $command->bind_param('s',$arguments[0]); //parameters
                $command->execute(); //execute
                //bind results
                $command->bind_result($id, 
                    $dateTimeReceived,
                    $dateTimeAnswered,
                    $dateTimeEnded,
                    $phoneNumber,
                    $sessionId,
                    $statusId,
                    $statusDescription,
                    $availableToAnswer,
                    $statusEndId,
                    $statusEndDescription,
                    $handleTime,
                    $waitTime); 
                //record was found
                if ($command->fetch()) {
                    //pass values to the attributes
                    $this->id = $id;
                    $this->dateTimeReceived = $dateTimeReceived;
                    $this->dateTimeAnswered = $dateTimeAnswered;
                    $this->dateTimeEnded = $dateTimeEnded;
                    $this->phoneNumber = $phoneNumber;
                    $this->session = $sessionId;
                    $this->status = new CallStatus($statusId, $statusDescription, $availableToAnswer);
                    $this->statusEnd = new CallEndStatus($statusEndId, $statusEndDescription);
                    $this->handleTime = $handleTime;
                    $this->waitTime = $waitTime;
                }
                else
                    throw new RecordNotFoundException($arguments[0]);
                mysqli_stmt_close($command); //close command
                $connection->close(); //close connection
            }
            //3multiple arguments received : gets data from arguments
            if (func_num_args() == 10) {
                $this->id = $arguments[0];
                $this->dateTimeReceived = $arguments[1];
                $this->dateTimeAnswered = $arguments[2];
                $this->dateTimeEnded = $arguments[3];
                $this->phoneNumber = $arguments[4];
                $this->session = $arguments[5];
                $this->status = $arguments[6];
                $this->statusEnd = $arguments[7];
                $this->$handleTime = $arguments[8];
                $this->$waitTime = $arguments[9];
            }
        }

        //instance methods
        
        //represents the object header in JSON format
        public function toJson() {
            return json_encode(array(
                'id' => $this->id,
                'dateTime' => array(
                    'received' => $this->dateTimeReceived,
                    'answered' => $this->dateTimeAnswered,
                    'ended' => $this->dateTimeEnded
                ),
                'phoneNumber' => $this->phoneNumber,
                'session' => $this->session,
                'status' => json_decode($this->status->toJson()),
                'statusEnd' => json_decode($this->statusEnd->toJson()),
                'metrics' => array(
                    'handleTime' => $this->handleTime,
                    'waitTime' => $this->waitTime
                )
            ));
        }

        //receive
        public static function receive($phoneNumber) {
            $connection = MySqlConnection::getConnection(); //get connection
            $query = 'call spReceiveCall(?, @result)'; //query
            $command = $connection->prepare($query); //prepare statement
            //parameters
            $command->bind_param('s', $phoneNumber); 
            $result = $command->execute(); //execute
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            return $result;
        }

        //end call
        public static function end($callId, $endStatusId) {
            $connection = MySqlConnection::getConnection(); //get connection
            $query = 'call spEndCall(?,?, @result)'; //query
            $command = $connection->prepare($query); //prepare statement
            //parameters
            $command->bind_param('ii', $phoneNumber, $endStatusId); 
            $result = $command->execute(); //execute
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            return $result;
        }

        //class methods
    }

?>