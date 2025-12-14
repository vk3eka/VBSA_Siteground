<?php

include('connection.inc');
include('header.php'); 
include('php_functions.php'); 

function CheckExist($firstname, $lastname)
{
    $sql_member = "Select FirstName, LastName, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . $fullname . "%'";
}
?>
<script>

function SelectPlayerButton() 
{
    player = document.getElementById('new_player').value;
    document.player_select.Player.value = player;
    document.player_select.Select.value = 'true';
    document.player_select.submit();
}
</script>

<form name='player_select' method='post' action='combine_emergency.php'>
<input type='hidden' name='Player' />
<input type='hidden' name='Select' />
 <table border='0' align='center' cellpadding='0' cellspacing='10' width='50%'> 
 <tr> 
 <td colspan='3'><h1 align='center'>Select/Combine New Players</h1></td> 
 </tr> 
 <tr> 
 <td colspan='3'>&nbsp;</td> 
 </tr> 

 <tr> 
 <td align='center' valign='top'><b>Select new player:&nbsp;&nbsp; <select id='new_player' onchange='SelectPlayerButton()'> 
<?php
if(isset($_POST['Player'])) 
{
     echo("<option value='' selected='selected'>" . $_POST['Player'] . "</option>"); 
}
else
{
     echo("<option value='' selected='selected'></option>"); 
}
// fill player dropdown
$sql = "Select * FROM tbl_emergency Order by MemberID";
$result_player = $dbcnx_client->query($sql) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
while($build_data = $result_player->fetch_assoc()) 
{
    echo("<option value='" . $build_data['FirstName'] . " " . $build_data['LastName'] . "'>" . $build_data['FirstName'] . " " . $build_data['LastName'] . "</option>"); 
}

?>
 </select></b> 
 </td> 
 </tr> 
 <tr> 
 <td colspan='3'>&nbsp;</td> 
 </tr> 
 </table> 
 </form> 
 <?php

if($_POST['Select'] == 'true')
{
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
                echo("<td colspan=7 align=center><b>Combine Emergency Players</b></td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td colspan=7 align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align='left'>Member ID</td>");
                echo("<td align='left'>First Name</td>");
                echo("<td align='left'>Last Name</td>");
                echo("<td align='left'>Email</td>");
                echo("<td align='left'>Mobile Phone</td>");
                echo("<td align='center'>Action</td>");
                echo("<td align='center'>Seen?</td>");
                echo("</tr>");
                if($num_rows == 0)
                {
                    echo("<tr>");
                    echo("<td colspan=7 align='center'>No records to display</td>");
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
                        echo("<td align='center'>");
                        echo("<a class='btn btn-primary btn-xs text-center' id='combine_player_". $i . "'>Select Player</a>");
                        echo("</td>");
                        echo("<td align='center'><input type='checkbox' name='active' id='seen_". $i . "'></td>");
                        echo("</tr>");
                        $i++;
                    }
                }
            ?>
            </table>
        </td>
    </tr>
</table>
<?php
}
?>

<?php include('footer.php'); ?>
