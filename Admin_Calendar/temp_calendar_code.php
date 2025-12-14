
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
  <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">January</td>
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
      <td align="center">
        <?php if ($row_Cal_01['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_01['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_01['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_01['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
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
  
  <!-- Feb -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">February</td>
    </tr>
    <?php if(isset($row_Cal_02['event_id'])) { ?>
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
    <?php do 
      { ?>
    <tr>
      <td align="center"><?php echo $row_Cal_02['event_id']; ?></td>
      <td><?php echo $row_Cal_02['event']; ?></td>
      <td><?php echo $row_Cal_02['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_02['state']; ?></td>
      <td align="center"><?php echo $row_Cal_02['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_02['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_02['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_02['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_02['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_02['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_02['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_02['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_02['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_02['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_02['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_02['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_02 = mysql_fetch_assoc($Cal_02)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Feb -->
  
    <!-- Mar -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">March</td>
    </tr>
    <?php if(isset($row_Cal_03['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_03['event_id']; ?></td>
      <td><?php echo $row_Cal_03['event']; ?></td>
      <td><?php echo $row_Cal_03['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_03['state']; ?></td>
      <td align="center"><?php echo $row_Cal_03['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_03['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_03['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_03['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_03['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_03['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_03['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_03['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_03['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_03['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_03['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_03['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_03 = mysql_fetch_assoc($Cal_03)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Mar -->
  
    <!-- Apr -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">April</td>
    </tr>
    <?php if(isset($row_Cal_04['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_04['event_id']; ?></td>
      <td><?php echo $row_Cal_04['event']; ?></td>
      <td><?php echo $row_Cal_04['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_04['state']; ?></td>
      <td align="center"><?php echo $row_Cal_04['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_04['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_04['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_04['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_04['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_04['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_04['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_04['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_04['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_04['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_04['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_04['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_04 = mysql_fetch_assoc($Cal_04)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Apr -->
  
    <!-- May -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">May</td>
    </tr>
    <?php if(isset($row_Cal_05['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_05['event_id']; ?></td>
      <td><?php echo $row_Cal_05['event']; ?></td>
      <td><?php echo $row_Cal_05['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_05['state']; ?></td>
      <td align="center"><?php echo $row_Cal_05['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_05['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_05['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_05['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_05['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_05['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_05['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_05['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_05['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_05['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_05['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_05['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_05 = mysql_fetch_assoc($Cal_05)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End May -->
  
    <!-- Jun -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">June</td>
    </tr>
    <?php if(isset($row_Cal_06['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_06['event_id']; ?></td>
      <td><?php echo $row_Cal_06['event']; ?></td>
      <td><?php echo $row_Cal_06['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_06['state']; ?></td>
      <td align="center"><?php echo $row_Cal_06['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_06['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_06['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_06['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_06['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_06['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_06['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_06['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_06['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_06['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_06['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_06['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_06 = mysql_fetch_assoc($Cal_06)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Feb -->
  
    <!-- July -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">July</td>
    </tr>
    <?php if(isset($row_Cal_07['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_07['event_id']; ?></td>
      <td><?php echo $row_Cal_07['event']; ?></td>
      <td><?php echo $row_Cal_07['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_07['state']; ?></td>
      <td align="center"><?php echo $row_Cal_07['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_07['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_07['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_07['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_07['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_07['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_07['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_07['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_07['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_07['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_07['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_07['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_07 = mysql_fetch_assoc($Cal_07)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Jul -->
  
    <!-- Aug -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">August</td>
    </tr>
    <?php if(isset($row_Cal_08['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_08['event_id']; ?></td>
      <td><?php echo $row_Cal_08['event']; ?></td>
      <td><?php echo $row_Cal_08['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_08['state']; ?></td>
      <td align="center"><?php echo $row_Cal_08['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_08['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_08['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_08['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_08['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_08['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_08['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_08['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_08['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_08['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_08['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_08['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_08 = mysql_fetch_assoc($Cal_08)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Aug -->
  
    <!-- Sept -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">September</td>
    </tr>
    <?php if(isset($row_Cal_09['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_09['event_id']; ?></td>
      <td><?php echo $row_Cal_09['event']; ?></td>
      <td><?php echo $row_Cal_09['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_09['state']; ?></td>
      <td align="center"><?php echo $row_Cal_09['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_09['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_09['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_09['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_09['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_09['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_09['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_09['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_09['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_09['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_09['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_09['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_09 = mysql_fetch_assoc($Cal_09)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Sept -->
  
    <!-- Oct -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">October</td>
    </tr>
    <?php if(isset($row_Cal_10['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_10['event_id']; ?></td>
      <td><?php echo $row_Cal_10['event']; ?></td>
      <td><?php echo $row_Cal_10['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_10['state']; ?></td>
      <td align="center"><?php echo $row_Cal_10['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_10['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_10['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_10['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_10['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_10['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_10['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_10['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_10['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_10['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_10['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_10['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_10 = mysql_fetch_assoc($Cal_10)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Oct -->
  
    <!-- Nov -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">November</td>
    </tr>
    <?php if(isset($row_Cal_11['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_11['event_id']; ?></td>
      <td><?php echo $row_Cal_11['event']; ?></td>
      <td><?php echo $row_Cal_11['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_11['state']; ?></td>
      <td align="center"><?php echo $row_Cal_11['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_11['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_11['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_11['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_11['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_11['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_11['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_11['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_11['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_11['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_11['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_11['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_11 = mysql_fetch_assoc($Cal_11)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>
  <p>&nbsp;</p>
  <!-- End Nov -->
  
      <!-- Dec -->
</table>
<table border="1" align="center" cellpadding="3" cellspacing="3" class="tableborder" width="1000">
    <tr>
      <td colspan="12" align="left" bgcolor="#333333" style=" color:#FFF">December</td>
    </tr>
    <?php if(isset($row_Cal_12['event_id'])) { ?>
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
      <td align="center"><?php echo $row_Cal_12['event_id']; ?></td>
      <td><?php echo $row_Cal_12['event']; ?></td>
      <td><?php echo $row_Cal_12['venue']; ?></td>
      <td align="center"><?php echo $row_Cal_12['state']; ?></td>
      <td align="center"><?php echo $row_Cal_12['aust_rank']; ?></td>
      <td align="center"><?php echo $row_Cal_12['ranking_type']; ?></td>
      <td align="center"><?php echo $row_Cal_12['visible']; ?></td>
      <td align="center"><?php if ($row_Cal_12['startdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_12['startdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><?php if ($row_Cal_12['finishdate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_12['finishdate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center">
        <?php if ($row_Cal_12['closedate'] != ''): ?>
        <?php $newDate = date("M d, Y", strtotime($row_Cal_012['closedate'])); echo $newDate; ?>
        <?php endif; ?></td>
      <td align="center"><a href="../Admin_Tournaments/edit_previous_tournament.php?eventID=<?php echo $row_Cal_12['event_id']; ?>&page=calendar"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>
      <!--<td align="center"><a href="calendar_detail.php?eventID=<?php echo $row_Cal_12['event_id']; ?>"><img src="../Admin_Images/detail.fw.png" height="20" /></a></td>-->
      <td align="center"><a href="user_files/event_delete_confirm.php?eventID=<?php echo $row_Cal_12['event_id']; ?>&cal_year=<?php echo $cal_year; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" /></a></td>
    </tr>
    <?php } while ($row_Cal_12 = mysql_fetch_assoc($Cal_12)); ?>
    <?php } else { ?>
    <tr>
      <td colspan="12" align="left">No events listed</td>
    </tr>
    <?php } ?>
  </table>

  <p>&nbsp;</p>
  <!-- End Dec -->
