<?php

require('connection.inc');
require('header_fixture.php');

?>
<div class="container">
<h3 class="text-center">Dynamic Drag and Drop table rows in PHP Mysql - ItSolutionStuff.com</h3>
<table id="myTable" class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">#</th>
        <th scope="col">Name</th>
    </tr>
    </thead>
    <tbody  class="row_position">
    <?php
    $sql = "SELECT * FROM tbl_authorise";
    $users = $dbcnx_client->query($sql);
    while($user = $users->fetch_assoc()){
        ?>
        <tr id="<?php echo $user['ID'] ?>"> <!-- data-channel-number="<?php echo $user['PlayerNo'] ?>">-->
            <td><?php echo $user['Name'] ?></td>
            <td class="index"><?php echo $user['PlayerNo'] ?></td>
            <td><?php echo $user['Access'] ?></td>

        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
</body>
<script>
var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        return $helper;
    },
    updateIndex = function(e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
            $(this).text(i+1);
        });
    };

    $("#myTable tbody").sortable({
        distance: 5,
        //delay: 100,
        opacity: 0.6,
        cursor: 'move',
        helper: fixHelperModified,
        update: function() {
            var chArray = [];
            $('.row_position>tr').each(function() {
                chArray.push({
                    chid : $(this).attr("id"),
                    chnumber : $(this).closest('tr').find('td.index').text()
                });
            });
            console.log(chArray);
            /*$.ajax({
                url:"ajaxPro.php",
                type:'post',
                data:{position:chArray},
                success:function(data){
                    console.log(data);
                    //alert('your change successfully saved');


                },
                error: function (error) {
                console.log(error);

                }
            })*/

        },
        stop: updateIndex
    }).disableSelection();

</script>
</html>
