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
	
	//die;

	
	//$sizeQuery = "select m.size from menu m, sandwich s where m.sname = s.sname";
		//$sizeStmt = $mysqli->prepare($sizeQuery);
?>
<html>
<head><title></title>
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js'></script>
<SCRIPT TYPE="text/javascript">
	function addRow()
	{
		var current = window.event.srcElement;
		var rowCount = current.id.substr(3,current.id.len);
		var txtShow = document.getElementById("txtShow");
		var rowid = current.parentElement.parentElement.id;
		var selSize = "selSize" + rowCount;
		var size = document.getElementById(selSize).selected;
		var lblPrice = "lblPrice" + rowCount;
		var price = document.getElementById(lblPrice).textContent;
		var txtQuantity = "txtQuantity" + rowCount;
		var quantity = document.getElementById(txtQuantity).value;
		txtShow.value = txtShow.value + size + price + quantity ;
		//alert(txtShow.value);
		
		//-------------------------
		var tbody = document.getElementById('tblFinalOrder').getElementsByTagName("TBODY")[0];
		//alert(tbody);
		// create row
		var row = document.createElement("TR");
		// create table cell 1
		var td1 = document.createElement("TD")
		td1.innerHTML  = "<label id='lbl"+rowid+"'>"+rowid+"</label>";
		//alert(strHtml1);
		//td1.innerHTML = strHtml1.replace(/!count!/g,count);
		//alert("------"+td1.innerHTML);
		// create table cell 2
		var td2 = document.createElement("TD")
		td2.innerHTML = "<label id='lblSize"+rowid+"'>"+size+"</label>";
		//td2.innerHTML = strHtml2.replace(/!count!/g,count);
		// create table cell 3
		var td3 = document.createElement("TD")
		td3.innerHTML = "<label id='lblQuantity"+rowid+"'>"+quantity+"</label>";
		//td3.innerHTML = strHtml3.replace(/!count!/g,count);
		// create table cell 4
		var td4 = document.createElement("TD")
		td4.innerHTML = "<label id='lblPrice"+rowid+"'>"+price+"</label>";
		//td4.innerHTML = strHtml4.replace(/!count!/g,count);
		// create table cell 5
		var td5 = document.createElement("TD");
		var totalPrice = parseInt(quantity) * parseFloat(price);
		//alert(totalPrice);
		td5.innerHTML = "<label id='lblTotal"+rowid+"'>"+totalPrice+"</label>";
		//td5.innerHTML = strHtml5.replace(/!count!/g,count);
		// create table cell 6
		var td6 = document.createElement("TD")
		td6.innerHTML = "<INPUT TYPE=\"Button\" CLASS=\"Button\" onClick=\"delRow()\" VALUE=\"-\">";
		//td6.innerHTML = strHtml5.replace(/!count!/g,count);
		// append data to row
		row.appendChild(td1);
		row.appendChild(td2);
		row.appendChild(td3);
		row.appendChild(td4);
		row.appendChild(td5);
		row.appendChild(td6);
		// add to count variable
		//count = parseInt(count) + 1;
		// append row to table
		tbody.appendChild(row);
		//-------------------------
	}
	function delRow()
	{
		var current = window.event.srcElement;
		//here we will delete the line
		while ( (current = current.parentElement)  && current.tagName !="TR");
			current.parentElement.removeChild(current);
	}
	$(document).ready(function(e) 
	{   
		$('select').on('change',function ()
		{ 
			var optionSelected = $(this).find("option:selected");
			var valueSelected  = optionSelected.val();
			var textSelected   = optionSelected.text();
			var current = window.event.srcElement;
			var rowid = current.parentElement.parentElement.id;
			alert(current);
			alert(rowid);
		//doAjaxCall($(this).attr('id'), $(this).attr('data-action'));
		});
	});
	

	function doAjaxCall(varID, vote)
	{
		var pageUrl = "./query.php";
		var post = '{"ajax":"yes", "sname":' + sname + ', "size":' + size + '}';// Incase you want to pass dynamic ID
		$.post(pageUrl,post,function(data){
        var response = $.parseJSON( data )
        if(response.success)
		{
            //do whatever you like.
            alert('baaaam');
        }
    }); 
}
	
</SCRIPT>
<head>
<body>
<form name="form1" method="post" action="<?php $_PHP_SELF ?>">
	<table>
	<tr>
	<td>
    <?php
		$rowCount = 1;
		echo "<div>";
		echo "<table border='1'>";
   		while ($myrow = $result->fetch_assoc()) 
		{
	        echo "<tr id='".$myrow['sname']."'>";
			echo "<td><input type='radio' name='rdSandwich' id='rd".$myrow['sname']."'/></td>";
            echo "<td>".$myrow['sname']."</td>";
			echo "<td><p>".$myrow['description']."</p></td>";
			$sizeQuery = "select m.size,m.price
							from menu m
							where m.sname = '".$myrow['sname']."'";
			$sizeStmt = $mysqli->prepare($sizeQuery);
			$sizeStmt->execute();
			$sizeresult = $sizeStmt->get_result();
			echo '<td><select name="selSize'.$rowCount.'" id="selSize'.$rowCount.'">';
			while ($myrow1 = $sizeresult->fetch_assoc()) 
			{	
				echo '<option value="'.$myrow1['size'].'">'.$myrow1['size'].'</option>';
			}
			echo '</select></td>';
			echo "<td><label id='lblPrice".$rowCount."'>".$myrow1['price']."</label></td>";
			echo "<td><input type='txt' id='txtQuantity".$rowCount."' size='3'/></td>";
			echo "<td><input type='button' id='btn".$rowCount."' value='+' onclick='addRow()'/></td>";
			echo "</tr>";
			$rowCount++;
		}
		echo "</table>";
		//echo "<td>".$myrow['price']."</td>";
		//echo "<td><input type='checkbox' name='cb".$rowCount."' id='cb".$rowCount."'></td>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "<div>";
	?>
	</td>
	<td>
	<table id="tblFinalOrder" border="1">
	<thead>
		<tr>
			<th>Sandwich</th>
			<th>Size</th>
			<th>Qunatity</th>
			<th>price per item</th>
			<th>total</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	</table>
	</td>
	</tr>
	<tr>
		<td><input type="text" id="txtShow"/></td>
	</tr>
	</table>
</form>
</body>
</html>
