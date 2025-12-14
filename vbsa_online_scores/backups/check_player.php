<?php 

include('connection.inc');

$fullname = $_GET['fullname'];
$fullname = addslashes($fullname);

//$sql = "Select MemberID, FirstName, LastName, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE FirstName = '" . $newarray[0] . "' and LastName = '" . $newarray[1] . "'";
$sql = "Select MemberID, FirstName, LastName, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . $fullname . "%'";
//echo($sql . "<br>");
$result_players = $dbcnx_client->query($sql);
$build_data = $result_players->fetch_assoc();

$rows = [];
$num_rows = $result_players->num_rows;
if($num_rows == 0)
{
	$newarray = explode(" ", $fullname);
    $firstname = ucfirst($newarray[0]);
    $surname = stripslashes(ucfirst($newarray[1]));
    $memberid = '';
}
else
{
    $firstname = $build_data['FirstName'];
    //$surname = addslashes($build_data['LastName']);
    $surname = $build_data['LastName'];
    $memberid = $build_data['MemberID'];
	
}

$rows[0] = $firstname;
//$rows[1] = addslashes($surname);
$rows[1] = $surname;
$rows[2] = $memberid;

echo json_encode($rows);

?>