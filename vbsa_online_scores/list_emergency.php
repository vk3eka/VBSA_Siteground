<?php
if (!isset($_SESSION)) 
{
  session_start();
}
include('header.php');
include('connection.inc');

?>
<table class='table table-striped table-bordered dt-responsive nowrap display' width='70%'>
    <tr>
        <td>
            <table class='table table-striped table-bordered dt-responsive nowrap display' width='70%'>
            <?php
                $sql = "Select * FROM tbl_emergency Order by MemberID";
                $result = $dbcnx_client->query($sql);
                $num_rows = $result->num_rows;
                echo("<tr>");
                echo("<td colspan=5 align=center><b>Emergency Players</b></td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td colspan=5 align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align='left'>Member ID</td>");
                echo("<td align='left'>First Name</td>");
                echo("<td align='left'>Last Name</td>");
                echo("<td align='left'>Email</td>");
                echo("<td align='left'>Mobile Phone</td>");
                echo("</tr>");
                if($num_rows == 0)
                {
                    echo("<tr>");
                    echo("<td colspan=5 align='center'>No records to display</td>");
                    echo("</tr>");
                }
                else
                {
                    $i = 0;
                    while ($build_data = $result->fetch_assoc()) {
                        echo("<tr>");
                        echo("<td align='left' id='member_id_". $i . "'>" . $build_data['MemberID'] . "</td>");
                        echo("<td align='left' id='firstname_". $i . "'>" . $build_data['FirstName'] . "</td>");
                        echo("<td align='left' id='lastname_". $i . "'>" . $build_data['LastName'] . "</td>");
                        echo("<td align='left' id='email_". $i . "'>" . $build_data['Email'] . "</td>");
                        echo("<td align='left' id='mobile_". $i . "'>" . $build_data['MobilePhone'] . "</td>");
                        echo("</tr>");
                        $i++;
                    }
                }
            ?>
            </table>
        </td>
    </tr>
</table>

<?php include('footer.php'); ?>
