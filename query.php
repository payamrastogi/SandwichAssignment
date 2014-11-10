<?php 
	include("Connections/SandwichDB.php"); 
	if($_POST['ajax'] == 'yes')
	{
		$field = $_POST['vote'];
		$query = "select m.price from menu WHERE sname=".$_POST['sname']." and size=".$_POST['size'];
		$queryStmt = $mysqli->prepare($query);
		$queryStmt->execute();
		$queryResult = $queryStmt->get_result();
		$return = array();
		$return['success'] = false;
		if($query_run)
		{
			$return['success'] = true;
		}
		echo json_encode($return);
		die();
	}
?>