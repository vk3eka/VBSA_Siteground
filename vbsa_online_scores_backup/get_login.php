<?php 

include('connection.inc');

$email = $_GET['username'];
$password = $_GET['password'];
$venue = $_GET['venue'];

$emaildata = explode(",", $email);

if ((($password) <> "") && (($email) <> "")) 
{
	for($i = 0; $i < count($emaildata); $i++)
	{
		$sql = "Select * FROM tbl_authorise WHERE Email = '" . $emaildata[$i] . "'";
		$result = $dbcnx_client->query($sql);
		$row = $result->fetch_assoc();
		if($row["Password"] <> null && password_verify($password, $row["Password"]))
		{
			echo($_GET['venue']);
			return;
		}
	}
}

?>