<?php
	include_once("database.php");
	//ini_set('display_errors', 'On');
	
	/** return all routines
	 * @return array routines
	 */
	function get_all_routines_array(){
		$database = new Database;
		
		$routines_query = "SELECT `routine_id`, `routine_name` FROM `routines` ORDER BY `routine_name`";
		$params = array();
		$cols = array_pad(array(), 2, '');
		$results = $database->db_select($routines_query, $params, $cols);
		
		return $results;
	}
	
	/** get exercises mathcing routine
	 * @param routine_id
	 * @return array exercises
	 */
	function get_routine_exercises_array($routine_id){
		$database = new Database;
		
		$goals_query = "SELECT `routine_id`, `routine_exercises`.`exercise_id`, CONCAT(`exercise_name`, ', ', `exercise_name_modifier`) AS `name` FROM `routine_exercises` INNER JOIN `exercises` ON `routine_exercises`.`exercise_id` = `exercises`.`exercise_id` WHERE `routine_id` = ? ORDER BY `exercise_name`, `exercise_name_modifier`";
		$params = array($routine_id);
		$cols = array_pad(array(), 3, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get table of all routines
	 * @return html table
	 */
	function get_routines_table(){
		$routines = get_all_routines_array();
		$table = "<table>
					<tr>
						<th>Routine</th>
						<th>Exercises</th>
					</tr>";
		
		foreach ($routines as $routine){
			$routine_id = $routine[0];
			$routine_name = $routine[1];
			$exercises = get_routine_exercises_array($routine_id);
			$table .= "<tr>
							<td>{$routine_name}</td>
							<td>";
			foreach ($exercises as $exercise){
				$exercise_name = $exercise[2];
				$table .= "{$exercise_name}<br>";
			}
			
			$table .= "</td></tr>";
		}
		
		$table .= "</table>";
		return $table;
	}
	
	/** get all exercises
	 * @return array exercises
	 */
	function get_all_exercises(){
		$database = new Database;
		
		$goals_query = "SELECT `exercise_id`, CONCAT(`exercise_name`, ',  ', `exercise_name_modifier`) AS `exercise_name_all` FROM `exercises` ORDER BY `exercise_name` ASC";
		$params = array();
		$cols = array_pad(array(), 2, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get form to add routine and exercises to choose by multiselect
	 * @return html form
	 */
	function add_routine_with_exercises_form(){
		$exercises = get_all_exercises();
		$exercise_input = "";
		foreach ($exercises as $exercise){
			$exercise_id = $exercise[0];
			$exercise_name = $exercise[1];
			$exercise_input .= "<option value='{$exercise_id}'>{$exercise_name}</option>";
		}
		
		$form = "<form action='../project/routines_add.php' method='post'>
						<table >
							<tr >
								<th>New Routine</th>
								<th>Select Exercises</th>
							</tr>
							<tr>
								<td><input type='text' name='new_routine' placeholder='Routine Name' required></td>
								<td><select name='exercise_ids[]' multiple=multiple>";
		$form .= $exercise_input;
		$form .= "</select></td>
				</tr>
				</table>
				<input type='submit' value='Add New Routine' class='button'>
				</form>";
		
		return $form;
	}
	
	/** perform insertion of new routine
	 * @param routine_name
	 * @return string insertion status message
	 */
	function insert_new_routine($routine_name){
		$database = new Database;
		
		$routine_query = "INSERT INTO `routines` (`routine_name`) VALUES (?)";
		$params = array($routine_name);
		$table = "`routines`";
		$results = $database->db_insert($routine_query, $params, $table);
		
		return $results;
	}
	
	/** perform insertion of new routine exercises
	 * @param routine_name
	 * @param exercises array
	 * @return string status
	 */
	function insert_new_routine_exercises($routine_name, $exercises){
		$database = new Database;
		$results = "";
		
		foreach ($exercises as $exercise){
			$exercise_id = $exercise;
			$exercise_query = "INSERT INTO `routine_exercises` (`routine_id`, `exercise_id`) SELECT `routine_id`, ? FROM `routines` WHERE `routine_name` = ?";
			$params = array($exercise_id, $routine_name);
			$table = "`routine_exercises`";
			$results .= $database->db_insert($exercise_query, $params, $table);
		}
		return $results;
	}
	
?>