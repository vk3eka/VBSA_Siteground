<?php //require_once('172.16.10.16/VBSA_Siteground/Connections/connvbsa.php');

include('header.php');
include('connection.inc');

$cal_year = 2023;
$filter = '';

function GetData($i)
{
  global $dbcnx_client;
  $cal_year = 2023;
  $filter = '';
  $query_Cal_01 = "Select event_id, event, venue, state, aust_rank, ranking_type, startdate, finishdate, entry_close,  visible, event_number, attach_name  FROM calendar  LEFT OUTER JOIN calendar_attach ON calendar_attach.event_number = calendar.event_id  WHERE calendar.visible = 'Yes' AND YEAR( startdate )='" . $cal_year . "' AND MONTH( startdate ) =  " . ($i+1) . " " . $filter . " GROUP BY calendar.event_id ORDER BY calendar.startdate";
  $result = $dbcnx_client->query($query_Cal_01);
  $build_data = $result->fetch_assoc();
  return $build_data;

}
for($i = 0; $i < 9; $i++)
{
  $Data[$i] = GetData($i);
  //echo("<pre>");
  //echo(var_dump($Data[$i]));
  //echo("</pre>");
}

for($j = 0; $j < 9; $j++)
{
  echo("<pre>");
  echo(var_dump($Data[$j]));
  echo("</pre>");
  
?>

<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
  <tr>
      <td colspan="11" align="left" bgcolor="#333333" style=" color:#FFF">January</td>
    </tr>
    <?php if(isset($row_Cal_01['event_id'])) { ?>
    <tr>
      <th align="center">Event ID</th>
      <th>Event Name</th>
      <th>Venue</th>
      <th align="center">State</th>
      <th align="center">Aust Rank?</th>
      <th align="center">Ranking Type</th>
      <th align="center">Visible?</th>
      <th align="center">Start Date</th>
      <th align="center">Finish Date</th>
      <th align="center">Entries Close </th>
      <th align="center">&nbsp;</th>
      <th align="center">&nbsp;</th>
    </tr>
    <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_Cal_01['event_id']; ?></td>
      <td><?php echo $row_Cal_01['event']; ?></td>
      <td><?php echo $row_Cal_01['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_01['state']; ?></td>
      <td align="center"><?php echo $row_Cal_01['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_01['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_01['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_01['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_01['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_01['entry_close'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['entry_close'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_01['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_01['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="11" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End January -->
<?php

}

?>

<select class="state">
  <option value="">Select</option>
  <option value="ALL">All States</option>
  <option value="ACT">ACT</option>
  <option value="NSW">NSW</option>
  <option value="NT">NT</option>
  <option value="QLD">QLD</option>
  <option value="SA">SA</option>
  <option value="TAS">TAS</option>
  <option value="VIC">VIC</option>
  <option value="WA">WA</option>
</select>

<select class="vic_rank">
  <option value="">Select</option>
  <option value="No">No Entry</option>
  <option value="Yes">Vic Ranking</option>
  <option value="Women">Women</option>
  <option value="Junior">Junior</option>
</select>

<script>
$(document).ready(function()
{
  $("select").change(function() {
    var State = $('select.state').val();
    var Rank = $('select.vic_rank').val();

    var varRank = Rank;
    switch (varRank) {
      case 'No':
        $whereby = " and ranking_type = 'No Entry'";
        break;
      case 'Yes':
        whereby = " and ranking_type = 'Vic Rank'";
        break;
      case 'Women':
        whereby = " and ranking_type = 'Womens Rank'";
        break;
      case 'Junior':
        whereby = " and ranking_type = 'Junior Rank'";
        break;
      default:
        whereby = "";
        break;
    }

    var varstate = State;
    if(varstate == 'ALL')
    {
     filter = ""; 
    }
    else
    {
      filter = " AND lower(state) = '" + varstate.toLowerCase() + "'";
    }

    alert(filter + " " + whereby);

  });
});
</script>



<!--
<?php 

//include('header.php');

?>
<select class="bedroom-min">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
</select>

<select class="type">
  <option value="all">Select...</option>
  <option value="casitas">Casitas</option>
  <option value="studios">Studios</option>
  <option value="dorm">Dorm</option>
</select>

<select class="bedrooms">
  <option value="all">Select...</option>
  <option value="1">1 bedroom</option>
  <option value="2">2 bedrooms</option>
</select>

<div class="property-load-section">
  <div class="property-item" data-bedrooms="5" data-type="casitas" data-bed="1">Room #529</div>
  <div class="property-item" data-bedrooms="4" data-type="studios" data-bed="2">Room #737</div>
  <div class="property-item" data-bedrooms="3" data-type="dorm" data-bed="2">Room #123</div>
  <div class="property-item" data-bedrooms="2" data-type="studios" data-bed="2">Room #126</div>
  <div class="property-item" data-bedrooms="1" data-type="casitas" data-bed="1">Room #523</div>
</div>


<script>
$(document).ready(function()
{
  $("select").change(function() {
    var minValue = $('select.bedroom-min').val();
    var roomType = $('select.type').val();
    var roomBed = $('select.bedrooms').val();
    alert(minValue + " " + roomType + " " + roomBed);
    $('.property-load-section').find('.property-item').filter(function () {
      return $(this).attr('data-bedrooms') < minValue 
              || ($(this).attr('data-type') != roomType || roomType == "all")
              || ($(this).attr('data-bed') != roomBed || roomBed == "all");
    }).fadeOut('fast');
    $('.property-load-section').find('.property-item').filter(function () {
      return $(this).attr('data-bedrooms') >= minValue 
              && ($(this).attr('data-type') == roomType || roomType == "all")
              && ($(this).attr('data-bed') == roomBed || roomBed == "all");
    }).fadeIn('fast');
  });
});
</script>-->