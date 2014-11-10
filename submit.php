<?php
	include("Connections/SandwichDB.php"); 
	if(isset($_POST['submit']))
	{
		echo $_POST['o_phone'];
		echo $_POST['o_sname'];
		echo $_POST['o_size'];
		if(! get_magic_quotes_gpc() )
		{	
			$o_size = addslashes ($_POST['o_size']);
			$o_sname = addslashes ($_POST['o_sname']);
		}
		else
		{
			$o_size = $_POST['o_size'];
			$o_sname = $_POST['o_sname'];
		}
		$o_phone = $_POST['o_phone']; 
		echo "\n";
		echo "$o_size";
		echo "$o_phone";
		echo "$o_sname";
		//$submitQuery = "Insert into orders (phone, sname, size, o_time, quantity, status) values (?, ?,? ,now(), 1, 'pending');";
		$submitQuery = "Select * from orders where phone = ? and sname = ? and size = ? and status = 'pending'";
		$submitStmt = $mysqli->prepare($submitQuery);
		if($submitStmt == false) 
		{
			echo "failed";
			trigger_error('Wrong SQL: ' . $submitQuery . ' Error: ' . $mysqli->error, E_USER_ERROR);
		}
		$submitStmt->bind_param("iss", $o_phone,$o_sname,$o_size);
		if(!$submitStmt->execute())
		{	
			echo "Execute failed: (" . $submitStmt->errno . ") " . $submitStmt->error;
		}
		if (!($submitResult = $submitStmt->get_result()))
		{
			echo "Getting result set failed: (" . $submitStmt->errno . ") " . $submitStmt->error;
		}
		if($submitResult->num_rows > 0)
		{
			$updateInsertQuery  = "Update orders set quantity = quantity + 1, o_time = now() where phone = ? and sname = ? and size = ? and status = 'pending'";
		}
		else
		{
			$updateInsertQuery = "Insert into orders values (?, ?, ?, now(), 1, 'pending')"; 
		}
		$submitStmt->close();
		$updateInsertStmt = $mysqli->prepare($updateInsertQuery);
		if($updateInsertStmt == false) 
		{
			echo "failed";
			trigger_error('Wrong SQL: ' . $updateInsertQuery . ' Error: ' . $mysqli->error, E_USER_ERROR);
		}
		$updateInsertStmt->bind_param("iss", $o_phone,$o_sname,$o_size);
		if(!$updateInsertStmt->execute())
		{	
			echo "Execute failed: (" . $updateInsertStmt->errno . ") " . $updateInsertStmt->error;
		}
		/* if (!($updateInsertResult = $updateInsertStmt->get_result()))
		{
			echo "Getting result set failed: (" . $updateInsertStmt->errno . ") " . $updateInsertStmt->error;
		} */
		$updateInsertStmt->close();
	}
?>