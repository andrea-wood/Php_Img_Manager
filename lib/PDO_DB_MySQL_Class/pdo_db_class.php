<?php

class PDODB {

	private $pdo = NULL;

	private $query;

	private $configs = array();

	private $conn = FALSE;

	private $log; //unused

	private $params = array();

	private $options = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES => FALSE
	);

	public function __construct(){
		// $this->log = new Logs();
		$this->OpenConnection();
	}

	public function OpenConnection(){

		$this->configs = parse_ini_file("configdb.ini");
	
		$dsn = $this->configs["db.adapter"] . ':dbname=' . $this->configs["db.name"] . ';host='. $this->configs["db.host"].'';

		try {	

			 if ($this->configs['db.charset'] and version_compare(PHP_VERSION, '5.3.6', '<')) {

		        $this->options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES '. $this->configs['db.charset'];

		    }

			$this->pdo = new PDO( 
				$dsn, $this->configs["db.username"],  
				$this->configs["db.password"], 
				$this->options
			);

			$this->conn = TRUE;

		} catch (PDOException $e)  {
			
			echo $e->getMessage();

			die();

		}
	}

	public function CloseConnection(){

		$this->pdo = NULL;

		$this->conn = FALSE;
	}

	private function Init($query , $parameters = array()){

		if(!$this->conn) { 

			$this->OpenConnection(); 

		}

		try {

			$this->query = $this->pdo->prepare($query);
			
			if(count($parameters) > 0) {

				foreach($parameters as $key => $value){ 

					$this->query->bindParam($key, $value);

				}		

			}

			$this->query->execute();

		} catch(PDOException $e) {

				echo $e->getMessage();

				die();

		}

		$this->params = array();
	}

	public function ExecuteQuery($query, $params = array(), $fetchmode = PDO::FETCH_ASSOC){

		$result = NULL;

		$query = trim($query);

		$this->Init($query, $params);

		$rawStatement = explode(" ", $query);
		
		$statement = $rawStatement[0];

		if ($statement === 'SELECT') {

			$result = $this->query->fetchAll($fetchmode);

		} elseif($statement === 'INSERT' ||  $statement === 'UPDATE' || $statement === 'DELETE') {

			$result = $this->query->rowCount();	

		}

		return $result;

	}

	public function GetRow($query, $params = array(), $fetchmode = PDO::FETCH_ASSOC){				
		
		$this->Init($query, $params);
			
		return $this->query->fetch($fetchmode);	

	}

	public function LastInsertId() {

		return $this->pdo->lastInsertId();

	}

}

?>