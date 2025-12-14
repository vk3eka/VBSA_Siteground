<?php
	  // establish if item is ordered on any of the web pages
	if($row_FPitems['OrderFP']=='not ordered' && $row_FPitems['OrderRef']=='not ordered' && $row_FPitems['OrderJunior']=='not ordered' && $row_FPitems['OrderHelp']=='not ordered' && $row_FPitems['OrderWomens']=='not ordered' && $row_FPitems['OrderRefProfile']=='not ordered' && $row_FPitems['OrderRefPoser']=='not ordered' && $row_FPitems['OrderScores']=='not ordered' && $row_FPitems['OrderInfo']=='not ordered' && $row_FPitems['OrderPolProc']=='not ordered' && $row_FPitems['OrderPlayerProfile']=='not ordered')
	{
		// if not ordered
	 	echo "Item is not ordered"; 
	}
	else
	{
		// if it is ordered
		echo " Ordered: ";
	}
	
    if($row_FPitems['OrderFP']<>'not ordered')
		{
		echo "Front = ";
		echo $row_FPitems['OrderFP'];
		echo ". ";
		}
		else echo "";

    if($row_FPitems['OrderRef']<>'not ordered')
		{
		echo "Referees = ";
		echo $row_FPitems['OrderRef'];
		echo ". ";
		}
		else echo "";

    if($row_FPitems['OrderJunior']<>'not ordered')
		{
		echo "Junior = ";
		echo $row_FPitems['OrderJunior'];
		echo ". ";
		}
		else echo "";

	if($row_FPitems['OrderHelp']<>'not ordered')
		{
		echo "Help = ";
		echo $row_FPitems['OrderHelp'];
		echo ". ";
		}
		else echo "";
		
	if($row_FPitems['OrderWomens']<>'not ordered')	
		{
		echo "Womens = ";
		echo $row_FPitems['OrderWomens'];
		echo ". ";
		}
		else echo "";
		
			
	if($row_FPitems['OrderRefProfile']<>'not ordered')	
		{
		echo "Ref Profiles = ";
		echo $row_FPitems['OrderRefProfile'];
		echo ". ";
		}
		else echo "";
		
	if($row_FPitems['OrderRefPoser']<>'not ordered')	
		{
		echo "Ref Posers = ";
		echo $row_FPitems['OrderRefPoser'];
		echo ". ";
		}
		else echo "";
		
	if($row_FPitems['OrderScores']<>'not ordered')	
		{
		echo "Scores = ";
		echo $row_FPitems['OrderScores'];
		echo ". ";
		}
		else echo "";
		
	if($row_FPitems['OrderPlayerProfile']<>'not ordered')	
		{
		echo "Player Profile = ";
		echo $row_FPitems['OrderPlayerProfile'];
		echo ". ";
		}
		else echo "";
		
	if($row_FPitems['OrderPolProc']<>'not ordered')	
		{
		echo "Policies & Procedures = ";
		echo $row_FPitems['OrderPolProc'];
		echo ". ";
		}
		else echo "";	
		
		// previously info page
	if($row_FPitems['OrderInfo']<>'not ordered')	
		{
		echo "About = ";
		echo $row_FPitems['OrderInfo'];
		echo ". ";
		}
		else echo "";
	?>