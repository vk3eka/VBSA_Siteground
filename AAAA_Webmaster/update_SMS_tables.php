<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="800" border="1" align="center">
  <tr>
    <td width="784" align="center" class="red_bold">When the &quot;Submit Query&quot; Button is clicked, the &quot; members_sms&quot; table will be emptied and updated.</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Please use this page to create a current list of members, juniors or female with Mobile Phone numbers for SMS</td>
  </tr>
  </table>
  <table align="center">
    <tr>
      <td>&nbsp;
      <?php require_once('../Connections/connvbsa.php'); ?>
	  <?php

if(isset($_POST["submit"]))

{

mysql_select_db($database_connvbsa, $connvbsa);


// START MEMBERS SMS
//tested ok drops the members_sms_members)

$querytoexecute = "DROP TABLE `vbsaorga_vbsa2`.`members_sms_members`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_members table was not dropped</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_members table was successfully dropped</font>";



//tested ok creates the members_sms_members)

$querytoexecute = "CREATE TABLE members_sms_members (Mobile VARCHAR(20), Name TEXT)";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_members table was not created</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_members table was successfully created</font>";



//tested ok inserts all members that exist in the members_fin table into the members_now table)

$querytoexecute = "INSERT members_sms_members (Mobile, Name)
SELECT MobilePhone AS Mobile, CONCAT(members.FirstName,' ',members.LastName) AS Name
FROM members
WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW( ) )) OR LifeMember=1 OR totplayed_curr>1 OR totplayed_prev>1) AND MobilePhone is not null
ORDER BY FirstName";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error current players were not inserted into the members_sms+members table</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms+membersw table was successfully populated with Mobile and Name (concatenated) from members table where MobilePhone is not null</font>";


//tested ok REMOVES SPACES IN "Mobile" column of the members_now table)

$querytoexecute = "UPDATE `members_sms_members` SET `Mobile` = REPLACE(`Mobile`, ' ', '')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces not removed in the members_sms_members Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>spaces have successfully been removed in the members_sms_members Mobile column</font>";


//tested ok INSERTS SPACE IN "Mobile" before the 8th digit so Mobile will be XXXXXXX XXX preparing for excel csv format phone number as text -1st step of 2 step process

$querytoexecute = "UPDATE `members_sms_members` SET `Mobile` = INSERT(`Mobile`, 8, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 7th number not inserted in the members_sms_members Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 1 of 2 space has been inserted after 7th number in the members_sms_members Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 5th digit so Mobile will be XXXX XXX XXX preparing for excel csv format phone number as text -2nd step of 2 step process

$querytoexecute = "UPDATE `members_sms_members` SET `Mobile` = INSERT(`Mobile`, 5, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 4th number not inserted in the members_sms_members Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 2 of 2 space has been inserted after 4th number in the members_sms_members Mobile column</font>";
// END MEMBERS SMS

// START MEMBERS FEMALE

//tested ok drops the members_sms_female)

$querytoexecute = "DROP TABLE `vbsaorga_vbsa2`.`members_sms_female`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_female table was not dropped</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_female table was successfully dropped</font>";



//tested ok creates the members_sms_female)

$querytoexecute = "CREATE TABLE members_sms_female (Mobile VARCHAR(20), Name TEXT)";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_female table was not created</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_female table was successfully created</font>";



//tested ok inserts all female members that exist in the members table into the members_sms_female table where Female = 1 or Paid = 20)

$querytoexecute = "INSERT members_sms_female (Mobile, Name)
SELECT MobilePhone AS Mobile, CONCAT(members.FirstName,' ',members.LastName) AS Name
FROM members
WHERE Female=1 AND MobilePhone is not null OR ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW( ) )))
ORDER BY FirstName";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error female players were not inserted into the members_sms_members table</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_female table was successfully populated with Mobile and Name (concatenated) from members table where MobilePhone is not null</font>";


//tested ok REMOVES SPACES IN "Mobile" column of the members_sms_female table)

$querytoexecute = "UPDATE `members_sms_female` SET `Mobile` = REPLACE(`Mobile`, ' ', '')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces not removed in the members_sms_female Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>spaces have successfully been removed in the members_sms_female Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 8th digit so Mobile will be XXXXXXX XXX preparing for excel csv format phone number as text -1st step of 2 step process

$querytoexecute = "UPDATE `members_sms_female` SET `Mobile` = INSERT(`Mobile`, 8, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 7th number not inserted in the members_sms_female Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 1 of 2 space has been inserted after 7th number in the members_sms_female Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 5th digit so Mobile will be XXXX XXX XXX preparing for excel csv format phone number as text -2nd step of 2 step process

$querytoexecute = "UPDATE `members_sms_female` SET `Mobile` = INSERT(`Mobile`, 5, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 4th number not inserted in the members_sms_female Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 2 of 2 space has been inserted after 4th number in the members_sms_female Mobile column</font>";

// END MEMBERS FEMALE

// START MEMBERS JUNIOR

//tested ok drops the members_sms_junior)

$querytoexecute = "DROP TABLE `vbsaorga_vbsa2`.`members_sms_juniors`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_juniors table was not dropped</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_juniors table was successfully dropped</font>";



//tested ok creates the members_sms_juniors)

$querytoexecute = "CREATE TABLE members_sms_juniors (Mobile VARCHAR(20), Name TEXT)";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_juniors table was not created</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_juniors table was successfully created</font>";



//tested ok inserts all junior members that exist in the members table into the members_sms_juniors table

$querytoexecute = "INSERT members_sms_juniors (Mobile, Name)
SELECT MobilePhone AS Mobile, CONCAT(members.FirstName,' ',members.LastName) AS Name
FROM members
WHERE dob_year between YEAR( CURDATE( ) ) -21 AND YEAR( CURDATE( ) ) AND (MobilePhone is not null OR ((paid_memb>0 AND YEAR(paid_date)=YEAR(NOW( ) ))) )
ORDER BY FirstName";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error juniors were not inserted into the members_sms_members table</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_juniors table was successfully populated with Mobile and Name (concatenated) from members table where MobilePhone is not null</font>";


//tested ok REMOVES SPACES IN "Mobile" column of the members_sms_juniors table)

$querytoexecute = "UPDATE `members_sms_juniors` SET `Mobile` = REPLACE(`Mobile`, ' ', '')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces not removed in the members_sms_juniors Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>spaces have successfully been removed in the members_sms_juniors Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 8th digit so Mobile will be XXXXXXX XXX preparing for excel csv format phone number as text -1st step of 2 step process

$querytoexecute = "UPDATE `members_sms_juniors` SET `Mobile` = INSERT(`Mobile`, 8, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 7th number not inserted in the members_sms_juniors Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 1 of 2 space has been inserted after 7th number in the members_sms_juniors Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 5th digit so Mobile will be XXXX XXX XXX preparing for excel csv format phone number as text -2nd step of 2 step process

$querytoexecute = "UPDATE `members_sms_juniors` SET `Mobile` = INSERT(`Mobile`, 5, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 4th number not inserted in the members_sms_juniors Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 2 of 2 space has been inserted after 4th number in the members_sms_juniors Mobile column</font>";

// END MEMBERS JUNIOR


// START MEMBERS IS

//tested ok drops the members_sms_IS)

$querytoexecute = "DROP TABLE `vbsaorga_vbsa2`.`members_sms_IS`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_IS table was not dropped</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_IS table was successfully dropped</font>";



//tested ok creates the members_sms_IS)

$querytoexecute = "CREATE TABLE members_sms_IS (Mobile VARCHAR(20), Name TEXT)";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_IS table was not created</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_IS table was successfully created</font>";



//tested ok inserts all junior members that exist in the members table into the members_sms_IS table

$querytoexecute = "INSERT members_sms_IS (Mobile, Name)
SELECT MobilePhone AS Mobile, CONCAT(members.FirstName,' ',members.LastName) AS Name
FROM members
WHERE HomeState != 'Vic' AND MemberID != 1 AND MobilePhone is not null
ORDER BY FirstName";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error IS were not inserted into the members_sms_members table</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_IS table was successfully populated with Mobile and Name (concatenated) from members table where MobilePhone is not null</font>";


//tested ok REMOVES SPACES IN "Mobile" column of the members_sms_IS table)

$querytoexecute = "UPDATE `members_sms_IS` SET `Mobile` = REPLACE(`Mobile`, ' ', '')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces not removed in the members_sms_IS Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>spaces have successfully been removed in the members_sms_IS Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 8th digit so Mobile will be XXXXXXX XXX preparing for excel csv format phone number as text -1st step of 2 step process

$querytoexecute = "UPDATE `members_sms_IS` SET `Mobile` = INSERT(`Mobile`, 8, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 7th number not inserted in the members_sms_IS Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 1 of 2 space has been inserted after 7th number in the members_sms_IS Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 5th digit so Mobile will be XXXX XXX XXX preparing for excel csv format phone number as text -2nd step of 2 step process

$querytoexecute = "UPDATE `members_sms_IS` SET `Mobile` = INSERT(`Mobile`, 5, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 4th number not inserted in the members_sms_IS Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 2 of 2 space has been inserted after 4th number in the members_sms_IS Mobile column</font>";

// END MEMBERS IS


// START MEMBERS BILLIARDS

//tested ok drops the members_sms_IS)

$querytoexecute = "DROP TABLE `vbsaorga_vbsa2`.`members_sms_billiards`";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_billiards table was not dropped</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_billiards table was successfully dropped</font>";



//tested ok creates the members_sms_billiards)

$querytoexecute = "CREATE TABLE members_sms_billiards (Mobile VARCHAR(20), Name TEXT)";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error members_sms_billiards table was not created</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_billiards table was successfully created</font>";



//tested ok inserts all junior members that exist in the members table into the members_sms_billiards table

$querytoexecute = "INSERT members_sms_billiards (Mobile, Name)
SELECT MobilePhone AS Mobile, CONCAT(members.FirstName,' ',members.LastName) AS Name
FROM members
WHERE totplaybill_curr+totplaybill_prev>1 AND MobilePhone is not null
ORDER BY FirstName";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error billiards were not inserted into the members_sms_members table</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>members_sms_billiards table was successfully populated with Mobile and Name (concatenated) from members table where MobilePhone is not null</font>";


//tested ok REMOVES SPACES IN "Mobile" column of the members_sms_billiards table)

$querytoexecute = "UPDATE `members_sms_billiards` SET `Mobile` = REPLACE(`Mobile`, ' ', '')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces not removed in the members_sms_billiards Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>spaces have successfully been removed in the members_sms_billiards Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 8th digit so Mobile will be XXXXXXX XXX preparing for excel csv format phone number as text -1st step of 2 step process

$querytoexecute = "UPDATE `members_sms_billiards` SET `Mobile` = INSERT(`Mobile`, 8, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 7th number not inserted in the members_sms_billiards Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 1 of 2 space has been inserted after 7th number in the members_sms_billiards Mobile column</font>";

//tested ok INSERTS SPACE IN "Mobile" before the 5th digit so Mobile will be XXXX XXX XXX preparing for excel csv format phone number as text -2nd step of 2 step process

$querytoexecute = "UPDATE `members_sms_billiards` SET `Mobile` = INSERT(`Mobile`, 5, 0, ' ')";
	
$result=mysql_query($querytoexecute, $connvbsa) or die("<br><br><font face='arial' color='green'>Error spaces after 4th number not inserted in the members_sms_billiards Mobile column</font>");

if (isset($result)) echo "<br><br><font face='arial' color='green'>STEP 2 of 2 space has been inserted after 4th number in the members_sms_billiards Mobile column</font>";

// END MEMBERS BILLIARDS

mysql_close ($connvbsa);

}

else

{

echo "<form id='form1' name='form1' method='post' action='update_SMS_tables.php?submit=1'>";

echo "<input type='submit' id='submit' name='submit'>";

echo "</form>";

}

?></td>
    </tr>
  </table>
  <table align="center">
    <tr>
      <td class="greenbg"><a href="create_members_SMS.php">Return to create sms</a></td>
    </tr>
  </table>
</body>
</html>