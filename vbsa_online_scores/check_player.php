<?php 

include('connection.inc');

$fullname = $_GET['fullname'];
$fullname = addslashes($fullname);

$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Deceased = 0 and Concat(FirstName, ' ', LastName) Like '%" . $fullname . "%'";
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
    $surname = $build_data['LastName'];
    $memberid = $build_data['MemberID'];
    $mobilephone = $build_data['MobilePhone'];
    $state = $build_data['HomeState'];
    $homephone = $build_data['HomePhone'];
    $postcode = $build_data['HomePostcode'];
    $email = $build_data['Email'];
    $overseas = $build_data['Overseas'];
    $dob_day = $build_data['dob_day'];
    $dob_month = $build_data['dob_mnth'];
    $dob_year = $build_data['dob_year'];
    $referee = $build_data['Referee'];
    $ref_class = $build_data['Ref_Class'];
    $occupation = $build_data['memb_occupation'];
    $rec_email = $build_data['ReceiveEmail'];
    $rec_sms = $build_data['ReceiveSMS'];
    $promo_pref = $build_data['promo_pref'];
    $promo_optin_out = $build_data['promo_optin_out'];
    $gender = $build_data['Gender'];

}
$rows[0] = $firstname;
$rows[1] = $surname;
$rows[2] = $memberid;
$rows[3] = $mobilephone;
$rows[4] = $state;
$rows[5] = $homephone;
$rows[6] = $postcode;
$rows[7] = $email;
$rows[8] = $overseas;
$rows[9] = $dob_day;
$rows[10] = $dob_month;
$rows[11] = $dob_year;
$rows[12] = $referee;
$rows[13] = $ref_class;
$rows[14] = $occupation;
$rows[15] = $rec_email;
$rows[16] = $rec_sms;
$rows[17] = $promo_pref;
$rows[18] = $promo_optin_out;
$rows[19] = $gender;

echo json_encode($rows);

?>