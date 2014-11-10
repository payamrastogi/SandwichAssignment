<!DOCTYPE >
<?php 
	include("Connections/SandwichDB.php"); 
	//perform SQL query
	 
	if(isset($_POST["txtLookingFor"])) 
	{
		$orderQuery = "select m.sname, m.size, m.price, s.description 
						from menu m, sandwich s 
						where s.description like ? 
						and m.sname = s.sname";
		$lookingFor = "%{$_POST['txtLookingFor']}%";
		$orderStmt = $mysqli->prepare($orderQuery);
		$orderStmt->bind_param("s", $lookingFor);
	}
	else 
	{
		$orderQuery = "select m.sname, m.size, m.price, s.description 
						from menu m, sandwich s 
						where m.sname = s.sname";
		$orderStmt = $mysqli->prepare($orderQuery);
	}
	
	if($orderStmt == false) 
	{
		trigger_error('Wrong SQL: ' . $orderQuery . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}
	$orderStmt->execute();
	$orderStmt->bind_result($s_name, $s_size, $s_price, $s_description);
?>
<html>
<head><title></title>
<body>
<form name="form1" method="post" action="">
    <table border = '1'>
	
    <?php
		$rowCount = 1;
   		while($orderStmt->fetch()) 
		{
			echo "<div>";
			echo "<table>";
	        echo "<tr>";
            echo "<td><h4>$s_name</h4></td>";
			echo "<td></td>";
			echo "<td></td>";
	        echo "</tr>";
			echo "<tr>";
			echo "<td><p>$s_description</p></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>$s_size</td>";
			echo "<td>$s_price</td>";
			echo "<td><input type='checkbox' name='cb".$rowCount."' id='cb".$rowCount."'></td>";
			echo "</tr>";
			echo "</table>";
			echo "<div>";
			$rowCount++;
        }
	?>
     </table>
</form>
</body>
</html>
