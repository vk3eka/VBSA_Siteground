<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');
include('php_functions.php'); 

?>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
                <?php
                $sql = "Select * FROM tbl_club_results Order by club, round";
                $result = $dbcnx_client->query($sql);
                echo("<tr>"); 
                echo("<td colspan=18 align=center><b>Team Scores</b></td>");
                echo("</tr>");
                echo("<tr>"); 
                echo("<td colspan=18 align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>"); 
                echo("<td width='25' align='center'>Team</td>");
                echo("<td width='25' align='center'>Season</td>");
                echo("<td width='25' align='center'>Year</td>");
                echo("<td width='25' align='center'>Round</td>");
                echo("<td width='25' align='center'>Season</td>");
                echo("<td width='25' align='center'>Date Played</td>");
                echo("<td width='25' align='center'>Overall Points</td>");
                echo("<td width='25' align='center'>Games Won</td>");
                echo("</tr>");
                $i = 0;
                while ($build_data = $result->fetch_assoc()) {
                    echo("<tr>"); 
                    echo("<td width='25' align='center'>" . $build_data['club'] . "</td>");
                    echo("<td width='25' align='center'>" . $build_data['season'] . "</td>");
                    echo("<td width='25' align='center'>" . $build_data['year'] . "</td>");
                    echo("<td width='25' align='center'>" . $build_data['round'] . "</td>");
                    echo("<td width='25' align='center'>" . $build_data['season'] . "</td>");
                    echo("<td width='25' align='center'>" . DisplayDate($build_data['date_played']) . "</td>");
                    echo("<td width='25' align='center'>" . $build_data['overall_points'] . "</td>");
                    echo("<td width='25' align='center'>" . $build_data['games_won'] . "</td>");
                    echo("</tr>");
                    $i++;
                } 
                ?>
            </table>
       </td>
    </tr>
</table>

<?php include('footer.php'); ?>