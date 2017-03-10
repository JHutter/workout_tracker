<?php
	include_once("database.php");
	//ini_set('display_errors', 'On');
	
	/** get all exercises
	 * @return exercises array
	 */
	function get_all_exercises_array(){
		$database = new Database;
		
		$my_goals_query = "SELECT `exercise_id`, `exercise_name`, `exercise_name_modifier` FROM `exercises` ORDER BY `exercise_name`, `exercise_name_modifier`";
		$params = array();
		$cols = array_pad(array(), 3, '');
		$results = $database->db_select($my_goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get goals for given exercise
	 * @param exercise_id
	 * @return goals array
	 */
	function get_exercise_goals_array($exercise_id){
		$database = new Database;
		
		$goals_query = "SELECT `goal_exercises`.`goal_id`, `goal_name` FROM `goal_exercises` INNER JOIN `goals` ON `goal_exercises`.`goal_id` = `goals`.`goal_id` WHERE `exercise_id` = ? ORDER BY `goal_name` DESC";
		$params = array($exercise_id);
		$cols = array_pad(array(), 2, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get table of exercises
	 * @return html table
	 */
	function get_exercises_table(){
		$exercises = get_all_exercises_array();
		$table = "<table>
					<tr>
						<th>Exercise</th>
						<th>Matching Goals</th>
					</tr>";
		
		foreach ($exercises as $exercise){
			$exercise_id = $exercise[0];
			$exercise_name = $exercise[1].', '.$exercise[2];
			$goals = get_exercise_goals_array($exercise_id);
			$table .= "<tr>
							<td>{$exercise_name}</td>
							<td>";
			foreach ($goals as $goal){
				$goal_name = $goal[1];
				$table .= "{$goal_name}<br>";
			}
			
			$table .= "</td></tr>";
		}
		
		$table .= "</table>";
		return $table;
	}
	
	/** get goals
	 * @return goals array
	 */
	function get_all_goals_for_exercises(){
		$database = new Database;
		
		$goals_query = "SELECT `goal_id`, `goal_name` FROM `goals` ORDER BY `goal_name` ASC";
		$params = array();
		$cols = array_pad(array(), 2, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get form to add exercise and associate goals
	 * @return html form
	 */
	function add_exercise_with_goals_form(){
		$goals = get_all_goals_for_exercises();
		$goals_input = "";
		foreach ($goals as $goal){
			$goal_id = $goal[0];
			$goal_name = $goal[1];
			$goals_input .= "<option value='{$goal_id}'>{$goal_name}</option>";
		}
		
		$form = "<form action='../project/exercises_add.php' method='post'>
						<table >
							<tr >
								<th>New Exercise</th>
								<th>Reps/Time/Distance</th>
								<th>Select Goals to Match to This Exercise</th>
							</tr>
							<tr>
								<td><input type='text' name='new_exercise' placeholder='Exercise Name' required></td>
								<td><input type='text' name='new_exercise_modifier' placeholder='For example, 3x15' required></td>
								<td><select name='goal_ids[]' multiple=multiple>";
		$form .= $goals_input;
		$form .= "</select></td>
				</tr>
				</table>
				<input type='submit' value='Add New Exercise' class='button'>
				</form>";
		
		return $form;
	}
	
	/** perform new exercise insertion
	 * @param exercise_name
	 * @param exercise_name_modifier
	 * @return status string
	 */
	function insert_new_exercise($exercise_name, $exercise_name_modifier){
		$database = new Database;
		
		$exercise_query = "INSERT INTO `exercises` (`exercise_name`, `exercise_name_modifier`) VALUES (?, ?)";
		$params = array($exercise_name, $exercise_name_modifier);
		$table = "`exercises`";
		$results = $database->db_insert($exercise_query, $params, $table);
		
		return $results;
	}
	
	/** perform insertion of exercise goals
	 * @param exercise_name
	 * @param exercise_name_modifier
	 * @param goals
	 * @return status message
	 */
	function insert_new_exercise_goals($exercise_name, $exercise_name_modifier, $goals){
		$database = new Database;
		$results = "";
		
		foreach ($goals as $goal){
			$goal_id = $goal;
			$exercise_query = "INSERT INTO `goal_exercises` (`exercise_id`, `goal_id`) SELECT `exercise_id`, ? FROM `exercises` WHERE `exercise_name` = ? AND `exercise_name_modifier` = ?";
			$params = array($goal_id, $exercise_name, $exercise_name_modifier);
			$table = "`goal_exercises`";
			$results .= $database->db_insert($exercise_query, $params, $table);
		}
		return $results;
	}
	
?>