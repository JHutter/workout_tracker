<?php
	include_once("database.php");
	//ini_set('display_errors', 'On');
	
	/** return all workouts based on a user_id
	 * @param user_id
	 * @return array results
	 */
	function get_all_workouts_array($user_id){
		$database = new Database;
		
		$workouts_query = "SELECT `workout_id`, `workouts`.`routine_id`, `routine_name`, `date`, `notes` 
		FROM `workouts` INNER JOIN `routines` on `workouts`.`routine_id` = `routines`.`routine_id` WHERE `user_id` = ? ORDER BY `date` DESC";
		$params = array($user_id);
		$cols = array_pad(array(), 5, '');
		$results = $database->db_select($workouts_query, $params, $cols);
		
		return $results;
	}
	
	/** get exercises present in a routine
	 * @param routine_id
	 * @return array exercises
	 */
	function get_routine_exercises_array_for_workouts($routine_id){
		$database = new Database;
		
		$exercises_query = "SELECT `routine_id`, `routine_exercises`.`exercise_id`, `exercise_name` FROM `routine_exercises` INNER JOIN `exercises` ON `routine_exercises`.`exercise_id` = `exercises`.`exercise_id` WHERE `routine_id` = ? ORDER BY `exercise_name`, `exercise_name_modifier`";
		$params = array($routine_id);
		$cols = array_pad(array(), 3, '');
		$results = $database->db_select($exercises_query, $params, $cols);
		
		return $results;
	}
	
	/** get table showing all workouts matching a given user id
	 * @param user_id
	 * @return table of user workouts
	 */
	function get_workouts_table($user_id){
		$workouts= get_all_workouts_array($user_id);
		$table = "<table>
					<tr>
						<th>Routine</th>
						<th>Date</th>
						<th>Notes</th>
					</tr>";
		
		foreach ($workouts as $workout){
			$routine_name = $workout[2];
			$date = $workout[3];
			$notes = $workout[4];
			$table .= "<tr>
							<td>{$routine_name}</td>
							<td>{$date}</td>
							<td>{$notes}</td>
						</tr>";
		}
		$table .= "</table>";
		return $table;
	}
	
	/** return a search form to find routines by goal text
	 * @return search form
	 */
	function get_search_form(){
		$form = "<form action='../project/workouts_search.php' method='post'>
						<table >
							<tr >
								<th>Search routines to add by goal:</th>
							</tr>
							<tr>
								<td><input type='text' name='search' placeholder='Enter goal search, for example endurance' required></td>
							</tr>
							</table>
							<input type='submit' value='Search Routines' class='button'>
				</form>";
		
		return $form;
		
	}
	
	/** perform search of routines by goal term
	 * @param search term
	 * @return array routines
	 */
	// citation: http://www.dreamincode.net/forums/topic/242389-mysqli-bind-param-with-like/
	function routines_by_goal_fragment($goal_term){
		$database = new Database;
		
		$goal_query = "SELECT DISTINCT `routine_exercises`.`routine_id`, `routine_name` FROM `routines` 
			INNER JOIN `routine_exercises` ON `routines`.`routine_id` = `routine_exercises`.`routine_id`
			INNER JOIN `goal_exercises` ON `routine_exercises`.`exercise_id` = `goal_exercises`.`exercise_id`
			INNER JOIN `goals` on `goal_exercises`.`goal_id` = `goals`.`goal_id`
			WHERE LOWER(`goal_name`) LIKE LOWER(?)
			ORDER BY `routine_name` ASC";
		
		$params = array('%'.$goal_term.'%');
		$cols = array_pad(array(), 2, '');
		$results = $database->db_select($goal_query, $params, $cols);
		
		return $results;
	}
	
	/** return all exercises for a routine
	 * @param routine_id
	 * @return array exercises
	 */
	function get_routine_exercises_by_routine($routine_id){
		$database = new Database;
		
		$goals_query = "SELECT `routine_id`, `routine_exercises`.`exercise_id`, CONCAT(`exercise_name`, ',  ', `exercise_name_modifier`) AS `exercise_name_all` FROM `routine_exercises` 
						INNER JOIN `exercises` ON `routine_exercises`.`exercise_id` = `exercises`.`exercise_id` 
						WHERE `routine_id` = ? ORDER BY `exercise_name`, `exercise_name_modifier`";
		$params = array($routine_id);
		$cols = array_pad(array(), 3, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** return all goals for a given routine
	 * @param routine_id
	 * @return array goals
	 */
	function get_routine_goals_by_routine($routine_id){
		$database = new Database;
		
		$goals_query = "SELECT DISTINCT `goal_name` FROM `routine_exercises` 
			INNER JOIN `goal_exercises` ON `routine_exercises`.`exercise_id` = `goal_exercises`.`exercise_id` 
			INNER JOIN `goals` ON `goal_exercises`.`goal_id` = `goals`.`goal_id`
			WHERE `routine_id` = ? 
			ORDER BY `goal_name`";
		$params = array($routine_id);
		$cols = array_pad(array(), 1, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get form to add a workout from an already generated array of routines
	 * @param array routines
	 * @return form html
	 */
	function add_workout_by_routines_form($routines){
		$form = "<form action='../project/workouts_add.php' method='post'>
						<table >
							<tr >
								<th>Select Routine Name</th>
								<th>Goals</th>
								<th>Exercises</th>
								<th>Date</th>
								<th>Notes</th>
							</tr>";
		foreach ($routines as $routine){
			$routine_id = $routine[0];
			$routine_name = $routine[1];
			$exercises = get_routine_exercises_by_routine($routine_id);
			$goals = get_routine_goals_by_routine($routine_id);
			
			$form .= "<tr>
						<td><input type='radio' name='routine_id' value='{$routine_id}' required>{$routine_name}</td>
						<td>";
			
			foreach ($goals as $goal){
				$goal_name = $goal[0];
				$form .= "{$goal_name}<br>";
			}
			$form .= "</td><td>";
			
			foreach ($exercises as $exercise){
				$exercise_name = $exercise[2];
				$form .= "{$exercise_name}<br>";
			}
			$form .= "</td>";
			
			$form .= "		<td><input type='date' name='{$routine_id}_date'></td>
							<td><input type='text' name='{$routine_id}_notes' placeholder='Notes...'></td>
						</tr>";
		}
		$form .= "</table>
					<input type='submit' value='Add Selected Routine' class='button'>
					</form>";
		return $form;
	}

	
	/** perform insertion of new workout
	 * @param user_id
	 * @param routine_id
	 * @param date
	 * @param notes
	 * @return string insert results
	 */
	function insert_new_workout($user_id, $routine_id, $date, $notes){
		$database = new Database;
		
		$workout_query = "INSERT INTO `workouts` (`user_id`, `routine_id`, `date`, `notes`) VALUES (?, ?, ?, ?)";
		$params = array($user_id, $routine_id, $date, $notes);
		$table = "`workouts`";
		$results = $database->db_insert($workout_query, $params, $table);
		
		return $results;
	}
	
?>
