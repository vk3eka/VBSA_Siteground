<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
  <?php if(isset($row_Cal_01['event_id'])) { ?>
  <tr>
    <th colspan=21  style="background-color: #CCC">Editable Event/Tournament Entries</th>
  </tr>
  <tr>
    <th rowspan=2  style="background-color: #CCC" align="center">Tourn ID</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Event ID</th>
    <th rowspan=2  style="background-color: #CCC"><h2><?= $title ?></h2></th>
    <th rowspan=2  style="background-color: #CCC">Venue</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Start Date</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Finish Date</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Entries Close </th>
    <th rowspan=2  style="background-color: #CCC" align="center">State</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Show on Website</th>
    <th rowspan=2  style="background-color: #CCC" align="center">National Ranking Event</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Attract Vic Ranking</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament</th>
    <th colspan=4  style="background-color: #CCC" align="center">Footers</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament Type</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament Class</th>
    <th rowspan=2  style="background-color: #CCC" align="center">How Seeded</th>
    <th colspan=2 style="background-color: #CCC" align="center">Action</th>
  </tr>
  <tr>
    <th align="center" style="background-color: #CCC">VBSA Event</th>
    <th align="center" style="background-color: #CCC">VBSA Entries</th>
    <th align="center" style="background-color: #CCC">Non VBSA Event</th>
    <th align="center" style="background-color: #CCC">Non VBSA Entries</th>
    <th align="center" style="background-color: #CCC">Add to Non Playing Dates</th>
    <th align="center" style="background-color: #CCC">Delete from Calendar</th>
  </tr>
  <?php 
  $i = 0;
  do { 
    ?>
    <tr>
      <td align="center"><input type='text' id='tourn_id_<?= $month ?>_<?= $i ?>' value='<?php echo $row_Cal_01['tourn_id']; ?>'style="width : 80px;"></td>
      <td align="center"><input type='text' id='event_id_<?= $month ?>_<?= $i ?>' value='<?php echo $row_Cal_01['event_id']; ?>'style="width : 80px;"></td>
      <td align="center"><input type='text' name='event_<?= $month ?>_<?= $i ?>' id='event_<?= $month ?>_<?= $i ?>' value="<?php echo $row_Cal_01['event']; ?>" style="width : 300px;"></td>
      <td><select id='venue_<?= $month ?>_<?= $i ?>'>
      <?php
        // get list of venues
        $query_venue = 'Select * FROM vbsa3364_vbsa2.clubs order by ClubTitle';
        $result_venue = mysql_query($query_venue, $connvbsa) or die(mysql_error());
        if($row_Cal_01['venue'] != '')
        {
          $selected = ' seleced';
        }
        else
        {
          $selected = '';
        }
        echo("<option value='" . $row_Cal_01['venue'] . "' " . $selected . ">" . $row_Cal_01['venue'] . "</option>");
        echo("<option value=''>&nbsp;</option>");
        echo("<option value='Multiple Venues'>Multiple Venues</option>");
        echo("<option value=''>--------------</option>");
        while($build_venue = $result_venue->fetch_assoc())
        {
           echo("<option value='" . $build_venue['ClubTitle'] . "'>" . $build_venue['ClubTitle'] . "</option>");
        }
        ?>
       </select></td>
      <?php
      
      $date = new DateTime($row_Cal_01['startdate']);
      $start_date = $date->format("l Y-m-d");

      $date = new DateTime($row_Cal_01['finishdate']);
      $finish_date = $date->format("l Y-m-d");

      $date = new DateTime($row_Cal_01['closedate']);
      $close_date = $date->format("l Y-m-d");

      ?>
      <td align="center"><input name="startdate_<?= $month ?>_<?= $i ?>" type="text" id="startdate_<?= $month ?>_<?= $i ?>" value="<?php echo ($start_date); ?>" style="width : 150px;">
      </td>
      <td align="center"><input name="finishdate_<?= $month ?>_<?= $i ?>" type="text" id="finishdate_<?= $month ?>_<?= $i ?>" value="<?php echo ($finish_date); ?>" style="width : 150px;">
      </td>
      <td align="center"><input name="closedate_<?= $month ?>_<?= $i ?>" type="text" id="closedate_<?= $month ?>_<?= $i ?>" value="<?php echo ($close_date); ?>" style="width : 150px;">
      </td>
      <td align="center"><select name="state_<?= $month ?>_<?= $i ?>" id="state_<?= $month ?>_<?= $i ?>">
        <?php
        if(isset($row_Cal_01['state']))
        {
          echo("<option value='" . $row_Cal_01['state'] . "' selected>" . $row_Cal_01['state'] . "</option>");
        }
        ?>
          <option value="ACT">ACT</option>
          <option value="NSW">NSW</option>
          <option value="NT">NT</option>
          <option value="Qld">Qld</option>
          <option value="SA">SA</option>
          <option value="Tas">Tas</option>
          <option value="Vic">Vic</option>
          <option value="WA">WA</option>
        </select></td>
        <td align="center"><select name="visible_<?= $month ?>_<?= $i ?>" id="visible_<?= $month ?>_<?= $i ?>">
        <?php
        if(isset($row_Cal_01['visible']))
        {
          echo("<option value='" . $row_Cal_01['visible'] . "' selected>" . $row_Cal_01['visible'] . "</option>");
        }
        ?>
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      <td align="center"><select name="aust_rank_<?= $month ?>_<?= $i ?>" id="aust_rank_<?= $month ?>_<?= $i ?>">
        <?php
        if(isset($row_Cal_01['aust_rank']))
        {
          echo("<option value='" . $row_Cal_01['aust_rank'] . "' selected>" . $row_Cal_01['aust_rank'] . "</option>");
        }
        ?>
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
        <td><select name="ranking_type_<?= $month ?>_<?= $i ?>" id="ranking_type_<?= $month ?>_<?= $i ?>">
          <?php
        if(isset($row_Cal_01['ranking_type']))
        {
          echo("<option value='" . $row_Cal_01['ranking_type'] . "' selected>" . $row_Cal_01['ranking_type'] . "</option>");
        }
        ?>
        <option value="None" <?php if (!(strcmp("None", ""))) {echo "SELECTED";} ?>>None</option>
        <option value="No Entry" <?php if (!(strcmp("No Entry", ""))) {echo "SELECTED";} ?>>No Entry</option>
        <option value="National" <?php if (!(strcmp("National", ""))) {echo "SELECTED";} ?>>National</option>
        <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
        <option value="Womens" <?php if (!(strcmp("Womens", ""))) {echo "SELECTED";} ?>>Womens</option>
        <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
      </select> 
      </td>
      <td align="center"><select name="tourn_<?= $month ?>_<?= $i ?>" id="tourn_<?= $month ?>_<?= $i ?>">
        <?php
        if(isset($row_Cal_01['tourn']))
        {
          echo("<option value='" . $row_Cal_01['tourn'] . "' selected>" . $row_Cal_01['tourn'] . "</option>");
        }
        ?>
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      <td align="center"><input type="checkbox" name="vbsa_event_<?= $month ?>_<?= $i ?>"  id="vbsa_event_<?= $month ?>_<?= $i ?>"  <?php if ($row_Cal_01['footer1'] == "Y"){echo "checked=\"checked\"";} ?> /></td>
      <td align="center"><input type="checkbox" name="vbsa_entries_<?= $month ?>_<?= $i ?>"  id="vbsa_entries_<?= $month ?>_<?= $i ?>"  <?php if ($row_Cal_01['footer2'] == "Y") {echo "checked=\"checked\"";} ?> /></td>
      <td align="center"><input type="checkbox" name="non_vbsa_event_<?= $month ?>_<?= $i ?>"  id="non_vbsa_event_<?= $month ?>_<?= $i ?>"  <?php if ($row_Cal_01['footer3'] == "Y") {echo "checked=\"checked\"";} ?> /></td>
      <td align="center"><input type="checkbox" name="non_vbsa_entries_<?= $month ?>_<?= $i ?>"  id="non_vbsa_entries_<?= $month ?>_<?= $i ?>"  <?php if ($row_Cal_01['footer4'] == "Y") {echo "checked=\"checked\"";} ?> /></td>
      <td align="center"><select name='tourn_type_<?= $month ?>_<?= $i ?>' id='tourn_type_<?= $month ?>_<?= $i ?>'>
        <?= $month ?>_<?= $i ?>">
        <?php
        if(isset($row_Cal_01['tourn_type']))
        {
          echo("<option value='" . $row_Cal_01['tourn_type'] . "' selected>" . $row_Cal_01['tourn_type'] . "</option>");
        }
        ?>
          <option value="Snooker">Snooker</option>
          <option value="Billiards">Billiards</option>
          <option value="Both">Both</option>
        </select></td>
      <td align="center"><select name='tourn_class_<?= $month ?>_<?= $i ?>' id='tourn_class_<?= $month ?>_<?= $i ?>'>>
           <?php
        if(isset($row_Cal_01['tourn_class']))
        {
          echo("<option value='" . $row_Cal_01['tourn_class'] . "' selected>" . $row_Cal_01['tourn_class'] . "</option>");
        }
        ?>
          <option value="Victorian">Victorian</option>
          <option value="Aust Rank">Aust Rank</option>
          <option value="Junior">Junior</option>
        </select></td>
      <td><select name="how_seed_<?= $month ?>_<?= $i ?>" id="how_seed_<?= $month ?>_<?= $i ?>">
         <?php
        if(isset($row_Cal_01['how_seed']))
        {
          echo("<option value='" . $row_Cal_01['how_seed'] . "' selected>" . $row_Cal_01['how_seed'] . "</option>");
        }
        ?>
        <option value="Not Applicable" <?php if (!(strcmp("Not Applicable", ""))) {echo "SELECTED";} ?>>Not Applicable</option>
          <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", ""))) {echo "SELECTED";} ?>>Aust Rankings</option>
          <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", ""))) {echo "SELECTED";} ?>>Victorian Rankings</option>
          <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", ""))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
          <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic WomensRankings", ""))) {echo "SELECTED";} ?>>Victorian Womens Rankings</option>
          <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", ""))) {echo "SELECTED";} ?>>Junior Rankings</option>
        </select></td>
      <td align="center"><input type="checkbox" name="copy_to_non_dates_<?= $month ?>_<?= $i ?>"  id="copy_to_non_dates_<?= $month ?>_<?= $i ?>"  <?php if ($row_Cal_01['special_dates'] == "Y"){echo "checked=\"checked\"";} ?> /></td>
      <!--<td align="center"><input type="checkbox" name="copy_to_non_dates_<?= $month ?>_<?= $i ?>"  id="copy_to_non_dates_<?= $month ?>_<?= $i ?>" /></td>-->
      <td align="center"><input type="checkbox" name="delete_<?= $month ?>_<?= $i ?>"  id="delete_<?= $month ?>_<?= $i ?>" /></td>
    </tr>
    <?php 
      $i++;
  } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); 
  $no_of_events = $i;
  ?>
  <input type='hidden' id='no_of_events' value='<?= $no_of_events ?>'>
  <?php } else { ?>
  <tr>
    <th colspan=21  style="background-color: #CCC">Editable Calendar Entries</th>
  </tr>
  <tr>
    <input type='hidden' id='event_id' value='<?php echo $row_Cal_01['event_id']; ?>'>
    <th rowspan=2  style="background-color: #CCC" align="center">Tourn ID</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Event ID</th>
    <th rowspan=2  style="background-color: #CCC"><h2><?= $title ?></h2></th>
    <th rowspan=2  style="background-color: #CCC">Venue</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Start Date</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Finish Date</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Entries Close </th>
    <th rowspan=2  style="background-color: #CCC" align="center">State</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Show on Website</th>
    <th rowspan=2  style="background-color: #CCC" align="center">National Ranking Event</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Attract Vic Ranking</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament</th>
    <th colspan=4  style="background-color: #CCC" align="center">Footers</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament Type</th>
    <th rowspan=2  style="background-color: #CCC" align="center">Tournament Class</th>
    <th rowspan=2  style="background-color: #CCC" align="center">How Seeded</th>
    <th colspan=2 style="background-color: #CCC" align="center">Action</th>
  </tr>
  <tr>
    <th align="center" style="background-color: #CCC">VBSA Event</th>
    <th align="center" style="background-color: #CCC">VBSA Entries</th>
    <th align="center" style="background-color: #CCC">Non VBSA Event</th>
    <th align="center" style="background-color: #CCC">Non VBSA Entries</th>
    <th align="center" style="background-color: #CCC">Add to Non Playing Dates</th>
    <th align="center" style="background-color: #CCC">Delete from List</th>
  </tr>
  <tr>
    <td colspan="21" align="center">No events listed</td>
  </tr>
  <?php } ?>
</table>
<br>
<br>
<?php 
if($totalRows_Cal_01 > 0)
{
?>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td colspan=20 align="center"><button type='button' style='width:250px' onclick="SaveSelectedChangesButton(<?= $month ?>, <?= $no_of_events ?>)">Save <?= $title ?> Edits</button></td>
    <td colspan=20 align="center"><button type='button' style='width:250px' onclick="DeleteSelectedButton(<?= $month ?>, <?= $no_of_events ?>)">Delete from  <?= $title ?> List</button></td>
  </tr>
  <tr>
    <td colspan=20>&nbsp;</td>
  </tr>
</table>
<?php } ?>