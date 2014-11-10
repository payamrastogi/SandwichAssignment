<!DOCTYPE >

<?php
 
	include("Connections/SandwichDB.php"); 
	//perform SQL query
	if(isset($_POST["txtLookingFor"])) 
	{
		$orderQuery = "select m.sname, m.size, m.price, s.description 
						from menu m, sandwich s 
						where s.description like ? 
						and m.sname = s.sname GROUP BY m.sname";
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
	$result = $orderStmt->get_result();

?>
<html>
<head><title></title>
<SCRIPT TYPE="text/javascript">	
	function handleClick(myRadio) 
	{
		alert("juju");
		var str = myRadio.value.split('#');
		document.getElementById("o_sname").value = str[1];
		document.getElementById("o_size").value = str[2];
		document.getElementById("o_phone").value = "<?php echo $_POST['txtPhoneNumber']; ?>";
		alert(document.getElementById("o_phone").value);
		//alert(str[1]);
		//alert(str[2]);
	}
	
</SCRIPT>
<head>
<body>
<form name="form1" method="post" action="submit.php">
<input type="hidden" id="o_sname" name="o_sname" value=""/>
<input type="hidden" id="o_size" name="o_size" value=""/>
<input type="hidden" id="o_phone" name="o_phone" value=""/>
    <?php
		$rowCount = 1;
		echo "<div>";
		echo "<table border='1'>";
   		while ($myrow = $result->fetch_assoc()) 
		{
	        echo "<tr id='".$myrow['sname']."'>";
            echo "<td>".$myrow['sname']."</td>";
			echo "<td><p>".$myrow['description']."</p></td>";
			echo "<td>";
			$sizeQuery = "select m.size,m.price
							from menu m
							where m.sname = '".$myrow['sname']."'";
			$sizeStmt = $mysqli->prepare($sizeQuery);
			$sizeStmt->execute();
			$sizeresult = $sizeStmt->get_result();
			//print_r($s_size); die;
			//echo '<td><select name="s_size">';
			echo "<table>";
			while ($myrow1 = $sizeresult->fetch_assoc()) 
			{	
				echo "<tr id='".$myrow['sname']."'>";
				echo "<td><input type='radio' name='rdSandwich' value='rd#".$myrow['sname']."#".$myrow1['size']."' onclick='handleClick(this);'/></td>";
				echo "<td><label id='lblSize".$rowCount."'>". $myrow1['size']."</label></td>";
				echo "<td><label id='lblPrice".$rowCount."'>".$myrow1['price']."</label></td>";
				echo "</tr>";
				$rowCount++;
			}
			echo "</table>";
			echo "</td>";
			echo "</tr>";
        }
		$sizeStmt->close();
		$orderStmt->close();
		echo "<tr><td><input type='submit' name='submit' id='submit' value='Place Order'></td></tr>";
		echo "</table>";
		echo "<div>";
	?>
</form>
</body>
</html>
