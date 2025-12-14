<?php 

include('connection.inc');
include('header.php'); 


$players = array();
$sql = "Select FirstName, LastName from members Order By Surname";
$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;
if ($num_rows != 0) 
{
  while($build_data = $result_players->fetch_assoc()) 
  {
    $players[] = $build_data['LastName'] . " " . $build_data['FirstName']; 
  }
  $player_data = json_encode($players);
}

?>
<!--Content--> 
<div class="">
  <div class="page-title"></div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
      </div>
      <div class="x_content"> 
      <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
      <tr>
       <td align="center"><h1>Add a new Player</h1></td>
      </tr>
      <tr>
       <td align="center">Choice 1</td>
      </tr>
      <tr> 
       <td >
        <div class="text-center ui-widget"><label for="tags">Select Player from Dropdown:&nbsp;&nbsp;</label><input id="tags">&nbsp;&nbsp;<a class='btn btn-primary btn-xs' href='javascript:;' onclick='AddButton();'>Add to Fixture List</a>
        </div>
      </td>
      </tr>
      </table>
      <script>
      $(document).ready(function() 
      { 
        var availableTags = <?php echo $player_data; ?>;
        $("#tags").autocomplete({
          source:  availableTags
        });
      });
      </script>
      <br>
      <table class='table table-striped table-bordered dt-responsive nowrap display' width='100%'>
        <tr>
         <td colspan='7' align="center">Choice 2</td>
        </tr>
        <tr> 
          <td class='text-left'><b>ID</td>
          <td class='text-left'><b>Surname (Family Name)</td>
          <td class='text-left'><b>First Name</b></td>
          <td class='text-center'><b>Email Address</b></td>
          <td class='text-center'><b>Jnr</b></td>
          <td class='text-center'><b>Mobile</b></td>
          <td class='text-center'><b>Action</b></td>
        </tr>
        <?php
        $sql = "Select ID, FirstName, LastNameame, EmailAddress from members Order By LastName";
        $result_players = $dbcnx_client->query($sql);
        $num_rows = $result_players->num_rows;
        $i = 0;
        if ($num_rows != 0) 
        {
          while($build_data = $result_players->fetch_assoc()) 
          {
            echo("<tr>"); 
            echo("<td class='text-left'><input type='text' id='id_" . $i . "' class='form-control input-sm' value=" . $build_data['ID'] . "></td>"); 
            echo("<td class='text-left'><input type='text' id='surname_" . $i . "' class='form-control input-sm' value='" . $build_data['LastName'] . "'></td>"); 
            echo("<td class='text-left'><input type='text' id='firstname_" . $i . "' class='form-control input-sm' value='" . $build_data['FirstName'] . "'></td>"); 
            echo("<td class='text-center'><input type='text' id='email_" . $i . "' class='form-control input-sm' value='" . $build_data['EmailAddress'] . "'></td>"); 
            echo("<td class='text-center'><input type='text' id='jnr_" . $i . "' class='form-control input-sm'></td>"); 
            echo("<td class='text-center'><input type='text' id='mobile_" . $i . "' class='form-control input-sm' value=''></td>"); 
            //echo("<td class='text-center'><input type='text' id='mobile_" . $i . "' class='form-control input-sm' value='" . $build_data['PhoneMobile'] . "'></td>"); 
            echo("<td>");
            echo("<div> ");
            echo("  <div class='text-center'>");
            echo("    <a class='btn btn-primary btn-xs' href='javascript:;' onclick='AddButton('" . $build_data['ID'] . "');'>Select Player</a>");
            echo("  </div>");
            echo(" </div> ");
            echo("</tr>"); 
            $i++;
          }
        }
        ?>
        </table>
        <div> 
          <div class='text-center'>
            <a class='btn btn-primary btn-xs'  href='<?= $url ?>/workinprogress.php'>Save New Players</a>
          </div>
        </div> 
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>
