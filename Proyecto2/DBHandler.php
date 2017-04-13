<?php
class DBHandler {
    private $_db;
    private $_stmt;
    private $_values = array();
    private $_types = '';
    private $_error = false;

	//TODO: MAKE THIS ONE USE A SINGLETON MYSQLI INSTANCE 
	
    /*
	$db = mysqlDB connector
    */
    public function __construct($db) {
        $this->_db = $db;
    }
	
	/*
		Resets everything but the dbconn, so we can reuse this object
	 */
    public function clear() {
        $this->_stmt = null;
		$this->_values = array();
		$this->_types = '';
		$this->_error = false;
    }
	
	/*
	Adds a parmeter to the query
	
	$type = a string with the type:
	i     corresponding variable has type integer
	d     corresponding variable has type double
	s     corresponding variable has type string
	b     corresponding variable is a blob and will be sent in packets
	$value the value
	*/
    public function add($type, &$value ){
        $this->_values[] = $value;
        $this->_types .= $type;
    }
	
    /*
		Initializes a query
		
		$query = a query like "INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)"
	 */
    public function prepareStatement($query) {
        $stmt = $this->_db->prepare($query);
		if ($stmt !== false) {
			if (count($this->_values) > 0) {
				$bindParams = $this->_get();
				$return = call_user_func_array(array($stmt, "bind_param"), $this->_makeValuesReferenced($bindParams));
				if ($return === false) {
					$this->_error = $stmt->error;
					$this->_treatError();
				}
			}
			if ($this->_error === false) {
				$this->_stmt = $stmt;
			}
		} else {
			$this->_error = $this->_db->error;
			$this->_treatError();
		}
    }
	
    /*
		Does a query
	 */
    public function exec() {
		if ($this->_error === false) {
			$return = $this->_stmt->execute();
			if ($return === false) {
				$this->_error = $this->_stmt->error;
				$this->_treatError();
			}
			$this->_stmt->close();
		}
    }
	
	/*
		Does a query and returns the munber of rows affected
	 */
    public function execCount() {
		$rows = 0;
		if ($this->_error === false) {
			$return = $this->_stmt->execute();
			if ($return === false) {
				$this->_error = $this->_stmt->error;
				$this->_treatError();
			} else {
				$rows = $this->_stmt->affected_rows;
			}
			$this->_stmt->close();
		}
		return $rows;
    }
	
    /*
		Does a query and returns the result as array
	 */
    public function query() {
        $result = array();
		if ($this->_error === false) {
			$this->_stmt->execute();
			if ($this->_stmt->error == '') {
				$result = $this->_fetch();
			} else {
				$this->_error = $this->_stmt->error;
				$this->_treatError();
				$result = false;
			}
		} else {
			$result = false;
		}
		$this->_stmt->close();
		return $result;
    }
	
	/*
		Gets the insert ID
	*/
	public function getInsertId() {
		return $this->_db->insert_id;
	}
	
	
	/*
		Gets any error that happened
	 */
    public function getError() {
        $result = false;
		$result = $this->_error;
		return $result;
    }
	
	private function _get(){
        return array_merge(array($this->_types), $this->_values);
    } 
	
	private function _makeValuesReferenced($arr){
		$refs = array();
		foreach($arr as $key => $value) {
			$refs[$key] = &$arr[$key];
		}
		return $refs;
		
	}
	
	private function _fetch() {   
		$array = array();
		$this->_stmt->store_result();
		
		$variables = array();
		$data = array();
		$meta = $this->_stmt->result_metadata();
		
		while($field = $meta->fetch_field()) {
			$variables[] = &$data[$field->name]; // pass by reference
		}
		
		call_user_func_array(array($this->_stmt, 'bind_result'), $variables);
		
		$i=0;
		while($this->_stmt->fetch()) {
			$array[$i] = array();
			foreach($data as $k=>$v) {
				$array[$i][$k] = $v;
			}
			$i++;
			// don't know why, but when I tried $array[] = $data, I got the same one result in all rows
		}
		
		return $array;
	}
	
	private function _treatError() {
		$messageArr = array();
		$messageArr[] = 'DBerror:' . "\r\n" . $this->_error;
		$messageArr[] = '$_REQUEST:' . "\r\n" . print_r($_REQUEST, true);
		$messageArr[] = '$_SESSION:' . "\r\n" . print_r($_SESSION, true);
		$messageArr[] = '$_SERVER:' . "\r\n" . print_r($_SERVER, true);
			
		$message = implode("\n\n", $messageArr);
		
		if (defined('DEVELOPMENT') && DEVELOPMENT == true) {
			die($message);
		} else {
			//mail ( 'jl.garcia@formblitz.de', "Test mail", "Test mail from your server name" );
			
			$sm = new SimpleMail(ADMIN_MAIL, 'DB Error at Kultkeks Backoffice', $message);
			
			$return = $sm->getReturn();
			die('There was a Database error. The Admins have been notified with the details about the incident. ');
		}
	} 
}
?>