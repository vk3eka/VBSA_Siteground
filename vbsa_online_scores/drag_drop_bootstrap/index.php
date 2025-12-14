<?php

include('../connection.inc'); 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag & Drop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

</head>
<?php
$sql_club = 'Select * from Team_entries where team_cal_year = 2024 and team_grade = "APS" order by fix_sort';
    //echo($sql_club . "<br>");
    $datas  = $dbcnx_client->query($sql_club);
?>
<style>
    .ui-sortable-helper {
  display: table;
}


</style>
<body>
    <div class="container">
        <h3 class="text-center">Dynamic Drag and Drop table rows</h3>
        <table class="table table-bordered" id="mytable">
            <thead>
                <th>Team ID</th>
                <th>v</th>
                <th>Team ID</th>
            </thead>
            <tbody class="row_position">
                <?php 
                $i = 0;
                while ($data = $datas->fetch_assoc()) { ?>
                <tr id="<?php echo $data['team_id']; ?>">
                    <td><input type='text' value='<?= $data['team_club_id'] ?>'></td>
                    <td>v</td>
                    <td><input type='text' value='<?= $data['team_club_id'] ?>'></td>
                </tr>
                <?php
                }
                ?>
                
            </tbody>
        </table>
    </div>


</body>
<script type="text/javascript">
    $(".row_position").sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $(".row_position>tr").each(function() {
                selectedData.push($(this).attr("id"));
            });
            //updateOrder(JSON.stringify(selectedData));
        }
    });

    function updateOrder(aData) {
        console.log("From func " + aData);
        $.ajax({
            url: 'ajaxPost.php',
            type: 'GET',
            data: {
                allData: aData,
            },
            success: function() {
                alert("Your change successfully saved");
            }
        });
    }
</script>

</html>