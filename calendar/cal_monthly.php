<!-- Open January -->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
    <thead>
      <tr>
        <th nowrap="nowrap" style="background-color: #CCC"><?= $title ?></th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th nowrap="nowrap" style="background-color: #CCC">Start</th>
        <th nowrap="nowrap" style="background-color: #CCC">Finish</th>
        <th nowrap="nowrap" style="background-color: #CCC">Close</th>
        <th nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
      </tr>
    </thead>

    <?php if (!isset($row_Cal_01['event'])) { ?>
      <tbody>
        <tr>
          <td colspan="8">No events Scheduled</td>
        </tr>
      </tbody>
    <?php } else { ?>
      <tbody>
        <?php do { ?>
          <tr>
            <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['event']; ?></td>
            <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['state']; ?></td>
            <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['venue']; ?></td>
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
            <td nowrap="nowrap" style="min-width:30%"><?php echo $ranking; ?></td>

            <!-- Start Date -->
            <td nowrap="nowrap">
              <?php echo !empty($row_Cal_01['startdate']) ? date("M j", strtotime($row_Cal_01['startdate'])) : "N/A"; ?>
            </td>

            <!-- Finish Date -->
            <td nowrap="nowrap">
              <?php echo !empty($row_Cal_01['finishdate']) ? date("M j", strtotime($row_Cal_01['finishdate'])) : "N/A"; ?>
            </td>

            <!-- Close Date -->
            <td nowrap="nowrap">
              <?php echo !empty($row_Cal_01['closedate']) ? date("M j", strtotime($row_Cal_01['closedate'])) : "N/A"; ?>
            </td>

            <td class="text-right">
              <?php if (!empty($row_Cal_01['event'])) { ?>
                <a href="cal_index_detail.php?event_id=<?php echo $row_Cal_01['event_id']; ?>" class="btn btn-primary btn-xs" role="button">More</a>
              <?php } ?>
            </td>
          </tr>
        <?php } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); ?>
      </tbody>
    <?php } ?>
  </table>
</div>
<!-- Close January -->