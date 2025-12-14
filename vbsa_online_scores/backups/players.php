<?php

include ("connection.inc");
include ("header.php");

?>

<center>
<form name="players" method="post" action="players.php">
<input type="hidden" name="MemberID" />
<input type="hidden" name="ButtonName" />
<input type="hidden" name="PackedData" />
<?php
		$sql_memberid = "Select MemberID from members Where MemberID > 1000 Order by ID LIMIT 25";
		$sql_member_name = "Select * from members Where MemberID > 1000 Order by ID  LIMIT 25";
		$disabled = '';
		$readonly = '';
?>
		  <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
		  <tr>
		    <td width="200" align="right">Select by Player No.</td>
		    <td width="200" align="left"><select id="rego_no" onchange="GetRegoData(this)">
		   	<option value="" selected="selected"></option>
		<?php    
			$result1 = $dbcnx_client->query($sql_memberid);
			while ($build_data1 = $result1->fetch_assoc()) {
		        echo"<option value=" . $build_data1['ID'] .">" . $build_data1['ID'] ."</option>";
			}		  
		?>				  
		    </select></td>
		    <td width="200" align="right">Select by Player Name.</td>
		    <td width="200" align="left"><select id="fullname" onchange="GetNameData(this)">
		    <option value="" selected="selected"></option>
		<?php   
			$result2 = $dbcnx_client->query($sql_member_name);
			while ($build_data2 = $result2->fetch_assoc()) {
		        echo"<option value=" . $build_data2['ID'] . ">" . $build_data2['LastName'] . " " . $build_data2['FirstName'] . "</option>";
			}		  
		?>				  
		    </select></td>
		  </tr>
		</table>
		<br>
		<table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
  	<tr>
    	<td colspan=2 align=center><b>Player Registration Data</b></td>
		</tr>
		<tr>
			<td>
		    <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%' align=right>
	       	<tr> 
	          <td width="188" align="left">Player No.: - </td>
	          <td width="291" align="left"><input name="rego" type="text" value="<?php echo $row_data['PlayerID']; ?>" style="width : 50px;"></td>
	      	</tr>
	      	<tr> 
	          <td width="188" align="left">First Name: - </td>
	          <td width="291" align="left"><input name="firstname" type="text" value="<?php echo $row_data['FirstName']; ?>" style="width : 100px;"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Second Name: - </td>
	          <td width="291" align="left"><input name="middlename" type="text" value="<?php echo $row_data['SecondName']; ?>" style="width : 100px;"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Last Name: - </td>
	          <td width="291" align="left"><input name="lastname" type="text" value="<?php echo $row_data['LastName']; ?>" style="width : 100px;"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Address: - </td>
	          <td width="291" align="left"><input name="housenumber" type="text" value="<?php echo $row_data['HouseNumber']; ?>" style="width : 50px;" ><input name="streetname" type="text" value="<?php echo $row_data['StreetName']; ?>" style="width : 100px; text-transform:capitalize">
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Suburb: - </td>
	          <td><input name="homepostcode" id="postcode" type="text" value="<?php echo $row_data['PostCode']; ?>" style="width : 40px;" ></td>
	      	</tr> 
	        <tr> 
	          <td width="188" align="left">Phone (Home): </td>
	          <td width="291" align="left"><input name="homephone" type="text" value="<?php echo $row_data['PhoneHome']; ?>" style="width : 100px;"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Phone (Work): </td>
	          <td width="291" align="left"><input name="workphone" type="text" value="<?php echo $row_data['PhoneWork']; ?>" style="width : 100px;"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Mobile: </td>
	          <td width="291" align="left"><input name="mobilephone" type="text" value="<?php echo $row_data['PhoneMobile']; ?>" style="width : 100px;"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">E-Mail: </td>
	          <td width="291" align="left"><input name="emailaddress" id="emailaddress" type="text" value="<?php echo $row_data['EmailAddress']; ?>" size="25" style="width : 175px;" onchange="CheckEmailDuplicate()"></td>
	      	</tr>
	        <tr> 
	          <td width="188" align="left">Date of Birth: </td>
	          <td width="291" align="left"><input name="dateofbirth" type="text" id="dateofbirth" value="" style="width : 100px;"></td>
	      	</tr>
		  </table>
    </td>
  </tr>
</table>
</center>

<?php

include("footer.php"); 

?>


