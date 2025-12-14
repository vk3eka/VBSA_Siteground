<table width="150" align="right">
  <tr>
    <td valign="middle"><a href="../Admin_web_pages/item_detail.php?item_id=<?php echo $row_page_items['ID']; ?>"><img src="../Admin_Images/detail.fw.png" width="25" height="25" title="More Detail, Edit, upload image" /></a></td>
    <td>Item ID : <?php echo $row_page_items['ID']; ?></td>
    </tr>
  <tr>
    <td colspan="2"><?php
	  // establish if item is ordered on any of the web pages
	if($row_page_items['OrderFP']=='not ordered' && $row_page_items['OrderRef']=='not ordered' && $row_page_items['OrderJunior']=='not ordered' && $row_page_items['OrderHelp']=='not ordered' && $row_page_items['OrderWomens']=='not ordered' && $row_page_items['OrderRefProfile']=='not ordered' && $row_page_items['OrderRefPoser']=='not ordered' && $row_page_items['OrderPlayerProfile']=='not ordered' && $row_page_items['OrderScores']=='not ordered' && $row_page_items['OrderPolProc']=='not ordered' && $row_page_items['OrderAbout']=='not ordered')
	{
		// if not ordered
	 	echo "Item is not ordered"; 
	}
	else
	{
		// if it is ordered
		echo " Ordered: ";
	}
	
    if($row_page_items['OrderFP']<>'not ordered')
		{
		echo "Front = ";
		echo $row_page_items['OrderFP'];
		echo ". ";
		}
		else echo "";

    if($row_page_items['OrderRef']<>'not ordered')
		{
		echo "Referees = ";
		echo $row_page_items['OrderRef'];
		echo ". ";
		}
		else echo "";

    if($row_page_items['OrderJunior']<>'not ordered')
		{
		echo "Junior = ";
		echo $row_page_items['OrderJunior'];
		echo ". ";
		}
		else echo "";

	if($row_page_items['OrderHelp']<>'not ordered')
		{
		echo "Help = ";
		echo $row_page_items['OrderHelp'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderWomens']<>'not ordered')	
		{
		echo "Womens = ";
		echo $row_page_items['OrderWomens'];
		echo ". ";
		}
		else echo "";
		
			
	if($row_page_items['OrderRefProfile']<>'not ordered')	
		{
		echo "Ref Profiles = ";
		echo $row_page_items['OrderRefProfile'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderRefPoser']<>'not ordered')	
		{
		echo "Ref Posers = ";
		echo $row_page_items['OrderRefPoser'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderScores']<>'not ordered')	
		{
		echo "Scores = ";
		echo $row_page_items['OrderScores'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderPlayerProfile']<>'not ordered')	
		{
		echo "Player Profile = ";
		echo $row_page_items['OrderPlayerProfile'];
		echo ". ";
		}
		else echo "";
		
	if($row_page_items['OrderPolProc']<>'not ordered')	
		{
		echo "Policies & Procedures = ";
		echo $row_page_items['OrderPolProc'];
		echo ". ";
		}
		else echo "";
	
	// previously info page	
	if($row_page_items['OrderAbout']<>'not ordered')	
		{
		echo "About = ";
		echo $row_page_items['OrderAbout'];
		echo ". ";
		}
		else echo "";
		?>
      </td>
    </tr>
</table>