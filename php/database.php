<?php
	include_once("database_data.php");
	/** database class to modularize stuff (if more time, might've done DAO pattern)
	 */
	class Database {
		var $mysqli;
		var $con_status;
		
		function db_connect(){
			$db_data = new DatabaseData;
			$mysqli = new mysqli($db_data->host,$db_data->user,$db_data->password,$db_data->db);
			if(!$mysqli || $mysqli->connect_errno){
				$message = "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			else {
				$message = "Success";
			}
			
			return array($mysqli, $message);
		}
	
		/** perform general database insertion (or update), max of 5 params
		* @param query
		* @param parameters to bind
		* @param table
		* @return status message
		*/
		// citation: adapted from sample code given in module
		function db_insert($query, $params, $table){
			$message = "";
			$type_string = "";
			foreach ($params as $param) {
				$type_string .= substr(gettype($param), 0, 1);
			}
			if(!($stmt = $this->mysqli->prepare($query))){
				$message .= "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}

			// bind_params won't allow array for params, call_user_func_array was not going as planned
			switch (count($params)) {
				case 1:
					$stmt->bind_param($type_string,$params[0]);
					break;
				case 2:
					$stmt->bind_param($type_string,$params[0], $params[1]);
					break;
				case 3:
					$stmt->bind_param($type_string,$params[0], $params[1], $params[2]);
					break;
				case 4:
					$stmt->bind_param($type_string,$params[0], $params[1], $params[2], $params[3]);
					break;
				case 5:
					$stmt->bind_param($type_string,$params[0], $params[1], $params[2], $params[3], $params[4]);
					break;
				default:
					break;
			}
			if ($stmt->error){
				$message .= "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!$stmt->execute()){
				$message .= "Execute failed: "  . $stmt->errno . " " . $stmt->error;
			} 
			else {
				$message .= "Added " . $stmt->affected_rows . " rows to " . $table;
			}
			return $message;
		}
		
		/** perform general select query, max 5 parameters
		* @param query
		* @param params
		* @return rows array
		*/
		function db_select($query, $params, $cols){
			$message = "";
			$type_string = "";
			foreach ($params as $param) {
				$type_string .= substr(gettype($param), 0, 1);
			}
			if(!($stmt = $this->mysqli->prepare($query))){
				$message .= "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			// bind_params based on params count
			switch (count($params)) {
				case 1:
					$stmt->bind_param($type_string,$params[0]);
					break;
				case 2:
					$stmt->bind_param($type_string,$params[0], $params[1]);
					break;
				case 3:
					$stmt->bind_param($type_string,$params[0], $params[1], $params[2]);
					break;
				case 4:
					$stmt->bind_param($type_string,$params[0], $params[1], $params[2], $params[3]);
					break;
				case 5:
					$stmt->bind_param($type_string,$params[0], $params[1], $params[2], $params[3], $params[4]);
					break;
				default:
					break;
			}
			if ($stmt->error){
				$message .= "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			if(!($stmt->execute())){
				$message .= "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			$results = array();
			switch (count($cols)) {
				case 1:
					$stmt->bind_result($cols[0]);
					while($stmt->fetch()){
						$results[] = array($cols[0]);
					}
					break;
				case 2:
					$stmt->bind_result($cols[0], $cols[1]);
					while($stmt->fetch()){
						$results[] = array($cols[0], $cols[1]);
					}
					break;
				case 3:
					$stmt->bind_result($cols[0], $cols[1], $cols[2]);
					while($stmt->fetch()){
						$results[] = array($cols[0], $cols[1], $cols[2]);
					}
					break;
				case 4:
					$stmt->bind_result($cols[0], $cols[1], $cols[2], $cols[3]);
					while($stmt->fetch()){
						$results[] = array($cols[0], $cols[1], $cols[2], $cols[3]);
					}
					break;
				case 5:
					$stmt->bind_result($cols[0], $cols[1], $cols[2], $cols[3], $cols[4]);
					while($stmt->fetch()){
						$results[] = array($cols[0], $cols[1], $cols[2], $cols[3], $cols[4]);
					}
					break;
				default:
					break;
			}
			if ($mysqli->connect_error > 0){
				$message .= "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			
			
			//while($stmt->fetch()){
				//$results[] = array($cols[0]);
			//}
			
			
			//$stmt->close();
			return $results;
		}
		/** database class constructor, set up connection and status (and trusting garbage collection for destructor)
		*/
		function __construct() {
			$connection = $this->db_connect();
			$this->mysqli = $connection[0];
			$this->con_status = $connection[1];
		}
	}



?>