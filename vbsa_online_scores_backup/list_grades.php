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
                $sql = "Select * FROM Team_grade Order by grade_name";
                $result = $dbcnx_client->query($sql);
                        $num_rows = $result->num_rows;
                        echo "<script type=\"text/javascript\">"; 
                        echo "no_of_grades = " . $num_rows . ";"; 
                        echo "</script>";
                echo("<tr>"); 
                echo("<td colspan=8 align=center><b>Existing Grades</b></td>");
                echo("</tr>");
                echo("<tr>"); 
                echo("<td colspan=8 align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>"); 
                echo("<td align='center'>Grade</td>");
                echo("<td align='center'>Grade Name</td>");
                echo("<td align='center'>Season</td>");
                echo("<td align='center'>Type</td>");
                echo("<td align='center'>Year</td>");
                echo("<td align='center'>Day Played</td>");
                echo("<td align='center'>Current</td>");
                echo("<td align='center'>Action</td>");
                echo("</tr>");
                        $i = 0;
                while ($build_data = $result->fetch_assoc()) {
                    echo("<tr>"); 
                    echo("<td align='left' id='grade_". $i . "'>" . $build_data['grade'] . "</td>");
                    echo("<td align='left' id='grade_name_". $i . "'>" . $build_data['grade_name'] . "</td>");
                    echo("<td align='left' id='season_". $i . "'>" . $build_data['season'] . "</td>");
                    echo("<td align='left' id='type_". $i . "'>" . $build_data['type'] . "</td>");
                    echo("<td align='left' id='year_". $i . "'>" . $build_data['fix_cal_year'] . "</td>");
                    echo("<td align='left' id='day_". $i . "'>" . $build_data['dayplayed'] . "</td>");
                    if ($build_data['current'] == "Yes") {                  
                            echo("<td align='center'><input type='checkbox' name='current' id='current_". $i . "' checked></td>");
                    }
                    else
                    {
                            echo("<td align='center'><input type='checkbox' name='current' id='current_". $i . "'></td>");
                    }
                    echo("<td align='center'>");
                    echo("<a class='btn btn-primary btn-xs text-center' id='edit'>Edit Record</a>");
                    echo("</td>");
                    echo("</tr>");
                          $i = ($i + 1);
                } 
            ?>
            </table>
        </td>
    </tr>
</table>
           
<?php include('footer.php'); ?>

