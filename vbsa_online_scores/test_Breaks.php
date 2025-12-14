<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>
$(document).ready(function()
{

 $(".break").click(function()
  {
    $('#breakid').val($(this).attr('id'));
    $('#BreaksModal').modal('show');
  });

  // add breaks to selected player
  $('#new_break').click(function(event){
    event.preventDefault();
    var breaks = $('#break_value');
    var break_id = $('#breakid');
    var box_value = breaks.val();
    var total_breaks = '';
    //$('#all_breaks').val(''); // clean all breaks input box on modal
    $('.break').each(function()
    {
      if(break_id.val() == $(this).attr('id'))
      {
        $(this).val(box_value);
        total_breaks = box_value + ", " + $('#all_breaks').val();
        $(this).val(total_breaks);

        //test_id = $(this).attr('id');
        //alert($('#' + test_id).val());

      }
    });
    $('#all_breaks').val(total_breaks); // all breaks input box on modal
    $("#break_value" ).val(''); // reset break input box to empty
    $('#BreaksModal').modal('hide');

  });

});  
</script>

<center>
<table width='80%'>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align=center>Input Box ID</td>
    <td align=center>Breaks</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php
  for($i = 0; $i < 2; $i++) // no of games
  {
    $j = 0; // no of players
    echo("<tr>");
    echo("<td align=center>breaks_" . $i . "_" . $j . "</td>");
    echo("<td align=center><input type='text' id='breaks_" . $i . "_" . $j . "' style='width:150px' class='form-control input-sm break'></td>");
    echo("</tr>");

    echo("<tr>");
    echo("<td align=center>breaks_" . $i . "_" . ($j+1) . "</td>");
    echo("<td align=center><input type='text' id='breaks_" . $i . "_" . ($j+1) . "' value='' style='width:150px' class='form-control input-sm break'></td>");
    echo("</tr>");

    echo("<tr>");
    echo("<td align=center>breaks_" . $i . "_" . ($j+2) . "</td>");
    echo("<td align=center><input type='text' id='breaks_" . $i . "_" . ($j+2) . "'value='' style='width:150px' class='form-control input-sm break'></td>");
    echo("</tr>");
    $j++;
  }
  ?>
</table>
</center>

<div class="modal fade" id="BreaksModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header ui-front">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Breaks</h4>
            </div>
            <div class="modal-body">
              <?php
                echo("<div id='breakid'></div>");
                echo("<br>");
                echo("<div class='text-center ui-widget'>");
                echo("<label for='breaks'>Add Breaks for this player:&nbsp;&nbsp;</label>");
                echo("<input type='text' id='break_value' style='width:50px' >");
                echo("<br>");
                echo("<br>");
                echo("<div><a class='btn btn-primary btn-xs' id='new_break'>Add to Scoresheet</a>");
                echo("<br>");
                echo("<div></div>");
                echo("<br>");
                echo("<div></div>");
                echo("</div>");
                echo("<br>");
                echo("<label for='all_breaks'>Breaks already entered.</label>");
                echo("<div><input type='text' id='all_breaks' style='width:250px' ></div>");
                echo("</div>");
                echo("<br>");
                echo("<br>");
              ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

