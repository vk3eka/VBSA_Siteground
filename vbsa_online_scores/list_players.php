<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');

?>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='80%'>
            <?php
            $sql = "Select * FROM members where (MemberID != 1 OR MemberID != 100 OR MemberID != 1000 OR MemberID != 1500) Order by MemberID";
            $result = $dbcnx_client->query($sql);
            echo("<tr>"); 
            echo("<td colspan=5 align=center><b>Existing Players</b></td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td colspan=5 align=center>&nbsp;</td>");
            echo("</tr>");
            echo("<tr>"); 
            echo("<td width='25' align='left'>Member ID</td>");
            echo("<td width='25' align='left'>Name</td>");
            echo("<td width='25' align='left'>Email</td>");
            echo("<td width='25' align='left'>Club</td>");
            echo("<td width='25' align='left'>&nbsp;</td>");
            echo("</tr>");
        	$i = 0;
            while ($build_data = $result->fetch_assoc()) 
            {
                echo("<tr>"); 
                echo("<td width='25' align='left' id='player_id_". $i . "'>" . $build_data['MemberID'] . "</td>");
                echo("<td width='25' align='left'>" . $build_data['FirstName'] . " " . $build_data['LastName'] . "</td>");
                echo("<td width='25' align='left'>" . $build_data['Email'] . "</td>");
                echo("<td width='25' align='left'>" . $build_data['Club'] . "</td>");
                
                echo("<td align='center'>");
                echo("<a class='btn btn-primary btn-xs text-center' id='edit'>Edit Record</a>");
                echo("</td>");
                echo("</tr>");
                $i++;
            } 
            ?>
        </table>
	   </td>
    </tr>
</table>
<?php include('footer.php'); ?>