<?php
	include_once("database.php");
	//ini_set('display_errors', 'On');
	
	/** get goals for given user
	 * @param user_id
	 * @return goals array
	 */
	function get_my_goals_array($user_id){
		$database = new Database;
		
		$my_goals_query = "SELECT `goal_name`, `date_added`, `progress` FROM `user_goals` INNER JOIN `goals` on `user_goals`.`goal_id` = `goals`.`goal_id` WHERE `user_id` = ? ORDER BY `date_added`";
		$params = array($user_id);
		$cols = array_pad(array(), 3, '');
		$results = $database->db_select($my_goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get all goals (regardless of user)
	 * @return goals array
	 */
	function get_all_goals_array(){
		$database = new Database;
		
		$goals_query = "SELECT `goal_id`, `goal_name` FROM `goals` ORDER BY `goal_name` ASC";
		$params = array();
		$cols = array_pad(array(), 2, '');
		$results = $database->db_select($goals_query, $params, $cols);
		
		return $results;
	}
	
	/** get name of goal with given goal id
	 * @param goal_id
	 * @return goal_name string
	 */
	function get_goal_name_by_id($goal_id){
		$database = new Database;
		
		$goal_query = "SELECT `goal_name` FROM `goals` WHERE `goal_id` = ?";
		$params = array($goal_id);
		$cols = array_pad(array(), 1, '');
		$results = $database->db_select($goal_query, $params, $cols);
		$result = $results[0];
		return $result[0];
	}
	
	/** get table of user goals using global array SESSION
	 * @return html table
	 */
	function get_table_my_goals(){
		$user_id = $_SESSION['user_id'];
		$user_name = $_SESSION['user_name'];
		$goals = get_my_goals_array($user_id);
		
		// get array of user goals
		//$goals = get_my_goals_array($user_id);
		
		// if 0: display - it looks like you haven't added any goals yet!
		if (count($goals) == 0) {
			$table = "<p>It looks like you don't have any goals yet. Add one or more goals to get started.</p>";
		}
		// otherwise create the table
		else {
			$table = "<table>
						<tr>
							<th>Goal</th>
							<th>Date Added</th>
							<th>Progress Notes</th>
						</tr>";
			foreach ($goals as $row){
				$progress = stripslashes($row[2]);
				$table .= "<tr>
								<td>{$row[0]}</td>
								<td>{$row[1]}</td>
								<td>{$progress}</td>
							</tr>";
			}
			$table .= "</table>";
		}
		
		return $table;
		
	}
	
	/** get progress notes for a given user goal
	 * @param user_id
	 * @param goal_id
	 * @return progress string
	 */
	function get_progress_by_user_goal($user_id, $goal_id){
		$database = new Database;
		
		$progress_query = "SELECT `progress` FROM `user_goals` WHERE `goal_id` = ? AND `user_id` = ?";
		$params = array($goal_id, $user_id);
		$cols = array_pad(array(), 1, '');
		$results = $database->db_select($progress_query, $params, $cols);
		foreach ($results as $result){
			return stripslashes($result[0]);
		}
		
	}
	
	/** get form to add or edit goals (form for general goals and user specific goals)
	 * @param user_id
	 * @return html form
	 */
	function get_table_add_goals($user_id){
		$database = new Database;
		$all_goals = get_all_goals_array();
		
		$table = "<form action='../project/goals_all_add.php' method='post'>
						<table >
							<tr >
								<th></th>
								<th>Goal</th>
								<th>Progress Notes</th>
							</tr>";
		
		foreach ($all_goals as $goal){
			$goal_id = $goal[0];
			$goal_name = $goal[1];
			$progress = htmlspecialchars(get_progress_by_user_goal($user_id, $goal_id));
			if ($progress == null){
				$progress = "--- not in your goals yet";
			}
			$table .= "<tr >
								<td><input type='radio' name='goal_id' value={$goal_id}></td>
								<td>{$goal_name}</td><input type='hidden' name='{$goal_id}_name' value='{$goal_name}'>
								<td><input type='text' name='{$goal_id}_progress' value='{$progress}'></td>
							</tr>";
		}
		
		$table .= "		<tr >
								<td><input type='radio' name='goal_id' value=-1></td>
								<td><input type='text' name='goal_name' value='Add a new goal here!'></td>
								<td><input type='text' name='progress' value='Add notes/progress here'></td>
							</tr>
						</table>
				<input type='submit' value='Submit' class='button'>
				</form>";
		
		
		return $table;
	}
	
	/** perform insertion of goal (goals and user goals)
	 * @param goal_name
	 * @param user_id
	 * @param progress
	 * @return status string
	 */
	function insert_goal($goal_name, $user_id, $progress){
		$database = new Database;
		
		$insert_query = "INSERT INTO `goals` (`goal_name`) VALUES (?)";
		$params = array($goal_name);
		$table = "`goals`";
		$message1 = $database->db_insert($insert_query, $params, $table);
		
		$insert_user_goals_query = "INSERT INTO `user_goals` (`user_id`, `goal_id`, `progress`, `date_added`) SELECT ?, `goal_id`, ?, CURDATE() FROM `goals` WHERE `goal_name` = ?";
		$params = array($user_id, $progress, $goal_name);
		$table = "`user_goals'";
		$message2 = $database->db_insert($insert_user_goals_query, $params, $table);
		
		return $message1.$message2;
	}
	/** if user doesn't have the goal already, add it. Otherwise, update progress notes
	 * @param user_id
	 * @param goal_id
	 * @param progress_id
	 * @return status string
	 */
	function add_or_edit_my_goals($user_id, $goal_id, $progress){
		$database = new Database;
		
		$insert_user_goals_query = "INSERT INTO `user_goals` (`user_id`, `goal_id`, `progress`, `date_added`) VALUES (?, ?, ?, CURDATE())";
		$params = array($user_id, $goal_id, $progress);
		$table = "`user_goals'";
		$message = $database->db_insert($insert_user_goals_query, $params, $table);
		if (strpos($message, 'Added') == false){
			$update_goal_query = "UPDATE `user_goals` SET `progress` = ? WHERE `user_id` = ? AND `goal_id` = ?";
			$params = array($progress, $user_id, $goal_id);
			$message = $database->db_insert($update_goal_query, $params, $table);
		}
		return $message;

	}
?>
