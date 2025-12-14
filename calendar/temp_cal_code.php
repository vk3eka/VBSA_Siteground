
<!--Open January-->
  <div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
      	<th nowrap="nowrap" style="background-color: #CCC">January</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_01['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
      	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['event']; ?></td>
      	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['state']; ?></td>
        <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['venue']; ?></td>
        <?php
        if(($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "Victorian"))
        {
          $ranking = 'Vic/Nat';
        }
        else if(($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "Womens"))
        {
          $ranking = 'Womens/Nat';
        }
        else if(($row_Cal_01['aust_rank'] == "Yes") && ($row_Cal_01['ranking_type'] == "Junior"))
        {
          $ranking = 'Junior/Nat';
        }
        else if($row_Cal_01['aust_rank'] == "Yes")
        {
          $ranking = 'National';
        }
        else
        {
          $ranking = $row_Cal_01['ranking_type'];
        }
        ?>
        <!--<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_01['ranking_type']; ?></td>-->
        <td nowrap="nowrap" style="min-width:30%"><?php echo $ranking; ?></td>
      	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_01['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_01['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_01['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_01['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_01['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_01['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_01['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_01 = mysql_fetch_assoc($Cal_01)); ?>
        </tbody>
	</table>
  </div>
  <!--Close January-->
  
<!--Open Feb-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<tr>
        <th nowrap="nowrap" style="background-color: #CCC">February</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
      </tr>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_02['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_02['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_02['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_02['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_02['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_02['closedate'])) 
    	echo '<span class="italic">'. "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_02['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_02['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_02['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_02 = mysql_fetch_assoc($Cal_02)); ?>
        </tbody>
	</table>
</div>
  <!--Close Feb-->

<!--Open Mar-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<tr>
        <th nowrap="nowrap" style="background-color: #CCC">March</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
      </tr>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_03['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
      	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['event']; ?></td>
      	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['state']; ?></td>
        <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['venue']; ?></td>
        <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_03['ranking_type']; ?></td>
      	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_03['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_03['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_03['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_03['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_03['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_03['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_03['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_03 = mysql_fetch_assoc($Cal_03)); ?>
        </tbody>
	</table>
</div>
<!--Close Mar-->

<!--Open Apr-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
    	<tr>
        <th nowrap="nowrap" style="background-color: #CCC">April</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
      </tr>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_04['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_04['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_04['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_04['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_04['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_04['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_04['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_04['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_04['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_04 = mysql_fetch_assoc($Cal_04)); ?>
        </tbody>
	</table>
</div>
<!--Close April-->

<!--Open May-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
      	<th nowrap="nowrap" style="background-color: #CCC">May</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_05['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_05['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_05['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_05['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_05['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_05['closedate'])) 
    	echo '<span class="italic">'. "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_05['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_05['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_05['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_05 = mysql_fetch_assoc($Cal_05)); ?>
        </tbody>
	</table>
</div>
<!--Close May-->


<!--Open June-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">June</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_06['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_06['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_06['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_06['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_06['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_06['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_06['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_06['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_06['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_06 = mysql_fetch_assoc($Cal_06)); ?>
        </tbody>
	</table>
</div>
<!--Close June-->

<!--Open July-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">July</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_07['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_07['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_07['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_07['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_07['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_07['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_07['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_07['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_07['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_07 = mysql_fetch_assoc($Cal_07)); ?>
        </tbody>
	</table>
</div>
<!--Close July-->

<!--Open Aug-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">August</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_08['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_08['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_08['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_08['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_08['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_08['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_08['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_08['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_08['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_08 = mysql_fetch_assoc($Cal_08)); ?>
        </tbody>
	</table>
</div>
<!--Close Aug-->

<!--Open Sept-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">September</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_09['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_09['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_09['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_09['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_09['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_09['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_09['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_09['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_09['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_09 = mysql_fetch_assoc($Cal_09)); ?>
        </tbody>
	</table>
</div>
<!--Close Sept-->

<!--Open Oct-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">October</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_10['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_10['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_10['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_10['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_10['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_10['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_10['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_10['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_10['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_10 = mysql_fetch_assoc($Cal_10)); ?>
        </tbody>
	</table>
</div>
<!--Close Oct-->

<!--Open Nov-->
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">November</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>
    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_11['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_11['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_11['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_11['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_11['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_11['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_11['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_11['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_11['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_11 = mysql_fetch_assoc($Cal_11)); ?>
        </tbody>
	</table>
</div>
<!--Close Nov-->

<!--Open Dec--> 
<div class="table-responsive center-block" style="max-width:1000px; padding-left:3px"> <!-- class table-responsive -->
  <table class="table">
  	<thead>
  		<tr>
        <th nowrap="nowrap" style="background-color: #CCC">December</th>
        <th nowrap="nowrap" style="background-color: #CCC">State</th>
        <th nowrap="nowrap" style="background-color: #CCC">Venue</th>
        <th nowrap="nowrap" style="background-color: #CCC">Ranking</th>
        <th colspan="4" nowrap="nowrap" style="background-color: #CCC">&nbsp;</th>    	</tr>
  	</thead>
	<?php if(!isset($row_Cal_12['event'])) 
	echo '<tbody>'. '<tr>' . '<td colspan="7">' . "No events Scheduled" . '</td>' . '</tr>' . '</tbody>'; else do { ?>
    <tbody>   
  		<tr>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['event']; ?></td>
    	<td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['state']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['venue']; ?></td>
      <td nowrap="nowrap" style="min-width:30%"><?php echo $row_Cal_12['ranking_type']; ?></td>
    	<td nowrap="nowrap">
		<?php //Start Date
		echo '<span class="italic"> Start: </span>';
		$StartDate = date("M j", strtotime($row_Cal_12['startdate'])); echo $StartDate; 
		?>
    	</td>
		<td>
		<?php //Finish Date
		if(isset($row_Cal_12['finishdate']))
		{
		echo '<span class="italic"> Finish: </span>';		
		$FinishDate = date("M j", strtotime($row_Cal_12['finishdate'])); echo $FinishDate;
		}
		else echo ""; ?>
        </td>
        <td>
		<?php 
		// Close Date
		if (empty($row_Cal_12['closedate'])) 
    	echo '<span class="italic">' . "Close: na" . '</span>';
		else 
		{
		echo '<span class="italic"> Close: </span>';		
		$CloseDate = date("M j", strtotime($row_Cal_12['closedate'])); echo $CloseDate;
		}
		?>
        </td>
    	<td class="text-right">
    	<?php if(empty($row_Cal_12['event'])) echo ""; else { ?>
    	<a href="cal_index_detail.php?event_id=<?php echo $row_Cal_12['event_id']; ?>" class="btn btn-primary btn-xs " role="button">More</a>
    	<?php } ?>
    	</td>
  		</tr>
  		<?php } while ($row_Cal_12 = mysql_fetch_assoc($Cal_12)); ?>
        </tbody>
	</table>
</div>
<!--Close Dec-->