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
if (isset($_GET['playdet'])) {
  $col1_player_det = $_GET['playdet'];
}
$col2_player_det = "-1";
if (isset($_GET['team_cur_year'])) {
  $col2_player_det = $_GET['team_cur_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_player_det = sprintf("SELECT members.FirstName, members.LastName, scrs.team_grade, Team_entries.team_club, Team_entries.team_name, Team_entries.team_cal_year,  scrs.played_S1, scrs.pts_S1, scrs.avg_posS1, ROUND(SUM( scrs.pts_S1 / scrs.played_S1 /3 *100 ),2) AS percS1, scrs.played_S2, scrs.pts_S2, scrs.avg_posS2, ROUND(SUM( scrs.pts_S2 / scrs.played_S2 /3 *100 ),2) AS percS2,  scrs.played_bill_S1, scrs.pts_bill_S1, scrs.avg_pos_bill_S1, ROUND(SUM( scrs.pts_bill_S1 / scrs.played_bill_S1 /2 *100 ),2) AS percbillS1, scrs.played_bill_S2, scrs.pts_bill_S2, scrs.avg_pos_bill_S2, ROUND(SUM( scrs.pts_bill_S2 / scrs.played_bill_S2 /2 *100 ),2) AS percbillS2 FROM scrs, members, Team_entries WHERE scrs.MemberID = members.MemberID  AND Team_entries.team_id = scrs.team_id  AND members.FirstName <> 'bye'  AND members.FirstName <> 'forfeit'  AND Team_entries.team_club = %s AND team_cal_year = %s  AND (played_S1>0 OR played_S2>0 OR played_bill_S1>0 OR played_bill_S2>0) GROUP BY scrs.scrsID ORDER BY scrs.team_grade", GetSQLValueString($col1_player_det, "text"),GetSQLValueString($col2_player_det, "int"));
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
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">Grade</td>
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
      <td align="left" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">'.$row_player_det['team_grade'].'</td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">';

        if($row_player_det['played_S1']>0)
            {
                $html .= $row_player_det['played_S1'];
            }
        elseif($row_player_det['played_S2']>0)
            {
                $html .= $row_player_det['played_S2'];
            }
        elseif($row_player_det['played_bill_S1']>0)
            {
                $html .= $row_player_det['played_bill_S1'];
            }
		elseif($row_player_det['played_bill_S2']>0)
            {
                $html .= $row_player_det['played_bill_S2'];
            }
        $html.='
      </td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">';

        if($row_player_det['played_S1']>0)
            {
                $html .= $row_player_det['pts_S1']; ;
            }
        elseif($row_player_det['played_S2']>0)
            {
                $html .= $row_player_det['pts_S2']; ;
            }
        elseif($row_player_det['played_bill_S1']>0)
            {
                $html .= $row_player_det['pts_bill_S1']; ;
            }
		elseif($row_player_det['played_bill_S2']>0)
            {
                $html .= $row_player_det['pts_bill_S2']; ;
            }
 $html .='
      </td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">';
        if($row_player_det['played_S1']>0)
            {
                $html .= $row_player_det['avg_posS1']; ;
            }
        elseif($row_player_det['played_S2']>0)
            {
                $html .= $row_player_det['avg_posS2']; ;
            }
        elseif($row_player_det['played_bill_S1']>0)
            {
                $html .= $row_player_det['avg_pos_bill_S1']; ;
            }
		elseif($row_player_det['played_bill_S2']>0)
            {
                $html .= $row_player_det['avg_pos_bill_S2']; ;
            }
        $html .='</td>
      <td align="center" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px; height: 22px; padding: 3px 7px 7px 3px; vertical-align: middle; line-height: 17px;">';

        if($row_player_det['played_S1']>0)
            {
             $html .= $row_player_det['percS1']; ;
            }
        elseif($row_player_det['played_S2']>0)
            {
             $html .= $row_player_det['percS2']; ;
            }
        elseif($row_player_det['played_bill_S1']>0)
            {
                $html .= $row_player_det['percbillS1']; ;
            }
		elseif($row_player_det['played_bill_S2']>0)
            {
                $html .= $row_player_det['percbillS2']; ;
            }

          $html .='</td>
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
