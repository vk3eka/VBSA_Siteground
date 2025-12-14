<?php require_once('../Connections/connvbsa.php'); 
mysql_select_db($database_connvbsa, $connvbsa);
error_reporting(0);

$sql = "Select * FROM affiliate_members Where memberID = 0";
//echo($sql . "<br>");
$result_affiliate = mysql_query($sql, $connvbsa) or die(mysql_error());
while($build_affiliate = $result_affiliate->fetch_assoc())
{
    if(($build_affiliate['DVSA'] == 1) && ($build_affiliate['MSBA'] == 1))
    {
        $affil_1 = 'MSBA';
        $affil_2 = 'DVSA';
    }
    else if(($build_affiliate['MSBA'] == 1) && ($build_affiliate['DVSA'] == 0))
    {
        $affil_1 = 'MSBA';
        $affil_2 = '';
    }
    else if($build_affiliate['DVSA'] == 1)
    {
        $affil_1 = 'DVSA';
        $affil_2 = '';
    }
    else if($build_affiliate['LVBSA'] == 1)
    {
        $affil_1 = 'LVBSA';
        $affil_2 = '';
    }
    $date = date_create($build_affiliate['DateJoined']);
    $date = date_format($date, 'Y-m-d');
    $sql_insert = "Insert INTO members (
    LastName, 
    FirstName, 
    MobilePhone, 
    Email, 
    ReceiveSMS, 
    ReceiveEmail, 
    LifeMember, 
    memb_date, 
    entered_on, 
    affiliate_player, 
    Affiliate_1, 
    Affiliate_2) 
    VALUES ('" .
    $build_affiliate['LastName'] . "', '" .
    $build_affiliate['FirstName'] . "', '" .
    $build_affiliate['MobilePhone'] . "', '" .
    $build_affiliate['Email'] . "', " .
    $build_affiliate['ReceiveSMS'] . ", " .
    $build_affiliate['ReceiveEmail'] . ", " .
    $build_affiliate['LifeMember'] . ", '" .
    $date . "', '" .
    $date . "', 1, '" .
    $affil_1 . "', '" .
    $affil_2 . "')";
    //echo($sql_insert . "<br>");
    $update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
    //echo("Data Updated<br>");
}

$sql = "Select * FROM affiliate_members Where memberID != 0";
//echo($sql . "<br>");
$result_affiliate = mysql_query($sql, $connvbsa) or die(mysql_error());
while($build_affiliate = $result_affiliate->fetch_assoc())
{
    if(($build_affiliate['DVSA'] == 1) && ($build_affiliate['MSBA'] == 1))
    {
        $affil_1 = 'MSBA';
        $affil_2 = 'DVSA';
    }
    else if(($build_affiliate['MSBA'] == 1) && ($build_affiliate['DVSA'] == 0))
    {
        $affil_1 = 'MSBA';
        $affil_2 = '';
    }
    else if($build_affiliate['DVSA'] == 1)
    {
        $affil_1 = 'DVSA';
        $affil_2 = '';
    }
    else if($build_affiliate['LVBSA'] == 1)
    {
        $affil_1 = 'LVBSA';
        $affil_2 = '';
    }
    $sql_update = "Update members Set affiliate_player = 1, Affiliate_1 = '" . $affil_1 . "', Affiliate_2 = '" . $affil_2 . "' Where MemberID = " . $build_affiliate['MemberID']; 
    
    //echo($sql_update . "<br>");
    $update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
    echo("Data Updated<br>");
}



?>