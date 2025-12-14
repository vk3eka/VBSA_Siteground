<?php require_once('../Connections/connvbsa.php'); ?>
<?php
ob_start();
require_once('../Connections/connvbsa.php'); ?>
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

$html = "";

//set variable
$col1_player_det = "-1";
if (isset($_GET['stats'])) {
  $col1_player_det = $_GET['stats'];
}
$col2_player_det = "-1";
if (isset($_GET['year'])) {
  $col2_player_det = $_GET['year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_player_det = sprintf("SELECT scrs.scrsID, Team_entries.team_cal_year, Team_entries.team_club, game_type, scr_season, Team_entries.team_name, scrs.team_grade, FirstName, LastName, count_played, pts_won, average_position, percent_won FROM scrs LEFT JOIN Team_entries ON scrs.team_id = Team_entries.team_id LEFT JOIN members ON scrs.MemberID = members.MemberID WHERE Team_entries.team_club = %s AND team_cal_year = %s AND pts_won >0 AND scrs.MemberID != 1 GROUP BY scrs.scrsID ORDER BY scr_season, scrs.team_grade, FirstName", GetSQLValueString($col1_player_det, "text"),GetSQLValueString($col2_player_det, "int"));
$player_det = mysql_query($query_player_det, $connvbsa) or die(mysql_error());
$row_player_det = mysql_fetch_assoc($player_det);
$totalRows_player_det = mysql_num_rows($player_det);

?>

<?PHP


$html .= '
<div id="Wrapper" style = "width: 980px; margin-right: auto; margin-left: auto; height: auto;">


    <div id="PageContent" style="font-family: Arial,Helvetica,sans-serif; width: 945px; height: auto; background-color: rgb(255, 255, 255); margin-right: auto; margin-left: auto;">
    <center>
    <table align="center">
      <tr>
        <td colspan="4" class="red_bold" style="color: rgb(255, 0, 0);">Player stats for : '. $row_player_det['team_club'].' in '.$col2_player_det.'</td>
        <td colspan="4" align="right">&nbsp;</td>
      </tr>
      <tr>
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">First Name</td>
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Surname</a></td>
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Team Name</td>
	  <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Season</td>	  
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Grade</td>
	  <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Type</td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Matches played</td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Frames/points won</td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Average Position</td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">% Won</td>

      </tr>';

 do {
 $html .='
      <tr>
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['FirstName'].'</td>
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['LastName'].'</td>
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['team_name'].'</td>
	  <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['scr_season'].'</td>	  
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['team_grade'].'</td>
	  <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['game_type'].'</td>
	  <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['count_played'].'</td>
	  <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['pts_won'].'</td>
	  <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['average_position'].'</td>
	  <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['percent_won'].'</td>
      
      </tr>';

} while ($row_player_det = mysql_fetch_assoc($player_det));

$html .= '</table>
</center>
</div>

</div>
</body>
';

include("mpdf/mpdf.php");
$mpdf=new mPDF('utf-8', array(310,236));
$mpdf->WriteHTML($html);
$mpdf->Output("ClubHist.pdf","D");


?>
