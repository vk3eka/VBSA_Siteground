<?php

include("../vbsa_online_scores/connection.inc");

$page_from = $_GET['page'];


if (isset($_GET['year'])) {
  $current_year = $_GET['year'];
}

if (isset($_GET['season'])) {
  $current_season = $_GET['season'];
}

//echo("Page = " . $page_from . "<br>");

if($page_from == 'vbsa_members')
{
    $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, referee, coach_id, ccc_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach, dob_year, Junior, hon_memb FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR (Junior !='na' AND Junior !='U21') OR active_coach=1 OR Gender!='Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0";
}
else if($page_from == 'vbsa_members_affiliates')
{
    $sql = "Select *, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE ((((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR affiliate_player=1 OR Gender = 'Female' OR (Junior !='na' AND Junior !='U21') OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR active_coach = 1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1))) AND curr_memb = 0";
}
else if($page_from == 'vbsa_all')
{
    $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, curr_memb, HomeState FROM members WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND Deceased !=1) ORDER BY LastName, FirstName ";
}
else if($page_from == 'vbsa_all_email')
{
    $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, curr_memb, HomeState FROM members WHERE (ReceiveEmail != 0 and MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND Deceased !=1 AND Email != '') ORDER BY LastName, FirstName";
}
else if($page_from == 'vbsa_history')
{
    $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, curr_memb, HomeState FROM members WHERE (MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND contact_only = 0) ORDER BY LastName, FirstName ";
}
else if($page_from == 'vbsa_all_sms')
{
    $sql = "Select MemberID, FirstName, LastName, HomeState, MobilePhone, Email, ReceiveEmail, ReceiveSMS, curr_memb, HomeState FROM members WHERE (ReceiveSMS != 0 and MemberID != 1 AND MemberID != 100 AND MemberID != 500 AND MemberID != 1000 AND Deceased !=1) ORDER BY LastName, FirstName";
}
else if($page_from == 'vbsa_juniors')
{
    $sql = "Select MemberID, LastName, FirstName, HomePhone, MobilePhone, Email, ReceiveEmail, Junior, dob_day, dob_mnth, dob_year, HomeState FROM members WHERE dob_year between YEAR( CURDATE( ) ) -18 AND YEAR( CURDATE( ) ) ORDER BY LastName";
}
else if($page_from == 'vbsa_womens')
{
    $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, HomeState, ReceiveEmail, ReceiveSMS, paid_memb, LifeMember, Gender, Junior, referee, coach_id, ccc_player, affiliate_player, curr_memb, totplayed_curr+totplaybill_curr AS Current, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, memb_by, memb_occupation, active_coach FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id WHERE (Gender != 'Male') AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0 Order By LastName";
}
else if($page_from == 'vbsa_affiliates')
{
    $sql = "Select MemberID, FirstName, LastName, MobilePhone, Email, ReceiveEmail, ReceiveSMS, LifeMember, Affiliate_1, Affiliate_2, Affiliate_3, Affiliate_4 FROM members Where affiliate_player = 1 ORDER BY LastName";
}
else if($page_from == 'vbsa_online')
{
    $sql = "Select * From tbl_membership_online where archive = 1 Order By entered_on desc";
}
else if($page_from == 'absc_members')
{
    $sql = "Select MemberID, FirstName, LastName, HomeAddress, HomeSuburb, HomePostCode, HomeState, MobilePhone, members.Email, paid_memb, LifeMember, Gender, referee, Ref_class, active_coach, class, ccc_player, totplayed_curr AS CSnooker, totplaybill_curr AS CBilliards, dob_day, dob_mnth, dob_year, Junior, board_member_id, board_desc, assist FROM members LEFT JOIN coaches_vbsa ON MemberID=memb_id LEFT JOIN vbsaorga_users ON MemberID = vbsaorga_users.board_member_id WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW())) OR LifeMember=1 OR totplayed_curr+totplaybill_curr>0 OR ccc_player=1 OR referee=1 OR (Junior !='na' AND Junior !='U21') OR active_coach=1 OR Gender!='Male' OR hon_memb=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND MemberID != 1500 AND Deceased !=1) AND curr_memb = 0";
}

$result = $dbcnx_client->query($sql);
if (!$result) die('Couldn\'t fetch records');
$headers = $result->fetch_fields();
foreach($headers as $header) {
    $head[] = $header->name;
}
$fp = fopen('php://output', 'w');
if ($fp && $result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, array_values($head)); 
    while ($row = $result->fetch_array(MYSQLI_NUM)) 
    {
        fputcsv($fp, array_values($row));
    }
    fclose($fp);
}

?>