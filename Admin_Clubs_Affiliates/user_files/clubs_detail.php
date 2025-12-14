<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

//session_destroy();

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

// set page url in session for insert / update files to return to detail page
$page = "http://www.vbsa.org.au/Admin_Clubs_Affiliates/user_files/clubs_detail.php";
$_SESSION['page'] = $page;

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

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
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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


mysql_select_db($database_connvbsa, $connvbsa);
$query_clubs = "SELECT * FROM clubs WHERE ClubNumber = '$club_id'";
$clubs = mysql_query($query_clubs, $connvbsa) or die(mysql_error());
$row_clubs = mysql_fetch_assoc($clubs);
$totalRows_clubs = mysql_num_rows($clubs);

mysql_select_db($database_connvbsa, $connvbsa);
$query_cont = "SELECT cont_id, club_id, cont_memb_id, cont_type, cont_title, FirstName, LastName, Email, HomeAddress, MobilePhone FROM clubs_contact LEFT JOIN members ON cont_memb_id=MemberID WHERE club_id = '$club_id' GROUP BY cont_id ORDER BY cont_type";
$cont = mysql_query($query_cont, $connvbsa) or die(mysql_error());
$row_cont = mysql_fetch_assoc($cont);
$totalRows_cont = mysql_num_rows($cont);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<table width="900" align="center">
  <tr>
    <td colspan="2" align="center" class="greenbg">&nbsp;</td>
    <!--<td colspan="2" align="left">page=<?php echo $_SESSION['page']; ?> detail = <?php echo $_SESSION['detail']; ?></td>-->
  </tr>
  <tr>
    <td align="center" class="red_bold"> Public Page Detail for: <?php echo $row_clubs['ClubTitle']; ?> (Club ID:<?php echo $club_id; ?>) </td>
    <td align="right" class="greenbg"><a href="<?php echo $_SESSION['detail']; ?>">Return to Previous page</a> </td>
  </tr>
  <tr>  
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
  </tr>
</table>

  <table width="900" align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="greenbg">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td align="left" class="greenbg"><a href="clubs_edit.php?club_id=<?php echo $row_clubs['ClubNumber']; ?>" >Edit these details</a> </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <!--<td class="greenbg"><a href="../all_clubs_public.php?club_id=<?php echo $row_clubs['ClubNumber']; ?> ">Upload an image</a></td>-->
        <td class="greenbg"><a href="../user_files/upload_image.php?club_id=<?php echo $row_clubs['ClubNumber']; ?> ">Upload an image</a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Title:</td>
        <td align="left"><?php echo $row_clubs['ClubTitle']; ?></td>
        <td>&nbsp;</td>
        <td align="right">VBSA Name:</td>
        <td align="left"><?php echo $row_clubs['ClubNameVBSA']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Address:</td>
        <td align="left"><?php echo $row_clubs['ClubStreet']; ?></td>
        <td>&nbsp;</td>
        <td align="right">Club Website::</td>
        <td align="left" class="page"><a href="<?php echo $row_clubs['../ClubLink']; ?>" target="_blank"><?php echo $row_clubs['ClubLink']; ?></a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Suburb:</td>
        <td align="left"><?php echo $row_clubs['ClubSuburb']; ?></td>
        <td>&nbsp;</td>
        <td align="right">Phone:</td>
        <td align="left" class="page"><a href="tel:<?php echo $row_clubs['ClubPhone1']; ?>" target="new"><?php echo $row_clubs['ClubPhone1']; ?></a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Postcode:</td>
        <td align="left"><?php echo $row_clubs['ClubPcode']; ?></td>
        <td>&nbsp;</td>
        <td align="right">Club Email:</td>
        <td align="left" class="page"><a href="mailto:<?php echo $row_clubs['ClubEmail']; ?>" target="new"><?php echo $row_clubs['ClubEmail']; ?></a> </td>
      </tr>
      <tr valign="baseline">
        <td align="right">Club Tables:</td>
        <td align="left"><?php echo $row_clubs['ClubTables']; ?></td>
        <td>&nbsp;</td>
        <td align="right">Pennant Tables:</td>
        <td align="left"><?php echo $row_clubs['PennantTables']; ?></td>
      </tr>
      <!--<tr valign="baseline">
        <td nowrap="nowrap" align="right">Melways Ref:</td>
        <td align="left"><?php echo $row_clubs['ClubMelwaysRef']; ?></td>
        <td>&nbsp;</td>
        <td align="right">Club Website::</td>
        <td align="left" class="page"><a href="<?php echo $row_clubs['../ClubLink']; ?>" target="_blank"><?php echo $row_clubs['ClubLink']; ?></a></td>
      </tr>-->
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td>&nbsp;</td>
        <?php if(isset($row_clubs['aff_URL'])) { ?>
        <td align="right">VBSA site page:</td>
        <td align="left" class="page"><a href="<?php echo $row_clubs['../aff_URL']; ?>" target="_blank"><?php echo $row_clubs['aff_URL']; ?></a></td>
        <?php } else echo '<td colspan=2>&nbsp;</td>' ?>
      </tr>
      <tr valign="baseline">
        <td colspan="5" align="center" nowrap="nowrap">Last Updated: <?php $newDate = date("d M Y", strtotime($row_clubs['LastUpdated'])); echo $newDate; ?>&nbsp;&nbsp;&nbsp; By: <?php echo $row_clubs['UpdatedBy']; ?>&nbsp;&nbsp;&nbsp; Comments:<?php echo $row_clubs['Comments']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td colspan="4" align="left" valign="top">&nbsp;</td>
      </tr>
    </table>  
    <table width="900" align="center" bgcolor="#FFFFFF">
          <tr>
            <td colspan="5" align="left" class="red_bold">This Club Fields teams in (Not applicable if  Association or Affiliate)</td>
      </tr>
          <tr>
            <td colspan="5" align="center">
            VBSA: <?php if($row_clubs['VBSAteam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            ChurchBill: <?php if($row_clubs['ChurchBillteam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            Church Bill: <?php if($row_clubs['ChurchBill']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            City Clubs: <?php if($row_clubs['CityClubTeam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            DVSA: <?php if($row_clubs['DVSAteam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            MSBA: <?php if($row_clubs['MSBAteam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            Over 55's: <?php if($row_clubs['Over55team']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
            RSL: <?php if($row_clubs['RSLteam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>&nbsp;&nbsp;&nbsp;
			Southern: <?php if($row_clubs['SouthernTeam']==1) echo '<img src="../../Admin_Images/tick.JPG" width="15"/>'; else echo ""; ?>            
            </td>
          </tr>
</table>


<table width="900" align="center">
  <tr>
    <td align="center" class="red_bold">Please note: To enter a &quot;Contact&quot; you will need a Member ID</td>
    <td align="center" class="greenbg"><a href="contact_insert.php?club_id=<?php echo $row_clubs['ClubNumber']; ?>">Insert a new Contact to this club</a></td>
  </tr>
  <tr>
    <td align="left" class="red_bold">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="red_bold">Current B&S Section Contacts</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left">Note: there may be more than 1 contact in any category</td>
  </tr>
</table>

  <div class="club_border">
    <table width="900">
      <tr>
        <th align="left">Type of contact</th>
        <th align="center">ID</th>
        <th align="left">Name</th>
        <th align="left">Job Description</th>
        <th align="left">Email</th>
        <th align="left" class="greenbg">Mobile</th>
        <th align="center">&nbsp;</th>
        <th align="center" class="greenbg">&nbsp;</th>
        <th align="center">&nbsp;</th>
      </tr>
	  <?php do { ?>
      <tr>
        <td align="left"><?php echo $row_cont['cont_type']; ?></td>
        <td align="center"><?php echo $row_cont['cont_memb_id']; ?></td>
        <td align="left"><?php echo $row_cont['FirstName']; ?> <?php echo $row_cont['LastName']; ?></td>
        <td align="left"><?php echo $row_cont['cont_title']; ?></td>
        <td  align="left" class="page"><a href="mailto:<?php echo $row_cont['Email']; ?>"><?php echo $row_cont['Email']; ?></a> </td>
        <td align="left" class="page"><a href="tel:<?php echo $row_cont['MobilePhone']; ?>"><?php echo $row_cont['MobilePhone']; ?></a> </td>
        
        <!-- Member Edit personal -->
        <td align="center">
        	<a href="../../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_cont['cont_memb_id']; ?>&club_id=<?php echo $club_id; ?>" ><img src="../../Admin_Images/edit_butt.fw.png" width="20" /></a> (personal)
        </td>
        <!-- Contact Edit -->
        <td align="center">
        	<a href="contact_edit.php?cont_id=<?php echo $row_cont['cont_id']; ?>&club_id=<?php echo $club_id; ?>"><img src="../../Admin_Images/edit_butt.fw.png" width="20" /></a> (Club info)</td>
    <!-- Contact Delete -->
        <td align="center">
        	<a href="contact_delete_confirm.php?cont_number=<?php echo $row_cont['cont_id']; ?>&club_id=<?php echo $club_id; ?> "><img src="../../Admin_Images/Trash.fw.png" height="20" title="delete this contact" /></a>
        </td>  
      </tr>
	  	<?php } while ($row_cont = mysql_fetch_assoc($cont)); ?>
      
    </table>
  </div>
  
</body>
</html>
<?php
mysql_free_result($clubs);

mysql_free_result($cont);
?>