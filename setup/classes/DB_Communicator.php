<?php
class Operation {
    const select     = 0;
    const insert	 = 1;
    const update	 = 2;
    const delete	 = 3;
}


class DB_Communicator {
	private $db;
	private $statment;

	private $transaction;

	private $operation;
	private $fields;
	private $table;
	private $where;
	private $groupBy;
	private $having;
	private $orderBy;
	
	private $insertFields;
	
	private $set;
	
	private $result;

	public function __construct($database="mysql", $host="localhost", $user="root", $pass="") {
		$this->db = new mysqli($host, $user, $pass, $database);
		$this->transaction = false;

		if($this->db->connect_errno > 0){
			die('Impossibile connettersi al database [' . $this->db->connect_error . ']');
		}
	}
	
	public function query($query, $param){
		
		$this->statment = $this->db->prepare($query);
		
		$this->bind($this->statment, $param);
		
		$response = $this->statment->execute();
		$this->result = $this->statment->get_result();

		if($this->transaction && $response === FALSE){
			file_put_contents("00-log.txt", montaQuery($query, $param)."\n", FILE_APPEND);
			$this->rollbackTransaction();
			die();
		}
		
		$this->resetParam();
		
		return $this->getResult();
	}
	
	public function select($fields){
		$this->operation = Operation::select;
		$this->fields = $fields;
		return $this;
	}
	
	public function insert($table, $insertFields){
		$this->operation = Operation::insert;
		$this->table = $table;
		$this->insertFields = $insertFields;
		return $this;
	}
	
	public function update($table){
		$this->operation = Operation::update;
		$this->table = $table;
		return $this;
	}
	
	public function delete($table){
		$this->operation = Operation::delete;
		$this->table = $table;
		return $this;
	}
	
	public function set($set){
		$this->set = $set;
		return $this;
	}
	
	public function from($table){
		$this->table = $table;
		return $this;
	}	
	
	public function where($where){
		$this->where = $where;
		return $this;
	}
	
	public function groupBy($groupBy){
		$this->groupBy = $groupBy;
		return $this;
	}
	
	public function having($having){
		$this->having = $having;
		return $this;
	}
	
	public function orderBy($orderBy){
		$this->orderBy = $orderBy;
		return $this;
	}
	
	public function execute(){
		$query = "";
		$paramNeeded = false;
		switch($this->operation){
			case Operation::select:
				$query = "SELECT ";				
				$query .= $this->createQueryString( $this->fields, ",", "array_values" );
				$query .= " FROM ".$this->table;
				
				if(isset($this->where)){
					$query .= " WHERE ";
					$query .= $this->createQueryString( $this->where, "AND", "array_keys" );
				}
				
				if(isset($this->groupBy)){
					$query .= " GROUP BY ";
					$query .= $this->createQueryString( $this->groupBy, ",", "array_values" );
				}
				
				if(isset($this->having)){
					$query .= " HAVING ";
					$query .= $this->createQueryString( $this->having, "AND", "array_keys" );
				}
				
				if(isset($this->orderBy)){
					$query .= " ORDER BY ";
					$query .= $this->createQueryString( $this->orderBy, ",", "array_values" );
				}
				
				$this->statment = $this->db->prepare($query);
				
				$param = array();
				if(isset($this->where) && is_array($this->where)){
					$paramNeeded = true;
					$param = array_values($this->where);
				}
				
				if(isset($this->having) && is_array($this->having)){
					$paramNeeded = true;
					$param = array_merge( $param, array_values($this->having) );
				}
				
				if($paramNeeded){
					$this->bind($this->statment, $param);
				}
				
				break;
			
			case Operation::insert:
				$query = "INSERT ".$this->table;	
				$query .= " (".$this->createQueryString( $this->insertFields, ",", "array_keys" ).")";
				
				$paramInterr = "?";
				for($i=1; $i<count($this->insertFields); $i++){ $paramInterr .= ", ?"; }				
				$query .= " VALUES (".$paramInterr.")";
				
				$this->statment = $this->db->prepare($query);
				
				$param = array_values($this->insertFields);
				$this->bind($this->statment, $param);
				
				break;
				
			case Operation::update:
				$query = "UPDATE ".$this->table;	
				$query .= " SET ".$this->createQueryString( $this->set, "=?,", "array_keys" );
				$query .= "=?";
				
				if(isset($this->where)){
					$query .= " WHERE ";
					$query .= $this->createQueryString( $this->where, "AND", "array_keys" );
				}
				
				$this->statment = $this->db->prepare($query);
				
				$param = array_values($this->set);
				if(isset($this->where) && is_array($this->where)){
					$paramNeeded = true;
					$param = array_merge($param, array_values($this->where));
				}

				if($paramNeeded){
					$this->bind($this->statment, $param);
				}
				
				break;
				
			case Operation::delete:
				$query = "DELETE FROM ".$this->table;
				
				if(isset($this->where)){
					$query .= " WHERE ";
					$query .= $this->createQueryString( $this->where, "AND", "array_keys" );
				}
				
				$this->statment = $this->db->prepare($query);
				
				$param = array();
				if(isset($this->where) && is_array($this->where)){
					$paramNeeded = true;
					$param = array_values($this->where);
				}

				if($paramNeeded){
					$this->bind($this->statment, $param);
				}
				
				break;
		}
						
		/*echo $query."<br/><pre>";
		var_dump($param);
		echo "</pre>";*/

		$this->statment->execute();				
		$this->result = $this->statment->get_result();
		
		$this->resetParam();
		
		return $this->getResult();
		
	}
	

	public function getLastInsertedId(){
		return $this->statment->insert_id;
	}


	public function beginTransaction(){
		$this->db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		$this->transaction = true;
	}


	public function commitTransaction(){
		$this->db->commit();
		$this->transaction = false;
	}


	
	public function rollbackTransaction(){
		$this->db->rollback();
		$this->transaction = true;
	}
	




	private function resetParam(){
		$this->fields = NULL;
		$this->table = NULL;
		$this->where = NULL;
		$this->groupBy = NULL;
		$this->having = NULL;
		$this->orderBy = NULL;
		
		$this->insertFields = NULL;
		
		$this->set = NULL;
		
	}
	
	private function getResult(){
		if($this->result===FALSE) return FALSE;
		$num = $this->result->num_rows;

		$res = array();
		while($row = $this->result->fetch_assoc()){
			$temp = array();
			
			foreach($row AS $k => $v){
				$temp[$k] = $v;
			}
			
			$res[] = $temp;
		}
		$this->result->free();
		
		return $res;
	}
	
	
	private function bind($stmt, $params){
		if ($params != null){
			// Generate the Type String (eg: 'issisd')
			$types = '';
			foreach($params as $param){
				if(is_int($param)){
					// Integer
					$types .= 'i';
				}elseif (is_float($param)){
					// Double
					$types .= 'd';
				}elseif (is_string($param)){
					// String
					$types .= 's';
				}else{
					// Blob and Unknown
					$types .= 'b';
				}
			}
	  
			// Add the Type String as the first Parameter
			$bind_names[] = $types;
	  
			// Loop thru the given Parameters
			for ($i=0; $i<count($params);$i++){
				// Create a variable Name
				$bind_name = 'bind' . $i;
				// Add the Parameter to the variable Variable
				$$bind_name = $params[$i];
				// Associate the Variable as an Element in the Array
				$bind_names[] = &$$bind_name;
			}
			 
			// Call the Function bind_param with dynamic Parameters
			call_user_func_array(array($stmt,'bind_param'), $bind_names);
		}
		
		return $stmt;
	}

		
	private function createQueryString($fields, $concatenation = NULL, $function=NULL){
		if( is_array($fields) ){
			if($function!=NULL){ $fields = $function($fields); }
			$queryString = "";
			foreach($fields AS $n => $fieldName){
				$queryString .= $fieldName.$concatenation." ";
			}
			$queryString = substr($queryString, 0, -(strlen($concatenation)+1));
			
			return $queryString;
		}else{
			return $fields;
		}
	}
	
	
	function __destruct(){
        $this->db->close();
    }
}

?>