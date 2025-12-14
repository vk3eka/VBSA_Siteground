<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
  <tr>
      <td colspan="13" align="left" bgcolor="#333333" style=" color:#FFF"><?= $title ?></td>
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
      <th align="center">&nbsp;</th>
    </tr>
    <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_Cal_01['event_id']; ?></td>
      <td><?php echo $row_Cal_01['event']; ?></td>
      <td><?php echo $row_Cal_01['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_01['state']; ?></td>
      <td align="center"><?php echo $row_Cal_01['aust_rank']; ?></td>
      <?php
      // Determine Ranking
      if (($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "Victorian")) {
        $ranking = 'Vic/Nat';
      } elseif (($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "Womens")) {
        $ranking = 'Womens/Nat';
      } elseif (($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "Junior")) {
        $ranking = 'Junior/Nat';
      } elseif (($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "National")) {
        $ranking = 'National';
      } elseif (($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "None")) {
        $ranking = 'National';
      } elseif (($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "No Entry")) {
        $ranking = 'National';
      } else {
        $ranking = $row_Cal_01['ranking_type'];
      }
      ?>
      <td align="center"><?php echo $ranking; ?></td>
      <td align="center"><?php echo $row_Cal_01['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_01['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_01['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_01['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_01['event_id']; ?>&page=calendar"><img src="../Admin_Images/edit_butt.fw.png" height="20" /></a></td>
      <td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_01['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_01['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End January -->