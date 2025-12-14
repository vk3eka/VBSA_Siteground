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
                $sql = "Select * FROM clubs Order by ClubNumber";
                $result = $dbcnx_client->query($sql);
                                        $num_rows = $result->num_rows;
                                        echo "<script type=\"text/javascript\">";
                                        echo "no_of_clubs = " . $num_rows . ";";
                                        echo "</script>";
                echo("<tr>");
                echo("<td colspan=4 align=center><b>Existing Clubs</b></td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td colspan=4 align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align='left'>Club ID</td>");
                echo("<td align='left'>Club Name</td>");
                echo("<td align='center'>Active</td>");
                echo("</tr>");
                                        $i = 0;
                while ($build_data = $result->fetch_assoc()) {
                    echo("<tr>");
                    echo("<td align='left' id='club_id_". $i . "'>" . $build_data['ClubNumber'] . "</td>");
                    echo("<td align='left'>" . $build_data['ClubTitle'] . "</td>");
                    if ($build_data['inactive'] == 1) {
                            echo("<td align='center'><input type='checkbox' name='active' id='active_". $i . "' checked></td>");
                    }
                    else
                    {
                            echo("<td align='center'><input type='checkbox' name='active' id='active_". $i . "'></td>");
                    };
                    echo("</tr>");
                                      $i = ($i + 1);
                }
            ?>
            </table>
        </td>
    </tr>
</table>

<?php include('footer.php'); ?>


