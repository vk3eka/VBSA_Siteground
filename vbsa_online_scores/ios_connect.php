<?php
include("connection.inc");

$username = $_GET['username'];
$password = $_GET['password'];

// This SQL statement selects data from the table 'tbl_authorise' where email is correct
$sql = "Select * from tbl_authorise where Email = '" . trim($username) . "'";
// Check if there are results
$result = $dbcnx_client->query($sql) or die("Couldn't execute query. " . mysqli_error($dbcnx_client)); 
$resultArray = array();
// Loop through each row in the result set
while($row = $result->fetch_assoc())
{
	if(($row["Password"] <> null) && (password_verify($password, trim($row["Password"]))))
	{
		// Add each row into our results array
		array_push($resultArray, $row);
	}
}

// Finally, encode the array to JSON and output the results
echo json_encode($resultArray);

?>